<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
require_once dirname(__DIR__) . "\controllers\message.php";
class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
            $data = json_decode($msg,true);
            $user_id = $data['userId']; // user id
            $username = $data['name']; // username
            $sent_msg = $data['msg']; // message
            // set the time of the chat
            $data['time'] = date('y-m-d h:i:s');
            $date = $data['time'];
            // instantiate the message class
            $message = new \Message;
            // pass the data to the method to insert into the database
            $message->message_data($user_id,$sent_msg,$username,$date);

        foreach ($this->clients as $client) {
            if ($from == $client) {
                // The sender is not the receiver, send to each client connected
                $data['from'] = "me";
            }
            else{
                $data['from'] = $username;
            }
            $client->send(json_encode($data));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
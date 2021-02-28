<?php
class Message{

    public function __construct(){
        require_once dirname(__DIR__) . "/connection/database.php";
        $database = new Database;
        $this->conn = $database->connect();
    }

    // insert data into the database
    public function message_data($user_id,$sent_msg,$username,$date){
        $query = "INSERT INTO messages(user_id,msg,username,created_on) 
        VALUES(?,?,?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('isss',$user_id,$sent_msg,$username,$date);
        $stmt->execute();
    }
    
    // fetch the messages from the database
    public function fetch_msg(){
        $data = null;
        $query = "SELECT * FROM messages";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()){
            $result = $stmt->get_result();
            while($fetch = $result->fetch_assoc()){
                $data[] = $fetch; 
            }
        }
        return $data;
    }
}
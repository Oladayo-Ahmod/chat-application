<?php
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);
session_start();
if ($_SESSION['id'] < 1) {
    header('location:../index.php');
    exit;
}
else{
    $user_id = $_SESSION['id']; // get user id
    require_once('../controllers/user.php'); // require the user controller
    $user = new User; // instantiate the User class
    $profiles = $user->getProfile($user_id); // fetch the user data by the id
    require_once('../controllers/message.php'); // require the message controller
    $msg_class = new Message; // instantiate Message class
    $messages = $msg_class->fetch_msg(); // fetch all messages
    $user = new User; // instantiate the User class
    $display_users = $user->get_users();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat application | Chat room</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/fontawesome.min.css">
    <link rel="stylesheet" href="../css/all.css">
</head>
<body>
    <div class="container chat-room">
        <div class="row">
            <div class="col-md-8 my-4">
                <h4 class="text-secondary shadow chat-head mb-4 text-center">Chat Room</h4>
                <div class="shadow chat-box" id="message_area">
                    <?php
                        foreach ($messages as $message) {
                            if ($_SESSION['id'] == $message['user_id']) {
                                $from  = "me";
                                $bg_class = 'text-dark alert-light';
                                $row ='row justify-content-end';
                            }
                            else{
                                $from = $message['username'];
                                $bg_class = 'text-dark alert-success';
                                $row ='row justify-content-start';
                            }
                            $msg_style = 'word-break:break-all;font-size:14px;padding:2px 2px;';
                            echo  "<div class='".$row."'><div class='my-1 col-sm-10'><div style='".$msg_style."' class=' shadow-sm alert ".$bg_class.
                            "'><b>".$from.' . '."</b>".$message['msg']."<br/><div class='text-right'><small><i>".$message['created_on']."</i></small></div></div></div></div>";

                        }
                    ?>
                </div>
                <form action="" method="POST" id="chat-form">
                    <div class="input-group">
                        <textarea class="chat-message shadow" name="message"
                        data-parsley-pattern="/^[a-zA-Z0-9\s]+$/" data-parsley-maxLength="1000"
                         id="message" rows="2" placeholder="Type a message here..."></textarea>
                        <div class="input-group-append">
                            <button name="send" id="send" class="btn btn-primary btn-sm"><i class=" fa fa-paper-plane"></i></button>
                        </div>
                        <!-- setting user id to the form -->
                        <input type="hidden" id="user_id" value="<?= $user_id;?>">
                    </div>
                    <div class="text-danger" id="validation_error"></div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="card shadow profile">
                    <?php
                    foreach($profiles as $profile){?>
                    <img class="card-img-top" src="../<?=$profile['profile'];?>" alt="">
                    <div class="card-body">
                        <input type="hidden" id="username" value="<?= $profile['username'];?>">
                        <p class="text-center "><i class="fa fa-circle"> </i><?=$profile['username']; ?></p>
                    <?php } ?>
                        <div class="flex-btn">
                            <a href="profile.php" class="btn btn-primary">Profile</a>
                            <a href="../logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                </div>
                <ul class="list-group my-4">
                    <li class="list-group-item active">Users list</li>
                    <?php
                        foreach($display_users as $users){
                            if ($users['id'] !== $_SESSION['id']) {
                             echo ' <li class="list-group-item">
                             <img style="border-radius:50%;" src="../'.$users['profile'].'"class="mr-2" alt="profile picture" height="50px;" width="50px">'
                             .$users['username'].'<i style="font-size:10px;" class="fa ml-2 fa-circle text-danger"> </i>
                         </li>';
                            }
                        }
                    ?>
                   
                    
                </ul> 
            </div>
        </div>
    </div>



<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/script.js"></script>  
<script src="../js/parsley.min.js"></script> 
</script>
</body>
</html>
<?php
session_start();
if ($_SESSION['id'] < 1) {
    header('location:../index.php');
    exit;
}
else{
    $user_id = $_SESSION['id'];
    require_once('../controllers/user.php');
    $user = new User;
    $profiles = $user->getProfile($user_id);
    // $username = $user->getUsername($user_id);
    // print_r($profiles);
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
                <h4 class="text-secondary shadow chat-head text-center">Chat Room</h4>
                <div class="shadow chat-box">
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
                        <div id="validation_error"></div>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="card shadow profile">
                    <?php
                    foreach($profiles as $profile){?>
                    <img class="card-img-top" src="../<?=$profile['profile'];?>" alt="">
                    <div class="card-body">
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
                    <li class="list-group-item">Item</li>
                    <li class="list-group-item disabled">Disabled item</li>
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
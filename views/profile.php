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
}
// if the update button is clicked without changing picture
if (isset($_POST['update']) && empty($_FILES['picture'])) {
    $user_id = $_SESSION['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $confirm = md5($_POST['c_password']);
    // instantiate the class
    $user = new User;
    $user->update_user($user_id,$username,$email,$password,$confirm);
    $data = $user->update_user($user_id,$username,$email,$password,$confirm);
    $error = $data['error'];
}
// if the update button is clicked with changing picture
else if (isset($_POST['update']) && !empty($_FILES['picture'])) {
    $user_id = $_SESSION['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $picture = $_FILES['picture'];
    $password = md5($_POST['password']);
    $confirm = md5($_POST['c_password']);
    // instantiate the class
    $user = new User;
    $user->update_user($user_id,$username,$email,$password,$confirm);
    $data = $user->update_user($user_id,$username,$email,$password,$confirm);
    $error = $data['error'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat application | User profile</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/fontawesome.min.css">
    <link rel="stylesheet" href="../css/all.css">
</head>
<body>
    <div class="container chat-room">
        <div class="row">
            <div class="col-md-8 mb-4">
                <h4 class="text-secondary shadow chat-head text-center">User Profile</h4>
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-4 my-3">
                        <?php 
                        foreach($profiles as $profile){?>
                         <div class="profile">
                            <img class="card-img-top" src="<?=$profile['profile'];?>" alt="">
                        </div>
                    </div>
                </div>
                <!-- error message  -->
                            <?php
                            if (!empty($error)) {
                                echo $error;
                            }
                            ?>
                <!-- error mesage ends -->
                <form action="" method="POST" class="shadow p-2" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" class="form-control" value="<?=$profile['username'];?>" name="username" required>
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="text" class="form-control" name="email" value="<?=$profile['email'];?>" required placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="status">Status</label>
                      <input type="text" readonly class="form-control" name="status" value="<?=$profile['status'];?>d" id="" aria-describedby="emailHelpId" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="username">Joined Since</label>
                      <input type="text" readonly class="form-control" value="
                      <?php 
                        $date = $profile['created_on'];
                        $date = strtotime($date);
                        $date = date('F, Y',$date);
                        echo $date;
                      ?>"  id="" aria-describedby="emailHelpId" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="password">Change password</label>
                      <input type="password" required class="form-control" name="password" id="" aria-describedby="emailHelpId" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="password">Confirm password</label>
                      <input type="password" required class="form-control" name="c_password" id="" aria-describedby="emailHelpId" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="status">Change Picture</label>
                      <input type="file" class="form-control" name="picture" id="" aria-describedby="emailHelpId" placeholder="">
                    </div>
                    <button class="btn btn-primary" name="update">Update</button>
                </form>
                    <?php } ?>
            </div>
            <div class="col-md-4">
                <div class="card shadow profile">
                    <?php
                    foreach($profiles as $profile){?>
                    <img class="card-img-top" src="<?=$profile['profile'];?>" alt="">
                    <div class="card-body">
                        <p class="text-center "><i class="fa fa-circle"> </i><?=$profile['username']; ?></p>
                    <?php } ?>
                        <div class="flex-btn">
                            <a href="chatroom.php" class="btn btn-primary">Chat room</a>
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
</body>
</html>
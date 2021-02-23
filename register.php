<?php
    // if the form is submitted
    if (isset($_POST['register']) && empty($_FILES['picture'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $confirm = md5($_POST['c_password']);
        $email = $_POST['email'];
        $user_status = "enable";
        $login_status = "online";
        $date = date('y-m-d h:i:s');
        $verification_code = md5(md5(microtime(true)));
        require_once('controllers/user.php');
        $user = new User;
        $register = $user->register_user($username,$password,$confirm,$email,$user_status,$login_status,$date,$verification_code);
        $error = $register['error_message'];        
    }
    else if (isset($_POST['register']) && !empty($_FILES['picture'])) {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $confirm = md5($_POST['c_password']);
        $email = $_POST['email'];
        $user_status = "enable";
        $login_status = "online";
        $date = date('y-m-d h:i:s');
        $verification_code = md5(md5(microtime(true)));
        require_once('controllers/user.php');
        $user = new User;
        $register = $user->register_user($username,$password,$confirm,$email,$user_status,$login_status,$date,$verification_code);
        $error = $register['error_message'];   
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.css">
</head>
<body>
    <div class="container mt-5">
        <h3 class="text-center">Register</h3>
        <div class="row justify-content-center align-items-center">
            <div class="col-md-5 register shadow p-2">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <!-- error message -->
                    <?php
                        if (!empty($error)) {
                            echo $error;
                        }
                    ?>
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" name="username" id="" required class="form-control" placeholder="" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="text" name="email" id="" required class="form-control" placeholder="" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" name="password" required id="" class="form-control" placeholder="" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                      <label for="password">Confirm password</label>
                      <input type="password" name="c_password" required id="" class="form-control" placeholder="" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                      <label for="image">Profile picture</label><br>
                      <input type="file" name="picture" id="" placeholder="" aria-describedby="helpId">
                    </div>
                    <button class="btn btn-primary my-2" name="register">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
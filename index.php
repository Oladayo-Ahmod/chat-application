<?php
    // if the form is submitted
    if (isset($_POST['login'])) {
        $password = md5($_POST['password']);
        $email = $_POST['email'];
        require_once('controllers/user.php');
        $user = new User;
        $login = $user->login($password,$email);
        $error = $login['error_message'];        
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Application | Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/all.css">
</head>
<body>
    <div class="container login mt-5">
        <h3 class="text-center">Login</h3>
        <div class="row justify-content-center align-items-center">
            <div class="col-md-4 register shadow p-2">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <!-- error message -->
                    <?php
                        if (!empty($error)) {
                            echo $error;
                        }
                    ?>
                    
                    <div class="form-group">
                      <label for="email">Email</label>
                      <input type="text" name="email" id="" required class="form-control" placeholder="" aria-describedby="helpId">
                    </div>
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" name="password" required id="" class="form-control" placeholder="" aria-describedby="helpId">
                    </div>
                    
                    <div style="display:flex;justify-content:space-between;">
                        <button class="btn btn-danger my-2" name="login">Login</button>
                        <a href="register.php" class="btn btn-primary my-2 " name="login">Sign Up</a>
                    </div>
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
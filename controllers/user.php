<?php
    class User{
        // private $user_id;
        // private $user_name;
        // private $user_password;
        // private $user_email;
        // private $user_profile;
        // private $user_created_on;
        // private $login_status;
        // private $connect;

        public function __construct(){
            require_once('database.php');
            $database = new Database;
            $this->conn = $database->connect();
        }

        // user registration method
        public function register_user($username,$password,$confirm,$email,$user_status,$login_status,$date,$verification_code){
            $error = '';
            // if image is empty
            if (isset($_POST['register']) && empty($_FILES['picture'])) {
                // check if email already exists in the database
                $check = "SELECT id FROM users WHERE email = ?";
                $stmt = $this->conn->prepare($check);
                $stmt->bind_param('s',$email);
                $stmt->execute();
                $result = $stmt->get_result();
                if (mysqli_num_rows($result) > 0) {
                    $error = '<div class="alert alert-warning" role="alert">Email aready taken!</div>';
                }
                else if ($password !== $confirm) {
                    $error = '<div class="alert alert-warning" role="alert">Passwords do not match</div>';
                }
                else{
                    // insert into the database
                    $query = "INSERT INTO users
                    (`email`,`username`,`password`,`status`,`login_status`,`created_on`,`verification_code`)
                    VALUE(?,?,?,?,?,?,?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('sssssss',$email,$username,$password,$user_status,$login_status,$date,$verification_code);
                    if ($stmt->execute()){
                        $user_id = mysqli_insert_id($this->conn);
                        $hash = md5(md5($password).$user_id);
                        $update = "UPDATE users SET `password`=? WHERE id =? LIMIT 1";
                        $stmt = $this->conn->prepare($update);
                        $stmt->bind_param('si',$hash,$user_id);
                        if ($stmt->execute()) {
                            $error = '<div class="alert alert-success" role="alert">Registered successfully</div>';                            
                        }
                        else{
                            $error = '<div class="alert alert-warning" role="alert">Error occurs</div>';
                        }
                    }
                    else{
                        $error = '<div class="alert alert-warning" role="alert">Error registering try later!</div>';
                    }
                }
            }
            else if (isset($_POST['register']) && !empty($_FILES['picture'])) {
                // check if email already exists in the database
                $check = "SELECT id FROM users WHERE email = ?";
                $stmt = $this->conn->prepare($check);
                $stmt->bind_param('s',$email);
                $stmt->execute();
                $result = $stmt->get_result();
                // check if the email is already chosen by another user
                if (mysqli_num_rows($result) > 0) {
                    $error = '<div class="alert alert-warning" role="alert">Email already taken!</div>';
                }
                // check if the password match
                else if ($password !== $confirm) {
                    $error = '<div class="alert alert-warning" role="alert">Passwords do not match</div>';
                }
                // check if it is an image
                else if(!preg_match("!image!",$_FILES['picture']['type'])){
                    $error = '<div class="alert alert-warning" role="alert">Please select an Image! </div>';
                }
                else{
                    $explode = explode(".",$_FILES['picture']['name']);
                    $path = "images/" .round(microtime(true)) . '.'. strtolower(end($explode));
                    move_uploaded_file($_FILES['picture']['tmp_name'],$path);
                    // insert into the database
                    $query = "INSERT INTO users
                    (`email`,`username`,`password`,`status`,`login_status`,`created_on`,`verification_code`,`profile`)
                    VALUE(?,?,?,?,?,?,?,?)";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('ssssssss',$email,$username,$password,$user_status,$login_status,$date,$verification_code,$path);
                    if ($stmt->execute()){
                        $user_id = mysqli_insert_id($this->conn);
                        $hash = md5(md5($password).$user_id);
                        $update = "UPDATE users SET `password`=? WHERE id =? LIMIT 1";
                        $stmt = $this->conn->prepare($update);
                        $stmt->bind_param('si',$hash,$user_id);
                        if ($stmt->execute()) {
                            $error = '<div class="alert alert-success" role="alert">Registered successfully</div>';                            
                        }
                        else{
                            $error = '<div class="alert alert-warning" role="alert">Error occurs</div>';
                        }
                    }
                    else{
                        $error = '<div class="alert alert-warning" role="alert">Error registering try later!</div>';
                    }
                }
            }
                $data['error_message'] = $error;
                return $data;
        }

        // user login method
        public function login($password,$email){
                // error message 
                $error = '';
                $query = "SELECT * FROM `users` WHERE `email`= ? ";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('s',$email);
                if($stmt->execute()){
                    $result = $stmt->get_result();
                    if (mysqli_num_rows($result) > 0) {
                        $fetch = $result->fetch_assoc();
                        //check if the password with one in the database match
                        $hash = md5(md5($password).$fetch['id']);
                        // print_r($fetch['password']);
                        if ($fetch['password'] ==  $hash) {
                            //setting the session id
                            $_SESSION['id'] = $fetch['id'];
                            // setting session username
                            $_SESSION['username'] = $fetch['username'];
                            //redirect to the dashboard if the passwords match
                            header('location:views/chatroom.php');
                        }
                        else{
                            $error = '<div class="alert alert-danger">An error occurs try later</div>';
                        }
                    }
                    else{
                        $error = '<div class="alert alert-danger">Incorrect Email or Password </div>';
                    }
                }
                    $data['error_message'] = $error;
                    return $data;
            }

            // get profile of the user
            public function getProfile($user_id){
                $error = '';
                $data = null;
                $query = "SELECT * FROM users WHERE id = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param('i',$user_id);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    while($fetch = $result->fetch_assoc()){
                        $data[] = $fetch;
                    }
                }
                else{
                    $error = '<div class="alert alert-danger">An error occurs try later</div>';
                }

                $message['error'] = $error; 
                return $data;
            }
            // update user method
            public function update_user($user_id,$username,$email,$password,$confirm){
                $error = '';
                if(isset($_POST['update']) && empty($_FILES['picture'])){
                    $hash = md5(md5($password).$user_id);
                    $query = "UPDATE `users` SET username = ? , `email` = ? ,`password` = ? WHERE id = ? LIMIT 1";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param('sssi',$username,$email,$hash,$user_id);
                    if ($stmt->execute()){
                        $error = '<div class="alert alert-success">Profile update successfully</div>';
                    }
                    else{
                        $error = '<div class="alert alert-danger">Error updating profile</div>';
                    }
                }
                // update the profile if picture is not null
                else if (isset($_POST['update']) && !empty($_FILES['picture'])) {
                     // check if the password match
                    if ($password !== $confirm) {
                        $error = '<div class="alert alert-warning" role="alert">Passwords do not match</div>';
                    }
                    // check if it is an image
                    else if(!preg_match("!image!",$_FILES['picture']['type'])){
                        $error = '<div class="alert alert-warning" role="alert">Please select an Image! </div>';
                    }
                    else{
                        $explode = explode(".",$_FILES['picture']['name']);
                        $path = "../images/" .round(microtime(true)) . '.'. strtolower(end($explode));
                        move_uploaded_file($_FILES['picture']['tmp_name'],$path);
                        $hash = md5(md5($password).$user_id);
                        $query = "UPDATE `users` SET username = ? , `email` = ? ,`password` = ?, `profile` = ? WHERE id = ? LIMIT 1";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param('ssssi',$username,$email,$hash,$path,$user_id);
                        if ($stmt->execute()){
                            $error = '<div class="alert alert-success">Profile update successfully</div>';
                        }
                        else{
                            $error = '<div class="alert alert-danger">Error updating profile</div>';
                        }
                    }
                }
        
                $message['error'] = $error;
                return $message;
        }

    }
?>
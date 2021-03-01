<?php
session_start();
$user_id = $_SESSION['id'];
require_once('controllers/user.php');
$user = new User;
$user->change_login_status($user_id);
session_unset();
session_destroy();
header('location:index.php');
?>
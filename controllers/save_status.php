<?php
require_once dirname(__DIR__) . "/connection/database.php";
$database = new Database;
$conn = $database->connect();
if (isset($_POST['status'])) {
    $user_id = $_POST['user_id'];
    $st = $_POST['status'];
    $check = "SELECT * FROM statuses WHERE user_id = ?";
    $stmt = $conn->prepare($check);
    $stmt->bind_param('i',$user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    // check if status already exist in the database
    if (mysqli_num_rows($result) > 0) {
        $query = "UPDATE statuses SET `status` = ? WHERE user_id = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si',$st,$user_id);
        $stmt->execute();
    }
    else{
        $query = "INSERT INTO statuses(`status`,`user_id`) VALUES(?,?) ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('si',$st,$user_id);
        $stmt->execute();
    }
    
    // to be continued 1 check if status has already uploaded if not insert else update
}

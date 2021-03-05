<?php
session_start();
require_once dirname(__DIR__) . "/connection/database.php";
$database = new Database;
$conn = $database->connect();
$user_id = $_SESSION['id'];
$query = "SELECT `status` FROM statuses WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i',$user_id);
$stmt->execute();
$result = $stmt->get_result();
if (mysqli_num_rows($result) > 0) {
    while($fetch = $result->fetch_assoc()){
        $data = $fetch;
    }
    echo $data['status'];
}
else{
    echo "no status yet";
}
?>


<?php

require_once '../dbconnect.php';
session_start();
$email = $_POST["Email"];
$Password = $_POST["Password"];
$sql = "select * from login where Email='$email' and Password ='$Password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {

    $responce = array(
        "Status" => true,
        "msg" => 'Benvenuto.'
    );
    while ($row = $result->fetch_assoc()) {
        $_SESSION["admin_id"] = $row['id'];
        $_SESSION["firstname"] = $row['name'];
    };
} else {
    $responce = array(
        "Status" => false,
        "msg" => 'Si è verificato un problema.'
    );
}
echo json_encode($responce);
?>

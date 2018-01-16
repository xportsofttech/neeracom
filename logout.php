<?php

session_start();
require_once 'dbconnect.php';
$d = getdate();
$time = $d['hours'] . ":" . $d['minutes'] . ":" . $d['seconds'];
$date = $d['year'] . "-" . $d['mon'] . "-" . $d['mday'];

$dt = $date . " " . $time;
$date = date_create($dt);
$dtime = date_format($date, "Y-m-d H:i:s");
$update = "update  login_report set logout_date_time='" . $dtime . "' where id='" . $_SESSION["login_report_id"] . "'";

$result = $conn->query($update);
if ($result) {
    session_destroy();
    header('location:index.php');
}
?> 

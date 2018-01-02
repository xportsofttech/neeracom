<?php

require_once 'dbconnect.php';
$sql = "SELECT * FROM `login_report` WHERE logout_date_time IS NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['id'] = $fname . " " . $lname;
    $d = getdate();
    $time = $d['hours'] . ":" . $d['minutes'] . ":" . $d['seconds'];
    $date = $d['year'] . "-" . $d['mon'] . "-" . $d['mday'];

    $dt = $date . " " . $time;
    $date = date_create($dt);
    $dtime = date_format($date, "Y-m-d H:i:s");
    $update = "update login_report set logout_date_time='" . $dtime . "' WHERE logout_date_time IS NULL";
    $result = $conn->query($update);
    if ($result) {
        $responce = array(
            "Status" => true,
            "msg" => 'welcome.'
        );
        @session_destroy();
    }
} else {
    $responce = array(
        "Status" => false,
        "msg" => 'something went wrong.'
    );
}
echo json_encode($responce);
?>

<?php

session_start();
require_once 'dbconnect.php';
$time = date("Y-m-d H:i:s");
$recaptcha_demo = $_POST["recaptcha_demo"];
$number = $_POST["number"];

if ($number == "") {
    $responce = array(
        "status" => false,
        "msg" => 'Riempire i campi.',
    );
    $return = json_encode($responce);
    echo $return;
    exit;
}
if ($_SESSION['id'] == '') {
    if ($recaptcha_demo == "") {
        $responce = array(
            "status" => false,
            "msg" => 'Selezionare il box nel modulo reCAPTCHA.',
        );
        $return = json_encode($responce);
        echo $return;
        exit;
    }
}

$number = mysql_escape_string($number);
$number = explode(";", $number);
if ($_SESSION['id'] == '') {
    if (count($number) > 1) {
        $responce = array(
            "status" => false,
            "msg" => 'Inserire solo un numero.',
        );
        $return = json_encode($responce);
        echo $return;
        exit;
    }
}
$aa = '';
$i = 1;
foreach ($number as $value) {
    if ($i != 1)
        $aa .= ' OR ';

    $aa .= "text like '%$value%'";

    $a .= "$value" . ',';

    $i++;
}
$nr = count($number);
if ($nr > 1) {
    $number1 = 'multiple';
} else {
    $number1 = 'single';
}
$sql = "select * from information where $aa";
$result = $conn->query($sql);



if ($result->num_rows == 0) {

    $datas = array();
    $data = array();
    $data[] = "";
    $data[] = "";
    $data[] = "";
    $datas[] = $data;
    $match_record = array();
    foreach ($datas as $idx => $value) {
        $match = array();

        foreach ($number as $number_value) {
            if (strpos($value[0], $number_value) !== false) {
                $match[] = $number_value;

                if (!in_array($number_value, $match_record)) {
                    $match_record[] = $number_value;
                }
            }
        }

        $datas[$idx][] = implode(",", $match);
    }

    $not_match = array_diff($number, $match_record);
    foreach ($not_match as $value) {
        $datas[] = array("-", "-", "-", $value);
    }
    $responce = array(
        "status" => TRUE,
        "data" => $datas
    );
    $return = json_encode($responce);
    echo $return;
    exit;
}
if ($result->num_rows > 0) {
    $row = array();
    $datas = array();
    while ($row = $result->fetch_assoc()) {

        $data = array();
        $d = array();
        $d = $row['text'];
        $data[] = utf8_encode($row['text']);
        $data[] = utf8_encode($row['text1']);
        $data[] = utf8_encode($row['text2']);
        $datas[] = $data;
        $da[] = $d;
    }
    $match_record = array();
    foreach ($datas as $idx => $value) {
        $match = array();

        foreach ($number as $number_value) {
            if (strpos($value[0], $number_value) !== false) {
                $match[] = $number_value;

                if (!in_array($number_value, $match_record)) {
                    $match_record[] = $number_value;
                }
            }
        }

        $datas[$idx][] = implode(",", $match);
    }
    $not_match = array_diff($number, $match_record);
    foreach ($not_match as $value) {
        $datas[] = array("-", "-", "-", $value);
    }
    foreach ($da as $value) {
        if ($i != 1)
            $ak .= "$value" . ',';

        $i++;
    }
    $_SESSION["a"] = $a;
    $_SESSION["ak"] = $ak;
    $insert = "insert into Verify(Activities,request,response,date_created,ip,UserId) values('" . $number1 . "','" . $a . "','" . $ak . "','" . $time . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $_SESSION['userid'] . "')";

    $conn->query($insert);
    $_SESSION["last_id"] = $conn->insert_id;
    $_SESSION["data"] = $datas;
    $json_data = array(
        "status" => TRUE,
        "data" => $datas   // total data array
    );

    echo json_encode($json_data);
}
$conn->close();
?>

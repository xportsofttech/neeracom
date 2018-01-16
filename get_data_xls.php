<?php

session_start();
require_once 'dbconnect.php';
$time = date("Y-m-d H:i:s");
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'assets/PHPExcelReader/PHPExcel.php';
include 'assets/PHPExcelReader/PHPExcel/IOFactory.php';

$time = date("Y-m-d H:i:s");

if (isset($_SESSION['file_name']) == '') {
    $responce = array(
        "status" => false,
        "msg" => 'Caricare file xlsx.',
    );
    $return = json_encode($responce);
    echo $return;
    exit;
}
$recaptcha_demo = $_POST["recaptcha_demo"];
if (isset($_SESSION['id']) == '') {
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
$xls = "assets/upload/" . $_SESSION['file_name'];

/* try {
    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $inputFileType = PHPExcel_IOFactory::identify($xls);

    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($xls);
}

 catch (Exception $e) {
    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
}; */


//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();
$aa = '';
$i = 1;
$data = array();
$a_mach = array();
$temp = null;
$a = null;
//  Loop through each row of the worksheet in turn
for ($row = 1; $row <= $highestRow; $row++) {

    //  Read a row of data into an array
     $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

    reset($rowData);
    foreach ($rowData[0] as $value) {

        $v=strpos($value,".");
		echo $v;
        if ($v != 0) {
			
			
           $json_data = array(
                "status" => FALSE,
                "msg" => "Caratteri non permessi."  // total data array
            );
            echo json_encode($json_data);
			
            exit;
        }
		
	
        if (empty($value)) {
            continue;
        }
        if ($temp)
            $temp .= ' OR ';
        $temp .= " text  like  '%$value%'";
        $a .= "$value" . ',';
        $a_mach[] .= $value;
    }
}
$sql = "select * from information where $temp";
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

        foreach ($a_mach as $number_value) {
            if (strpos($value[0], $number_value) !== false) {
                $match[] = $number_value;

                if (!in_array($number_value, $match_record)) {
                    $match_record[] = $number_value;
                }
            }
        }

        $datas[$idx][] = implode(",", $match);
    }
    $not_match = array_diff($a_mach, $match_record);
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
    };
    $match_record = array();
    foreach ($datas as $idx => $value) {
        $match = array();

        foreach ($a_mach as $number_value) {
            if (strpos($value[0], $number_value) !== false) {
                $match[] = $number_value;

                if (!in_array($number_value, $match_record)) {
                    $match_record[] = $number_value;
                }
            }
        }

        $datas[$idx][] = implode(",", $match);
    }
    $not_match = array_diff($a_mach, $match_record);
    foreach ($not_match as $value) {
        $datas[] = array("-", "-", "-", $value);
    }
    $ak = 0;
    foreach ($da as $value) {
        if ($i != 1)
            $ak .= "$value" . ',';

        $i++;
    }
    $_SESSION["a"] = $a;
    $_SESSION["ak"] = $ak;
    $insert = "insert into Verify(Activities,request,response,date_created,ip,UserId) values('" . "file" . "','" . $a . "','" . $ak . "','" . $time . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $_SESSION['userid'] . "')";
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

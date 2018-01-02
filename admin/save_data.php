<?php

@session_start();
require_once '../dbconnect.php';
include '../assets/PHPExcelReader/PHPExcel.php';
include '../assets/PHPExcelReader/PHPExcel/IOFactory.php';

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

include '../excel_reader.php';
$xls = "../assets/upload/" . $_SESSION['file_name'];
//
try {
    PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
    $inputFileType = PHPExcel_IOFactory::identify($xls);

    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($xls);
} catch (Exception $e) {
    die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
};

//  Get worksheet dimensions
$sheet = $objPHPExcel->getSheet();
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();

$TRUNCATE = 'TRUNCATE TABLE  information';
mysqli_query($conn, $TRUNCATE);
//  Loop through each row of the worksheet in turn
for ($row = 1; $row <= $highestRow; $row++) {
    //  Read a row of data into an array

    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
    $text = mysqli_real_escape_string($conn, $rowData[0][0]);
    $text1 = mysqli_real_escape_string($conn, $rowData[0][1]);
    $text2 = mysqli_real_escape_string($conn, $rowData[0][2]);
    $query = "insert into information(text,text1,text2) values('" . $text . "','" . $text1 . "','" . $text2 . "')";
    $data = array();
    $data['id'] = $row;
    $data['text'] = $rowData[0][0];
    $data['text1'] = $rowData[0][1];
    $data['text2'] = $rowData[0][2];

    $data = array_map('utf8_encode', $data);
    mysqli_query($conn, $query);
    $p = ($row / $highestRow ) * 100; //Progress
    send_message($serverTime, $data, $p);
}

send_message($serverTime, 'Complete  100%');

function send_message($id, $message, $progress) {
    $d = array('message' => $message, 'progress' => $progress);
    echo "data: " . json_encode($d) . PHP_EOL;
    echo PHP_EOL;
    ob_flush();
    flush();
}
?>


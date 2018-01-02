<?php

@session_start();
require_once '../dbconnect.php';


header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
session_start();

include '../excel_reader.php';
$xls = "../assets/upload/" . $_SESSION['file_name'];
$excel = new PhpExcelReader;

$excel->read($xls);
$return = array();
for ($i = 0; $i < count($excel->sheets); $i++) { // Loop to get all sheets in a file.
    $total_count = $excel->sheets[$i]['numRows'];

    if (count($excel->sheets[$i]['cells']) > 0) { // checking sheet not empty
        $TRUNCATE = 'TRUNCATE TABLE  information';

        mysqli_query($conn, $TRUNCATE);

        for ($j = 1; $j <= count($excel->sheets[$i]['cells']); $j++) {
            $text = mysqli_real_escape_string($conn, $excel->sheets[$i]['cells'][$j][1]);
            $text1 = mysqli_real_escape_string($conn, $excel->sheets[$i]['cells'][$j][2]);
            $text2 = mysqli_real_escape_string($conn, $excel->sheets[$i]['cells'][$j][3]);
            $query = "insert into information(text,text1,text2) values('" . $text . "','" . $text1 . "','" . $text2 . "')";

            $data = array();
            $data['id'] = $j;
            $data['text'] = $excel->sheets[$i]['cells'][$j][1];
            $data['text1'] = $excel->sheets[$i]['cells'][$j][2];
            $data['text2'] = $excel->sheets[$i]['cells'][$j][3];

            $data = array_map('utf8_encode', $data);
            mysqli_query($conn, $query);
            $p = ($j / $total_count ) * 100; //Progress
            send_message($serverTime, $data, $p);
        }
    }
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


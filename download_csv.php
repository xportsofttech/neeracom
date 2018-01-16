<?php

session_start();

require_once 'dbconnect.php';
$time = date("Y-m-d H:i:s");
require ('assets/PHPExcelReader/PHPExcel.php');
//require ('assets/PHPExcelReader/PHPExcel/Writer/Excel2007.php');
require ('assets/PHPExcelReader/PHPExcel/IOFactory.php');

ob_start();
$num = 0;
if (!empty($_SESSION['data']) && $_SESSION['data'] != 0 && isset($_SESSION['data'])) {
    foreach ($_SESSION['data'] as $d) {

        $prod[$num]['test'] = $d[0];
        $prod[$num]['test1'] = $d[1];
        $prod[$num]['test2'] = $d[2];
        $prod[$num]['match'] = $d[3];

        $num++;
    }
    ini_set("display_errors", 1);

/// Create a new Excel file from an array using PHPExcel 
    $objPHPExcel = new PHPExcel();
    $header = array('Partita IVA', 'Denominazione', 'Elenco', 'Verifica');
    
    $objPHPExcel->getActiveSheet()->getDefaultColumnDimension()->setWidth(12);

    $objPHPExcel->getActiveSheet()->fromArray($header, null, 'A1');
    $objPHPExcel->getActiveSheet()->fromArray($prod, null, 'A2');

// Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="split-payment-check_tool_results.csv"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');

    $objWriter->save('php://output');
    $insert = "insert into Verify(Activities,request,response,date_created,ip,UserId) values('download csv','" . $_SESSION["a"] . "','" . $_SESSION["ak"] . "','" . $time . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $_SESSION['userid'] . "')";
    $conn->query($insert);
}



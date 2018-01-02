<?php

session_start();
$time = date("Y-m-d H:i:s");
require_once 'dbconnect.php';
include('config.php');

require 'assets/phpmailkit/PHPMailerAutoload.php';
require ('assets/PHPExcelReader/PHPExcel.php');
require ('assets/PHPExcelReader/PHPExcel/Writer/Excel2007.php');

$mail = new PHPMailer;
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
    $filename = uniqid() . ".xlsx";

    $objPHPExcel = new PHPExcel();
    $header = array('text', 'text1', 'text2', 'match');

    $objPHPExcel->getActiveSheet()->fromArray($header, null, 'A1');
    $objPHPExcel->getActiveSheet()->fromArray($prod, null, 'A2');

    $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

//$targetPath = Mage::getBaseDir() . DS  ;

    $objWriter->save('assets/pdf-attachments/' . $filename);
}

$Username = $GLOBALS['UserName'];
$Password = $GLOBALS['PassWord'];
$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'ssl://smtp.gmail.com';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = "$Username";                 // SMTP username
$mail->Password = "$Password";                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465;                                    // TCP port to connect to

$mail->setFrom("$Username", 'verification');
$mail->addAddress($_POST['to']);     // Add a recipient
// Name is optional
$mail->addReplyTo("$Username");
$mail->addCC($_POST['cc']);
$mail->addBCC($_POST['bcc']);

$mail->addAttachment('assets/pdf-attachments/' . $filename);         // Add attachments

$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = $_POST['sub'];
$mail->Body = "<pre>" . $_POST['msg'] . "<pre>";
if (!$mail->send()) {
    session_destroy();
    echo 'E-Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo;
} else {
    $last_id = $_SESSION["last_id"];
    $to = $_POST['to'];
    $insert = "UPDATE Verify SET send_email='$to',pdf_name='assets/pdf-attachments/$filename' WHERE id=$last_id";
    $conn->query($insert);
//    session_destroy();
    echo 'E-Mail has been sent';
}
?>
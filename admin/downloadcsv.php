<?php
//include database configuration file
require_once '../dbconnect.php';
include '../config.php';

//get records from database
$query = mysqli_query($conn,"select l.*,v.* from login_report as l,Verify as v where l.user_id=v.UserId");
$num_rows=mysqli_num_rows($query);
if($num_rows > 0){

    $delimiter = ",";
    $filename = "logDetail" . date('Y-m-d') . ".csv";
    
    //create a file pointer
    $f = fopen('php://memory', 'w');
    
    //set column headers
    $fields = array( 'User Id', 'ActionType', 'Login Time', 'Logout Time');
    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row=mysqli_fetch_assoc($query)){
        $lineData = array($row['user_id'], $row['Activities'], $row['login_date_time'], $row['logout_date_time']);
        fputcsv($f, $lineData, $delimiter);
    }
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;

?>
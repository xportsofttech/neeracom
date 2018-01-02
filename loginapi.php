<?php
//include external files
//1. Database Connection
//2. Email Library
//3. Config file
require_once 'dbconnect.php';
require 'assets/phpmailkit/PHPMailerAutoload.php';
include('config.php');

session_start();
//create an object of PHPMailer
$mail = new PHPMailer;
ob_start();

//Both the variables email and password are login post values
$email = $_POST["Email"];
$Password = $_POST["Password"];

//get current date or time 
$date = date('Y-m-d');
$time = date("Y-m-d H:i:s");


//Checking for the logging in user account email and password to be same
$sql = "select * from user where email='$email' and password='$Password' and visibility='1' and deleted='0' ";
$result = $conn->query($sql);
$result_trial = $conn->query($sql);
$trial_expire = $result_trial->fetch_assoc();


//If user does not exists, show error message
if ($result->num_rows == 0) {
    $responce = array(
        "Status" => false,
        "msg" => 'Si è verificato un problema.'
    );
}
//Checking expire Trial date 
else if ($date >= $trial_expire['trial_expire']) {
    $responce = array(
        "Status" => false,
        "msg" => 'Periodo di prova terminato.'
    );
} 
//User data is taken when account details are fetched i.e. user email, password
else {
    while ($row = $result->fetch_assoc()) {
        $user_id = $row['id'];
        $max_login = $row['connections_available'];
        $fname = $row['first_name'];
        $lname = $row['last_name'];
        $connections_available = $row['connections_available'];
        $user_email = $row['email'];
    }
    //for checking maximum connection availability
    $q = "SELECT * FROM login_report  WHERE logout_date_time IS NULL AND user_id='" . $user_id . "'";
    $r = mysqli_query($conn, $q);
    $rows = mysqli_num_rows($r);
    if ($rows < $max_login) {
        $_SESSION['id'] = $fname . " " . $lname;
        $d = getdate();
        $time = $d['hours'] . ":" . $d['minutes'] . ":" . $d['seconds'];
        $date = $d['year'] . "-" . $d['mon'] . "-" . $d['mday'];

        $dt = $date . " " . $time;
        $date = date_create($dt);
        $dtime = date_format($date, "Y-m-d H:i:s");
        
        //insert login user detail in login_report table
        $insert = "insert into login_report set login_date_time='" . $dtime . "', user_id='" . $user_id . "'";
        $conn->query($insert);
        $_SESSION["login_report_id"] = $conn->insert_id;
        $responce = array(
            "Status" => true,
            "msg" => 'Benvenuto.'
        );
    } 
    //If maximum connection number is crossed, it shows error message
    else {
        $responce = array(
            "Status" => "connections_available",
            "msg" => "Il suo profilo prevede " . $connections_available . " connessione/i contemporanea/e. Risulta essere connesso " . $connections_available . " volta/e. E' possibile che non abbia effettuato il logout nella precedente sessione."
        );

//Send mail to admin, if maximum user connection is crossed
        $Username = $GLOBALS['UserName'];
        $PassWord = $GLOBALS['PassWord'];
        $admin_email = $GLOBALS['admin_email'];
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $GLOBALS['Host'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = "$Username";                 // SMTP username
        $mail->Password = "$PassWord";                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom("$Username", 'Split-Payment Tool / ADMIN');
        $mail->addAddress($admin_email);     // Add a recipient
// Name is optional
        $mail->addReplyTo("$Username");

        $mail->isHTML(true);
        $mag = "<pre> <h4>Hello,</h4>
            <div style='font-size:13px;'>
            This is to notify you that user " . $user_email . "'s account is configured to allowed maximum " . $connections_available . "
            User's login attempt was blocked recently because of violation of maximum allowed parallel logins.
		 	Regards
		 	</div>
		 		</pre>";

        $mail->Subject = "Violazione numero connessioni";
        $mail->Body = "<pre>" . $mag . "<pre>";
        if (!$mail->send()) {
            echo 'E-Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        } else {
            //Insert values to activity table ,for Activities,request,response,date,ip        
            $insert = "insert into Verify(Activities,request,response,date_created,ip) values('Login','" . $user_id . "','" . $responce['msg'] . "','" . $time . "','" . $_SERVER['REMOTE_ADDR'] . "')";

            $conn->query($insert);
        }
    }
    $_SESSION['id'] = $fname . " " . $lname;
}
echo json_encode($responce);
?>

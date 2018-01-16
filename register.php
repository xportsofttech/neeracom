<?php

session_start();

require 'assets/phpmailkit/PHPMailerAutoload.php';
include('config.php');
include('dbconnect.php');

$sql = "SELECT * from user where email = '" . $_POST['Email'] . "'";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $ret = array(
        "Status" => false,
        "Message" => 'email already exits.'
    );
} else {
    $mail = new PHPMailer;
    ob_start();
    $q = "insert into user set first_name='" . $_POST['FName'] . "', last_name='" . $_POST['LName'] . "', email='" . $_POST['Email'] . "', password='" . $_POST['Password'] . "', company_name='" . $_POST['cName'] . "', mobile='" . $_POST['Number'] . "', address='" . $_POST['address'] . "', trial_expire='" . date('Y-m-d', strtotime("+" . $GLOBALS['user_trail_days'] . " days")) . "'";
    $res = mysqli_query($conn, $q) or die("not fire");
    if ($res) {
        $_SESSION["id"] = $_POST['FName'] . " " . $_POST['LName'];
		$sqlQry=mysqli_query($conn,"select max(id) as LatestId from user");
		$row=mysqli_fetch_assoc($sqlQry);
		$_SESSION['userid']=$row['LatestId'];
        $d = getdate();
        $time = $d['hours'] . ":" . $d['minutes'] . ":" . $d['seconds'];
        $date = $d['year'] . "-" . $d['mon'] . "-" . $d['mday'];

        $dt = $date . " " . $time;
        $date = date_create($dt);
        $dtime = date_format($date, "Y-m-d H:i:s");
        
        //insert login user detail in login_report table
        $insert = mysqli_query($conn,"insert into login_report set login_date_time='" . $dtime . "', user_id='" . $_SESSION['userid'] . "'");
		if($insert){
			$sqlQrylogin=mysqli_query($conn,"select max(id) as loginid from login_report");
			$rowlogin=mysqli_fetch_assoc($sqlQrylogin);
			$_SESSION["login_report_id"] = $rowlogin['loginid'];
		}
        $fullname = $_POST['FName'] . " " . $_POST['LName'];
        $Username = $GLOBALS['UserName'];
        $Password = $GLOBALS['PassWord'];
        $admin_email = $GLOBALS['admin_email'];
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $GLOBALS['Host'];  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = "$Username";                 // SMTP username
        $mail->Password = "$Password";                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $GLOBALS['Port'];                                   // TCP port to connect to
        $mail->setFrom("$Username", 'Split-Payment Tool / ADMIN');

        // $mail->addAddress($_POST['Email']);     // Add a recipient - switched to admin
		$mail->addAddress($admin_email);


        $mail->addReplyTo("$Username");
        $mail->isHTML(true);                                  // Set email format to HTML
        $website = $GLOBALS['website'];
        $mag = " Welcome <span style='font-size:18px;text-transform:capitalize;'>" . $fullname . "</span>
				<div style='font-size:13px;'>Thank you for registering , your account has been activated.
				Thanks and enjoy :)
				All the best,
				website = " . $website . "</div>";
				
        $mail->Subject = "Benvenuto";
        $mail->Body = "<pre>" . $mag . "<pre>";
        if (!$mail->send()) {
            $ret = array('Status' => true, 'Message' => 'E-Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
        } else {
            $mail = new PHPMailer;
            ob_start();
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $GLOBALS['Host'];  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = "$Username";                 // SMTP username
            $mail->Password = "$Password";                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = $GLOBALS['Port'];                                    // TCP port to connect to

            $mail->setFrom("$Username", 'Split-Payment Tool / ADMIN');
            $mail->addAddress($admin_email);
            $mail->isHTML(true);
            $mag = "Hello <span style='font-size:18px;text-transform:capitalize;'>Admin,</span>
            		<div style='font-size:13px;'>
					A new user has been registered whose details are as under:
					Email: " . $_POST['Email'] . ",
        			Full Name: " . $fullname . ",
        			Trial Expire Date: " . date('Y-m-d', strtotime("+" . $GLOBALS['user_trail_days'] . " days")) . ",
        			Connections Available: 1,
        			visibility: Active,
      				Regards
      				</div>";
      				
            $mail->Subject = "Nuovo Utente";
            $mail->Body = "<pre>" . $mag . "<pre>";
            if (!$mail->send()) {
                $ret = array('Status' => true, 'Message' => 'E-Mail could not be sent. Mailer Error: ' . $mail->ErrorInfo);
            } else {
                $ret = array('Status' => true, 'Message' => 'Successfully registered.');
            }
        }
    } else {
        $ret = array('Status' => false, 'Message' => 'Something went wrong. Try again.');
    }
}
echo json_encode($ret);
?>

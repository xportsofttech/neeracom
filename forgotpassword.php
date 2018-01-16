<?php
session_start();
$sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';
if(!empty($sessData['status']['msg'])){
    $statusMsg = $sessData['status']['msg'];
    $statusMsgType = $sessData['status']['type'];
    unset($_SESSION['sessData']['status']);
}

require_once 'dbconnect.php';
include('config.php');

//forgot password
if(isset($_POST['btnfpswd'])){
	$email=$_POST['txtEmail'];
	$sql=mysqli_query($conn,"select * from user where email='$email'");
	$num_row=mysqli_num_rows($sql);
	if($num_row>0){
		while($row=mysqli_fetch_assoc($sql)){
			$id=$row['Id'];
			$fname=$row['first_name'];
			$lname=$row['last_name'];
			if($fname!="" && $lname!=""){
				$full_name=$fname." ".$lname;
			}else if($fname!="" && $lname=""){
				$full_name=$fname;
			}else if($fname="" && $lname!=""){
				$full_name=$lname;
			}
			$mail=$row['email'];
		}
		$hmail=base64_encode($mail);
		if($sql){
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$body="Hello ".$full_name.",<br/>Reset your password, and we'll get you on your way.<br/>To change your password, click <strong><a href='http://sportica.in/split/reset_password.php?id=$hmail'>Here</a></strong>";
			mail($mail,"here's the link to reset your password",$body,$headers);
			$verify=true;
			$cookie_name = "email";
			$cookie_value = $email;
			header("Location:login.php?reset");
		}
		else{
			$errMSG="Oops!Error Occured!!" .mysqli_error($conn);
		}
	}
	else{
		$errMSG="Oops!No match found!!" .mysqli_error($conn);
	}
}


?>
<html>
<head>
    <title>Login</title>
	<?php include 'header.php'; ?>
    <style>
		.input-group, .join_form .input-group{margin-top:15px;}
		.join_form .col-md-10{margin:10px 0px 40px 0px;}
		.forget_form .col-md-6 .col-md-12{margin:20px 0px 70px 0px;}
	</style>
	<!-- hide message after 5 second -->
	<script>
		setTimeout(function() {
			document.getElementById("msg").style.display = 'none';
		},5000);
	</script>
</head>
<body>
<div class="col-md-3"></div>
 <div class="col-md-6">
 <center><h4>Let’s find your account</h4></center><br/><br/>
        <div class="panel panel-info">
			<span id="msg" style="color:red;">
				<center><strong><?php if(isset($errMSG)){ echo $errMSG; } ?></strong></center>
			</span>
			<span id="msg" style="color:green;">
                <?php if(isset($successMSG)){ echo $successMSG; } ?>
			</span>
            <div class="panel-heading text-center"><strong><h4> Please enter your email</h4></strong></div>
            <div class="panel-body">
                <form method="post" id="loginForm">
                    <div class="input-group">
                        <input type="Email" class="form-control" name="txtEmail" id="txtEmail" placeholder="Email" required />
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    </div>
                    <button type="submit" id="btnfpswd" name="btnfpswd" class="btn btn-success btn-block" style="margin-top:10px;"><i class="glyphicon glyphicon-log-in"></i>&emsp;Submit</button>
                </form>
            </div>
        </div>
    </div>
	<div class="col-md-3"></div>
</body>
</html>
<?php
session_start();
if (isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}

require_once 'dbconnect.php';
include('config.php');

if(isset($_POST['btnfpswd'])){
	$mail=$_GET['id'];
	$hmail=base64_decode($mail);
	$pswd=$_POST['txtPswd'];
	$re_pswd=$_POST['txtConfPswd'];
	//$hpswd=hash('sha256',$pswd);
	$sql="UPDATE user SET password='".$pswd."' WHERE email='".$hmail."'";
	$qry=mysqli_query($conn,$sql);
	if($qry){
		$successMSG="Password Changed.";
	}
	else{
		$message="We’re sorry, there was a problem with your request.";
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
<br/>
<br/>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-info" id="chngepswd" <?php if(isset($qry)){?>style="display:none;" <?php }?>>
            <div class="panel-heading text-center">
				<span style="color:green;">
					<?php if(isset($_GET['reset'])){ echo "An email has been sent. Please Check your Mail Box"; } ?>
				</span>			
				<strong><h4> Choose a new password that’s hard to guess</h4></strong>
			</div>
            <div class="panel-body">
                <form method="post" id="form-register" name="form-register">
                    <div class="input-group">
                        <input type="password" class="form-control" name="txtPswd" id="txtPswd" placeholder="Enter Password" />
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    </div>
                    <div class="input-group">
                        <input type="password" class="form-control" name="txtConfPswd" id="txtConfPswd" placeholder="Confirm Password" />
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    </div>
                    <div class="pull-right" style="margin:10px 3px 10px;">
                        <a href="index.php" >Annulla</a>
                    </div>
                    <button type="submit" id="btnfpswd" name="btnfpswd" class="btn btn-success btn-block" style="margin-top:10px;"><i class="glyphicon glyphicon-log-in"></i>&emsp;Save</button>
                </form>
            </div>
        </div>
		<div id="changed" style="display:<?php if(isset($qry)){ echo ""; }else { echo "none"; }?>" class="panel panel-info">
			<span>
				<?php 
				if(isset($successMSG)){
					echo "<center><h3 style='color:green;'>".$successMSG."</h3></center>"; 
				?>	
				<form method="post" action="login.php">
					<input type="hidden" name="mail" id="mail" value="<?php if(isset($_GET['id'])){ echo $hmail=base64_decode($_GET['id']); } ?>">
					<center><input type="submit" name="btnSignIn" id="btnSignIn" value="Sign in" class="btn btnn-primary"></center>
				</form>
				<?php
					} 
				?>
				
			</span>
			<span id="msg">
				<?php if(isset($message)){ echo "<center><h3>".$message."</h3></center>"; } ?>
			</span>
		</div>
    </div>
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery.validate.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#form-register").validate({
				rules:{
					txtPswd:{
						required:true,
						minlength:8,
						
					},
					txtConfPswd:{
						required:true,
						equalTo:"#txtPswd",
						
					},
				
				},
				messages:{
					txtPswd:
					{
						required:"Enter password",
						minlength:"<font color='red'>Password must be 8 character long</font>",
					
					},
					txtConfPswd:
					{
						required:"Re-enter password",
						equalTo:"Password doesn't match!!",
					},
				}
			});
		});
	</script>
</body>
</html>


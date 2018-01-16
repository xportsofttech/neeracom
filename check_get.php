<?php
session_start();
$id=$_SESSION['userid'];
$_SESSION['fiscal']=$_POST['txtFiscal'];
$_SESSION['auth']=$_POST['txtAuthorized'];
if(isset($_POST['search_submit'])){
	$fiscal_code=$_POST['txtFiscal'];
	$auth=$_POST['txtAuthorized'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "http://sportica.in/split/search_api.php?authorize=$auth&code=$fiscal_code&userid=$id");
	$result = curl_exec($ch);
	if($result=='1'){
		$authERR="Autorizzazione non valida";
	}else if($result=='2'){
		$loginERR="Per favore fai prima il log in!";
	}else{
		$output="<div class='alert alert-info'><center><strong>Result=></strong><br/>".$result."</center></div>";
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
		.red{color:red;}
    </style>
</head>
<body>
    <br>
	<span id="loginERR" class="red"><center><strong><?if(isset($loginERR)){ echo $loginERR; }?></strong></center></span>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div class="panel panel-info">
				<div class="panel-heading text-center">		
					<strong><h4>   Ricerca Codice Fiscale   </h4></strong>
				</div>
				<div class="panel-body">
					<form method="post" id="searchForm" onsubmit="return validate();">
						<div id="msg">
						</div>
						<div class="form-group">
							<label><strong>Enter Codice Fiscale :</strong></label>
							<input type="text" class="form-control" name="txtFiscal" id="txtFiscal" value="<?php echo $_SESSION['fiscal']; ?>" />
						</div>
						<div class="form-group">
							<label><strong>Autorizzazione :</strong></label>
							<input type="text" class="form-control" name="txtAuthorized" id="txtAuthorized" value="<?php echo $_SESSION['auth']; ?>" />
							<span id="auth_err" class="red"><?php if(isset($authERR)){ echo $authERR; }?></span>
						</div>
						<div class="pull-right" style="margin:10px 3px 10px;">
							<a href="index.php" >Annulla</a>
						</div>
						<button type="submit" id="search_submit" name="search_submit" class="btn btn-success btn-block" style="margin-top:10px;">&emsp;Sottoscrivi</button>
						<input type="hidden" id="session_id" name="session_id" value="<?php echo $_SESSION['userid']; ?>" >
					</form> 
				</div>
			</div>
		</div>
		<div class="col-md-3"></div>
	</div>
	<div id="output"> <?php if(isset($output)){ echo $output; } ?> </div>
	<script>
	function validate(){
		var fiscal=$("#txtFiscal").val();
		var authorization=$("#txtAuthorized").val();
		
		if (fiscal == '') {
            $("#txtFiscal").css('border', '1px solid red');
            $("#txtFiscal").focus();
			return false;
        } else if (authorization == ''){
            $("#txtAuthorized").css('border', '1px solid red');
            $("#txtAuthorized").focus();
			return false;
        }else{
			return true;
		}
	}
	</script>
</body>
</html>


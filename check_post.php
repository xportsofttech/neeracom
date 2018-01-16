<?php
session_start();
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
	<span id="loginERR" class="red"></span>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div class="panel panel-info">
				<div class="panel-heading text-center">		
					<strong><h4>   Ricerca Codice Fiscale   </h4></strong>
				</div>
				<div class="panel-body">
					<form method="post" id="searchForm">
						<div id="msg">
						</div>
						<div class="form-group">
							<label><strong>Enter Codice Fiscale :</strong></label>
							<input type="text" class="form-control" name="txtFiscal" id="txtFiscal" />
						</div>
						<div class="form-group">
							<label><strong>Autorizzazione :</strong></label>
							<input type="text" class="form-control" name="txtAuthorized" id="txtAuthorized" />
							<span id="auth_err" class="red"></span>
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
	<div id="output">
	</div>
	<script>
    $("#searchForm").submit(function (e) {
        e.preventDefault();
		var fiscal=$("#txtFiscal").val();
		var authorization=$("#txtAuthorized").val();
		var usr=$("#session_id").val();
		
        if (fiscal == '') {
            $("#txtFiscal").css('border', '1px solid red');
            $("#txtFiscal").focus();
        } else if (authorization == ''){
            $("#txtAuthorized").css('border', '1px solid red');
            $("#txtAuthorized").focus();
        } else {
            $("#search_submit").prop("disabled", true);
            $("#search_submit").html("<i class='fa fa-refresh fa-spin'></i> &emsp; Please wait...");
            $.ajax({
				
                type: "POST",
                url: 'search_api.php',
                data: 'code='+fiscal+'&authorize='+authorization+'&userid='+usr,
                success: function (response) {
					if(response=='1'){
						$("#txtAuthorized").css('border', '1px solid red');
						$("#txtAuthorized").focus();
						$("#auth_err").html("Autorizzazione non valida");
						$("#search_submit").prop("disabled", false);
                        $("#search_submit").html("&emsp;Sottoscrivi");
					}else if(response=="2"){
						$("#loginERR").html("<center><strong>Per favore fai prima il log in!</strong></center>");
						$("#search_submit").prop("disabled", false);
                        $("#search_submit").html("&emsp;Sottoscrivi");
					}
					else{
						$("#output").html("<div class='alert alert-info'><center><strong>Result=></strong><br/>"+response+"</center></div>");
						$("#search_submit").prop("disabled", false);
                        $("#search_submit").html("&emsp;Sottoscrivi");
					}                
                }				
            });
        }
        ;
    });
	</script>
</body>
</html>


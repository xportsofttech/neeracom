<?php
session_start();
if (isset($_SESSION["admin_id"])) {
    header("Location: Verify.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Amministrazione</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta name="MobileOptimized" content="320">
        <link href=" https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../assets/alertifyjs/css/alertify.min.css" />
        <link rel="stylesheet" href="../assets/alertifyjs/css/themes/default.min.css" />
        <script src="../assets/js/jquery-1.10.2.min.js" type="text/javascript"></script>
        <script src="../assets/alertifyjs/alertify.min.js"></script>
        <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
    </head>

    <style>
        .join_form .input-group{margin-top:15px;}
        .join_form .col-md-10{margin:10px 0px 40px 0px;}
    </style>
    <body>
<!--
        <div class="col-md-12" style="padding: 0px;margin-top:20px;">
            <h3 class="text-center">Information Admin Login </h3><hr style="width:40%;margin:auto;margin-bottom:30px;">
        </div>
        -->
        <br>
        <div class="container-fluid join_form">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading text-center"><strong>Login Amministrazione</strong></div>
                        <div class="panel-body">
                            <form method="post" id="login_Form">
                                <div class="input-group">
                                    <input type="Email" class="form-control" name="Email" id="email" placeholder="Email"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                </div>
                                <div class="input-group">
                                    <input type="Password" class="form-control" name="Password" id="password" placeholder="Password"/>
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                </div>
                                <button type="submit" id="login_submit" class="btn btn-success btn-block" style="margin-top:10px;"><i class="glyphicon glyphicon-log-in"></i>&emsp;Login</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <script>
            $("#login_Form").submit(function (e) {
                e.preventDefault();
                if ($("#email").val() == '' || $("#password").val() == '') {
                    if ($("#email").val() == '') {
                        $("#email").css('border', '1px solid red');
                        $("#email").focus();
                    }
                    if ($("#password").val() == '') {
                        $("#password").css('border', '1px solid red');
                    }
                } else {

                    $("#login_submit").prop("disabled", true);
                    $("#login_submit").html("<i class='fa fa-refresh fa-spin'></i> &emsp; Please wait...");
                    $.ajax({
                        type: "POST",
                        url: 'loginapi.php',
                        data: $("#login_Form").serialize(),
                        dataType: 'json',
                        success: function (data) {
                            if (data.Status == true) {
                                window.location = 'admin_user.php';
                            } else if (data.Status == false) {
                                alertify.error(data.msg);
                                $("#login_submit").prop("disabled", false);
                                $("#login_submit").html("<i class='glyphicon glyphicon-log-in'></i> &emsp; Login");
                            }
                        }
                    });
                }
            });
        </script>
    </body>
</html>

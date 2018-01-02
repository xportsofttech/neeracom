<?php
session_start();
if (isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}
?>
<html>
    <head>
        <title>Login</title>
        <?php
        include 'header.php';
        ?>
        <style>

            .input-group, .join_form .input-group{margin-top:15px;}
            .join_form .col-md-10{margin:10px 0px 40px 0px;}
            .forget_form .col-md-6 .col-md-12{margin:20px 0px 70px 0px;}
        </style>
<!--
    <div class="col-md-12" style="padding: 0px;margin-top:20px;">
        <h3 class="text-center">Login</h3><hr style="width:40%;margin:auto;margin-bottom:30px;">
    </div>
    -->
    <br>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading text-center"><strong><h4>   Login   </h4></strong></div>
            <div class="panel-body">
                <form method="post" id="loginForm">
                    <div class="input-group">
                        <input type="Email" class="form-control" name="Email" id="email" placeholder="Email"/>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                    </div>
                    <div class="input-group">
                        <input type="Password" class="form-control" name="Password" id="password" placeholder="Password"/>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    </div>
                    <div class="pull-right" style="margin:10px 3px 10px;">
                        <a href="index.php" >Annulla</a>
                    </div>
                    <button type="submit" id="login_submit" class="btn btn-success btn-block" style="margin-top:10px;"><i class="glyphicon glyphicon-log-in"></i>&emsp;Login</button>
                </form>
            </div>
        </div>
    </div>
    <script>  $("#loginForm").submit(function (e) {
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
                    data: $("#loginForm").serialize(),
                    dataType: 'json',
                    success: function (data) {
                        if (data.Status == true) {
                            window.location = 'index.php';
                                                      
                            } else if (data.Status == "connections_available") {
                            alertify.alert(data.msg, function () {
                                window.location = 'index.php';
                            });
                                   
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
</head>
</html>


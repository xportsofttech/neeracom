<?php
session_start();
if (isset($_SESSION["id"])) {
    header("Location: index.php");
    exit;
}
?>
<html>
    <head>
        <title>Registrazione</title>
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
        <h3 class="text-center">Join with us</h3><hr style="width:40%;margin:auto;margin-bottom:30px;">
    </div>
    -->
    <br>
    <div class="container-fluid join_form">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="panel panel-info">
                    <div class="panel-heading text-center"><strong></h4>Registrazione</h4></strong></div>
                    <div class="panel-body">
                        <form method="post" id="signup" action="">
                            <div class="input-group">
                                <input type="text" class="form-control" name="FName" id="FName" placeholder="Nome"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" name="LName" id="LName" placeholder="Cognome"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            </div>

                            <div class="input-group">
                                <input type="text" class="form-control" name="cName" id="cName" placeholder="Numero Telefono (Opzionale)"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            </div>
                            
                            <!--
                            <div class="input-group">
                                <input type="text" class="form-control" name="Number" id="Number" placeholder="Numero Cellulare"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            </div>
                             -->
                            <div class="input-group">
                                <input type="Email" class="form-control" name="Email" id="Email" placeholder="Email"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                            </div>
                            <div class="input-group">
                                <input type="Password" class="form-control" name="Password" id="Password" placeholder="Password"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                            </div>
                            <div class="input-group">
                                <input type="Password" class="form-control" name="Password" id="confirm_Password" placeholder="Conferma Password"/>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                            </div>
                            
                             <!--
                            <div class="input-group">
                                <textarea class="form-control" style="resize: none; height:100%; width: 100%; " placeholder="Indirizzo (Opzionale)" id="address" name="address"></textarea>
                                <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                            </div>   --> 
                            <div class="pull-right" style="margin:10px 3px 10px;">
                                <a href="index.php"> Annulla </a></div>
                            <button type="submit" id="registerSubmit" class="btn btn-success btn-block"  style="margin-top:10px;"><i class="glyphicon glyphicon-floppy-disk"></i>&emsp;Registrazione</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md-1"></div>
</div>

<script>
    $("#signup").submit(function (e) {
        e.preventDefault();
        if ($("#FName").val() == '') {
            $("#FName").css('border', '1px solid red');
            $("#FName").focus();
        } else if ($("#LName").val() == '')
        {
            $("#LName").css('border', '1px solid red');
            $("#LName").focus();
        } else if ($("#Email").val() == '')
        {
            $("#Email").css('border', '1px solid red');
            $("#Email").focus();
        } else if ($("#Password").val() == '')
        {
            $("#Password").css('border', '1px solid red');
            $("#Password").focus();
        } else if ($("#confirm_Password").val() == '')
        {
            $("#confirm_Password").css('border', '1px solid red');
            $("#confirm_Password").focus();
        } else if ($("#confirm_Password").val() != $("#Password").val())
        {
            alertify.error("Password do not match");
            $("#Password").focus();
        } else
        {
            $("#registerSubmit").prop("disabled", true);
            $("#registerSubmit").html("<i class='fa fa-refresh fa-spin'></i> &emsp; Please wait...");
            $.ajax({
                type: "POST",
                url: 'register.php',
                data: $("#signup").serialize(),
                dataType: 'json',
                success: function (data) {
                    if (data.Status == true) {
                        window.location = 'index.php';
                    } else if (data.Status == false) {
                        alertify.error(data.Message);
                        $("#registerSubmit").prop("disabled", false);
                        $("#registerSubmit").html("<i class='glyphicon glyphicon-floppy-disk'></i>&emsp;Register");
                    }
                }
            });
        }
        ;
    });
</script>

</body>
</html>
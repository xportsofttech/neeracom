<?php
@session_start();
if ($_SESSION["admin_id"] > 0) {
    $id = $access[0];
} else {
    header("Location: index.php");
    exit;
}
?><!DOCTYPE html>
<html>
    <head>
        <title>Amministrazione</title>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta name="MobileOptimized" content="320">
        <link href=" https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link id="bsdp-css" href="../assets/css/bootstrap-datepicker3.min.css" rel="stylesheet">
         <link rel="stylesheet" href="../assets/alertifyjs/css/alertify.min.css" />
        <link rel="stylesheet" href="../assets/alertifyjs/css/themes/default.min.css" />
        <link href="../assets/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
        <script src="../assets/js/jquery-1.10.2.min.js" type="text/javascript"></script>
         <script src="../assets/alertifyjs/alertify.min.js"></script>
        <script src="../assets/js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../assets/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../assets/js/bootstrap-datepicker.min.js"></script>
        <script src="../assets/js/jspdf.debug.js" type="text/javascript"></script>
        
        
        <script src="../assets/js/sendmail.js"></script> 
        
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <style>
            .input-group{margin-top:10px;}
        </style>
        
    	
    <body>
           <div class="container">
        <nav class="navbar navbar-inverse" style="padding:0px;margin:0px;border-radius: 0px;">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>                        
                    </button>
                    <a class="navbar-brand" href="admin_user.php">Amministrazione</a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <ul class="nav navbar-nav">
                        <li ><a href="admin_user.php">Elenco Utenti</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li ><a href="Verify.php">Log Attività</a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li ><a href="add_data.php">Aggiorna Database</a></li>
                    </ul>
                    
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                        if (!$_SESSION["firstname"]) {
                            ?>
                            <li><a href=""><span class="glyphicon glyphicon-user"></span>Login</a></li>
                            <?php
                        } else {
                            ?>
                            <li><a href="" class="dropdown-toggle" data-toggle="dropdown"></span><?= $_SESSION["firstname"] ?></a>
                                <ul class="dropdown-menu">
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
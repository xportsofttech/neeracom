<?php
session_start();
?><!DOCTYPE html>
<html>
    <head>
        <title>Split-Payment Check Tool</title>
        <?php
        include 'header.php';
        ?>
        <style>
            body {font-family: "Verdana", sans-serif; font-size: 12px}
			
            /* Style the tab */
            div.tab {
                overflow: hidden;
                background-color: #ffffff;
            }

            /* Style the buttons inside the tab */
            div.tab button {
                background-color: inherit;
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 6px 6px 6px 6px;
                transition: 0.3s;
                font-size: 12px;     
                color: white;
            }

            /* Change background color of buttons on hover */
            .nav>li>a:focus, .nav>li>a:hover {
                text-decoration: none;
                background-color: transparent !important;
            }
            /* Create an active/current tablink class */
            div.tab button.active {
                background-color: #00A3E0;
            }
            div.tab button {
                background-color: #ccc;
            }

            /* Style the tab content */
            .tabcontent {
                display: none;
                padding: 4px 10px;
                border-top: none;
            }
            .a
            {
                color: white;
            }
        </style>
        <script type="text/javascript">
            var verifyCallback = function (response) {
                alert(response);
            };
            var widgetId1;
            var widgetId2;
            var onloadCallback = function () {
                // Renders the HTML element with id 'example1' as a reCAPTCHA widget.
                // The id of the reCAPTCHA widget is assigned to 'widgetId1'.
                widgetId1 = grecaptcha.render('example1', {
                    'sitekey': '6LfIKzkUAAAAAKE84qU7MPDowEBO81OMe6rZkm7d',
                    'theme': 'light'
                });
                widgetId2 = grecaptcha.render('example2', {
                    'sitekey': '6LfIKzkUAAAAAKE84qU7MPDowEBO81OMe6rZkm7d'
                });
                widgetId3 = grecaptcha.render('example3', {
                    'sitekey': '6LfIKzkUAAAAAKE84qU7MPDowEBO81OMe6rZkm7d',
                    'theme': 'dark'
                });
            };
        </script>
        <style>
            .form-control, .btn, .input-group-addon{border-radius:3px;}
            #errmsg
            {
                color: red;
            }
        </style>
              
        <script> 										// carica risultati per ricerca testo
            $(document).ready(function () {
                $("#number").keypress(function (e) {
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 59)) {
                        alertify.error("Carattere non permesso.");
                        return false;
                    }
                });
                $("#submit").click(function () {
                    var number = $("#number").val();
                    var recaptcha_demo = grecaptcha.getResponse(widgetId1);
                    var dataTable = $('#dataTables-example').DataTable({
                        "destroy": true,
                        "pagingType": "full_numbers",
                        "pageLength": 5,
                        "lengthChange": false,
                        "lengthMenu": [1, 3, 5],
                        "pagingType": "full_numbers",
                        "order": [[1, "desc"]],
                        // AJAX Code To Submit Form.
                        "ajax": {
                            type: "POST",
                            url: "get_data.php",
                            dataType: 'json',
                            dataSrc: function (json) {
                                if (json.status == false)
                                {
                                    alertify.error(json.msg);
                                    $('#Results').hide();
                                } else
                                {
                                    return json.data;
                                }
                            },
                            data: {
                                'number': number,
                                'recaptcha_demo': recaptcha_demo,
                            },
                        }
                    })
                    $('#Results').show();
                })
            });
        </script>
        
        <script>										// carica risultati per ricerca file
            $(document).ready(function (e) {
            $('input[type=file]').click(function(){    
                    $("#file").val("");
                })
                $("#xlsfile").on('change', (function (e) {
                    e.preventDefault();
                    $("#message").empty();
                    $('#loading').show();
                    $.ajax({
                        url: "upload.php", // Url to which the request is send
                        type: "POST", // Type of request to be send, called as method
                        data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                        contentType: false, // The content type used when sending data to the server.
                        cache: false, // To unable request pages to be cached
                        processData: false, // To send DOMDocument or non processed data file it is set to false
                        success: function (data)   // A function to be called if request succeeds
                        {
                            if (data.Status == false)
                            {
                                alert(data.Status);
//                                alertify.error(data.msg);
                                $('#Results').hide();
                            }
                        }
                    });
                }));

                $("#xls_submit").click(function (e) {
                    e.preventDefault();
                    var recaptcha_demo = grecaptcha.getResponse(widgetId2);
                    var file = $("#file").val();
                    if (file == '')
                    {
                        alertify.error("Caricare file xlsx.");
                    } else {
                        var dataTable = $('#dataTables-example').DataTable({
//                        "processing": true,
                            "destroy": true,
                            "pagingType": "full_numbers",
                            "pageLength": 5,
                             "lengthChange": false,
                            "lengthMenu": [1, 3, 5],
                            "pagingType": "full_numbers",
                            "order": [[1, "desc"]],
                            // AJAX Code To Submit Form.
                            "ajax": {
                                type: "POST",
                                url: "get_data_xls.php",
                                dataType: 'json',
                                data: {
                                    'recaptcha_demo': recaptcha_demo,
                                },
                                dataSrc: function (json) {
                                    if (json.status == false)
                                    {
                                        alertify.error(json.msg);
                                        $('#Results').hide();
                                    } else
                                    {
                                        return json.data;
                                    }
                                }
                            }
                        })
                        $('#Results').show();
                    }
                });
            });
            function openCity(evt, cityName) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(cityName).style.display = "block";
                evt.currentTarget.className += " active";
            }
        </script>
        
        
        <script>											// carica risultati per ricerca libera
            $(document).ready(function () {
                
                $("#search_submit").click(function () {
                    var number = '%';
                    var recaptcha_demo = grecaptcha.getResponse(widgetId1);
                    var dataTable = $('#dataTables-example').DataTable({
                        "destroy": true,
                        "pagingType": "full_numbers",
                        "pageLength": 5,
                        "lengthChange": false,
                        "lengthMenu": [1, 3, 5],
                        "pagingType": "full_numbers",
                        "order": [[1, "desc"]],
                        // AJAX Code To Submit Form.
                        "ajax": {
                            type: "POST",
                            url: "get_data.php",
                            dataType: 'json',
                            dataSrc: function (json) {
                                if (json.status == false)
                                {
                                    alertify.error(json.msg);
                                    $('#Results').hide();
                                } else
                                {
                                    return json.data;
                                }
                            },
                            data: {
                                'number': number,
                                'recaptcha_demo': recaptcha_demo,
                            },
                        }
                    })
                    $('#Results').show();
                })
            });
        </script>
        
        
        
        
        
                
</head>
<body>
    
        <div class="container" style="padding: 10px;">
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->                   
                </div>
            </div>

		<!--   *********  NAVBAR ********    -->

                <nav class="navbar-xs navbar" style="padding:0px; margin:0px !important; background-color: #00A3E0;">
                	<div class="container-fluid">
                	
              			<!-- <div class="collapse navbar-collapse" id="myNavbar" > -->
                      	  <ul class="nav navbar-nav navbar-right">                
                            <?php
                            if (!isset($_SESSION["id"])) {
                                ?>
                                  <!--  <li><a href="join.php" class="a">Registrazione</a></li> -->
                                  <!--  <li><a href="login.php" class="a">Login</a></li>		-->	
                                <?php
                            } else {
                                ?>
                                <li><a href="" class="dropdown-toggle text-capitalize a" data-toggle="dropdown"><?= $_SESSION['id'] ?></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="logout.php" class="a">Logout</a></li>
                                    </ul>
                                </li>
                                <?php  
                            }
                            ?>
                        </ul>
               		<!-- </div> --> 
               		
                	</div>  
                </nav>
        
   		<!--   *********  TABS ********    -->
                  <div class="tab">                
                    <button class="tablinks active" style="border-right: 1px solid; border-top: 1px solid;" onclick="openCity(event, 'Chart')">Ricerca per Codice Fiscale</button>
                    <?php
                    if (isset($_SESSION["id"])) {
                        ?>
                        <button class="tablinks" style="border-right: 1px solid; border-top: 1px solid;" onclick="openCity(event, 'Table')">Riscontro su Elenco Excel</button>
                        
                        <button class="tablinks" style="border-right: 1px solid; border-top: 1px solid;" onclick="openCity(event, 'Search')">Ricerca Libera nel Database</button>
            
                        <?php
                    }
                    ?>
                </div>
                         
            	<div id="Chart" class="tabcontent" style="display: block; padding: 0px ;margin-top: 10px;"> 
                	<div class="col-md-3"></div>
                		<div class="col-md-5" style="padding:0px;margin-top: 7px">
                    		<form method="post">
                        	<div class="input-group input-group-sm">
                            <textarea class="form-control" style="resize: none;" id="number"></textarea>
                            <span class="input-group-addon"><i class="fa fa-pencil"></i></span>
                            <span id="errmsg"></span>
                        	</div>  
                        	<div id="example1"  <?= (isset($_SESSION['id'])) ? 'style="display: none"' : ""; ?>></div>   
							<div style="text-align:center;">
                        	<button type="button" class="btn btn-sm btn-success" style="margin-top:10px; background-color: #86BC25;" id="submit">Verifica</button>
                    		</div>
                    		</form>
                		</div>
                	
            	</div>
            <?php
            if (isset($_SESSION["id"])) {
                ?>
                <div id="Table"   class="tabcontent" style="padding: 0px; margin-top: 10px; ">               
                    <div class="col-md-3"></div>
                    <div class="col-md-5"  style="padding:0px;margin-top: 7px;">
                        <form method="post"nctype="multipart/form-data" id="xlsfile" >
                            <div class="input-group">
                                <input type="file" class="form-control"  name="file" id="file" accept=".xlsx, .xls, .ods, .xml"/>
                                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                            </div>

                            <div id="example2"  <?= (isset($_SESSION['id'])) ? 'style="display: none"' : ""; ?>></div> 
							<div style="text-align:center;">
                            <button type="button" class="btn btn-sm btn-success" style="margin-top:6px;background-color: #86BC25;" id="xls_submit">Verifica</button>
                            </div>
                        </form>
                    </div>
                </div>
                 
                <div id="Search"   class="tabcontent" style="padding: 0px; margin-top: 10px; ">
                	<div class="col-md-12" style="padding:0px;">
						<div style="text-align:center;">
                 		<button type="button" class="btn btn-sm btn-success" style="margin-top:10px; background-color: #86BC25;" id="search_submit">Carica Database Completo</button>
                      </div>
                	</div>
                	
                
                </div>
                
                
                <?php
            }
            ?>
                        
            <!--   *********  RESULTS ********    -->
                        
            <div style="display:none" id="Results">
                <div class="col-md-12" style="padding: 0px;">
                    <br>                     
                    <div id="page-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">




                                    <!-- /.panel-heading -->
                                    <div class="panel-body table-responsive">
                                        <table width="100%" class="table table-striped table-bordered table-hover table-condensed" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>C.F.</th>
                                                    <th>Denominazione</th>
                                                    <th>Elenco</th>
                                                    <th>Verifica</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                @session_start();
                if ($_SESSION["id"]) {
                    ?>
                    <div class="col-md-12" style="padding: 0px;"> 

                        <div style="text-align:right;"> <a href="download.php" target="_blank" class="btn btn-sm btn-success" id="download" 
                        			style="margin-top:3px;background-color: #86BC25;"><i class="fa fa-download"></i> &emsp; Scarica xlsx</a>                             
                        </div>
                     <!--   <div class="col-md-6"> <a href="download_csv.php" target="_blank" class="btn btn-block btn-success" id="download" 
                     				style="margin-top:10px;background-color: #86BC25;"><i class="fa fa-download"></i> &emsp; Scarica csv</a>                             
                       
                        </div> -->
                        
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

</body>
</html>

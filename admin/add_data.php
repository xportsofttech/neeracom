<?php
include 'header.php';
?>
<script>
    $(document).ready(function (e) {
        $("#xlsfile").on('change', (function (e) {
            e.preventDefault();
            $("#message").empty();
            $('#loading').show();
            $.ajax({
                url: "../upload.php", // Url to which the request is send
                type: "POST", // Type of request to be send, called as method
                data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                contentType: false, // The content type used when sending data to the server.
                cache: false, // To unable request pages to be cached
                processData: false, // To send DOMDocument or non processed data file it is set to false
                success: function (data)   // A function to be called if request succeeds
                {
                    console.log(data);
                    $('#loading').hide();
                    $("#message").html(data);
                }
            });
        }));
        var dataTable = $('#dataTables').DataTable({
            "pagingType": "full_numbers",
            "aoColumns": [
                {"mDataProp": "id"},
                {"mDataProp": "text"},
                {"mDataProp": "text1"},
                {"mDataProp": "text2"},
            ]
        });
        $("#xls_submit").click(function (e)
        {
            var file= $("#file").val();
            if (file == '')
            {
                alertify.error("Please upload xlsx file.");
            }    
            else
            {
                url = 'save_data.php';
                source = new EventSource(url);

                //a message is received
                source.addEventListener('message', function (e)
                {
                    var result = JSON.parse(e.data);

                    add_log(result.message);
                    $('#progress_data').html(result.progress.toFixed(0) + '%');
                    document.getElementById('progressor').style.width = result.progress + "%";

                    if (e.data.search('Complete  100%') != -1)
                    {
                        source.close();
                    }
                });

                source.addEventListener('error', function (e)
                {
                    add_log('Error occured');
                    //kill the object ?
                    source.close();
                });
            }
        });

        function add_log(message)
        {
            $('#myModal').modal('show');
            if (message.text)
            {
                console.log(message);
                dataTable.rows.add([message]).draw(false);
                //  dataTable.rows().add([message]).draw(false);
                dataTable.order([0, 'asc']).draw();
                dataTable.page('last').draw(false);
            }
        }

    });
</script>
</head>
<body>
    <div style="background: #f3f3f3; text-align: center;" >
        <h3 style="margin: 0px!important; padding: 10px 0px; color: black">Aggiorna Database</h3>  
    </div>
    <div class="portlet-body form" style="margin-top:15px;">
        <div class="col-md-12" style="padding:0px;">

            <div class="col-md-3"></div>
            <div class="col-md-6"  style="padding:0px;margin-top: 7px;">
                <form method="post"nctype="multipart/form-data" id="xlsfile" >
                    <div class="input-group">
                        <input type="file" class="form-control"  name="file" id="file"/>
                        <span class="input-group-addon"><i class="fa fa-file"></i></span>
                    </div>
                    <button type="button"   class="btn btn-block btn-success" style="margin-top:25px;" id="xls_submit">Upload</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12" style="padding: 0px;">
        <h3>Risultati</h3>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>Risultati</span>
                        </div>
                        <div class="panel-body table-responsive">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">
                                <thead>
                                     <tr>
                                        <th>ID</th>
                                        <th> P. IVA</th>
                                        <th> Denominazione</th>
                                        <th> Elenco</th>
                                    </tr>
                                </thead>                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Notification</h4>
                </div>
                <div class="modal-body">
                    <div class="progress">
                        <div class="progress-bar"  id="progressor" role="progressbar" aria-valuenow="20" aria-valuemin="0" data-original-title="0%" aria-valuemax="100" style="width: 0%" >     
                        </div> 
                    </div>
                    <h5 id="progress_data"></h5>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-success" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="../js/jquery.dataTables.min.js" type="text/javascript"></script>
        <script src="../js/dataTables.bootstrap.min.js" type="text/javascript"></script>
</body>
</html>



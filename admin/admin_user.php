<?php
include 'header.php';
?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" style=height:500px;"> 
                <iframe src="" class="iframe" id="frame" height="100%" width="100%">></iframe> 
            </div>
        </div> 
    </div>
</div>
<h3>Elenco Utenti</h3>


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span>Elenco Utenti</span>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body table-responsive">
                    <table width="100%" class="table table-striped table-bordered table-hover" id="displayData">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome e Cognome</th>
                                <th>E-Mail</th>
                                <th>Società</th>
                                <th>Numero Cellulare</th>
                                <th>Indirizzo</th>
                                <th>Scadenza</th>
                                <th>Link</th>
                                <th>Visibile</th>
                                <th>Modifica</th>
                                <th>Cancella</th>
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
<br>
<!-- Session Destroy -->

<a href="../session_destroy.php" class="a">Session Destroy</a>

<!--  -->


<script>
    $(document).ready(function () {
//                 
        getDataTable();

    });

    function getDataTable() {
        var dataTable = $('#displayData').DataTable({
            "processing": true, "destroy": true,
            "serverSide": true,
            "ajax": {
                url: "adm_users_table.php", // json datasource
                type: "post", // method  , by default get
                error: function () {  // error handling
                }
            }
        });
    }
    function getModelPdf(pdfName)
    {
        var name = '../pdf-attachments/' + pdfName;
        $('#frame').attr('src', name);
        $('#myModal').modal('show');

    }
    function changeVisibility(id, vis) {
        myData = {id: id, vis};
        alertify.confirm('Are u sure?', 'Do you want to change visibility of user.',
                function () {
                    $.ajax({
                        type: "POST",
                        url: 'adm_change_visibility.php',
                        data: myData,
                        dataType: 'json',
                        success: function (data) {
                            if (data.Status == true) {
                                alertify.success(data.Message);
                            } else {
                                alertify.warning(data.Message);
                            }
                            getDataTable();
                        }
                    });
                },
                function () {}
        );
    }
    function deleteUser(id) {
        myData = {id: id};
        alertify.confirm('Are u sure?', 'Do you want to delete user record.',
                function () {
                    $.ajax({
                        type: "POST",
                        url: 'adm_delete_user.php',
                        data: myData,
                        dataType: 'json',
                        success: function (data) {
                            if (data.Status == true) {
                                alertify.success(data.Message);
                            } else {
                                alertify.warning(data.Message);
                            }
                            getDataTable();
                        }
                    });
                },
                function () {}
        );
    }
</script>
</body>



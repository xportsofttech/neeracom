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
            <h3>Log Attività</h3>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <span>Log Attività</span>
								<div style="text-align:right;"> <a href="downloadcsv.php" target="_blank" class="btn btn-sm btn-success" id="download" 
                        			style="margin-top:3px;background-color: #86BC25;"><i class="fa fa-download"></i> &emsp; Scarica registro xlsx</a>                             
                        </div>
                            </div>
							
                            <!-- /.panel-heading -->
                            <div class="panel-body table-responsive">
                                <table width="100%" class="table table-striped table-bordered table-hover" id="displayData">
                                    <thead>
                                        <tr>
                                            <th>Attività</th>
                                            <th style="max-width:200px;!important">Parametri</th>
                                            <th>Risposta</th>
                                            <th>IP</th>
                                            <th>Data</th>
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
        <script>
            $(document).ready(function () {
//                 
                var dataTable = $('#displayData').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        url: "get_Verify.php", // json datasource
                        type: "post", // method  , by default get
                        error: function () {  // error handling
                        }
                    }
                });  
                
            }); 
                 
            function getModelPdf(pdfName)
            {
                var name ='../'+pdfName;
                $('#frame').attr('src', name);
                
            }
        </script>
    </body>

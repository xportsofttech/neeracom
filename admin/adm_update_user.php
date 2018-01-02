  <?php
  include 'header.php';
  
  require_once '../dbconnect.php';
  $q="select * from user where id='".$_GET['uid']."'";
  $res=mysqli_query($conn,$q);
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
            <h3>Update User Detail</h3>
            <div id="page-wrapper">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <form method="post" id="updateUser" action="">
                            <div class="panel panel-info">
                                <div class="panel-heading">User Detail</div>
                                <div class="panel-body">
                                    <?php
                                        while($u= mysqli_fetch_assoc($res)){
                                    ?>
                                    <input type='hidden' name='uid' value='<?= $u['id']; ?>'/>
                                    <div class="col-md-6" class="padding:0px;">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" name="ufname" value="<?= $u['first_name']; ?>" placeholder="Enter user first name" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6" class="padding:0px;">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                            <input type="text" name="ulname" value="<?= $u['last_name']; ?>" placeholder="Enter user last name" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                                            <input type="text" name="uemail" value="<?= $u['email']; ?>" placeholder="Enter user email" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-building-o"></i></span>
                                            <input type="text" name="ucname" value="<?= $u['company_name']; ?>" placeholder="Enter user company name" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                            <input type="text" name="umob" value="<?= $u['mobile']; ?>" placeholder="Enter user mobile" class="form-control"/>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                                            <input type="text" name="uaddr" value="<?= $u['address']; ?>" placeholder="Enter Address" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar-check-o"></i></span>
                                            <input type="text" name="connections_available" value="<?= $u['connections_available']; ?>" placeholder="number of connections available" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group date" data-provide="datepicker" >
                                            <div class="input-group-addon">
                                                <span class="fa fa-calendar-check-o"></span>
                                            </div>
                                            <input type="text" class="form-control" name="ute" value="<?= $u['trial_expire']; ?>">
                                            
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="panel-footer">
                                    <button type="submit" class="btn btn-success">Update user</button>
                                    <a href="admin_user.php" class="btn btn-default">Cancel</a>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
    			$('.datepicker').datepicker();
    			$('.date').datepicker({
                            format: "yyyy/mm/dd",
                            todayBtn: "linked",
                            autoclose: true,
                            toggleActive: true
                        });
    		});
            $("#updateUser").submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: 'adm_update_user_submit.php',
                    data: $("#updateUser").serialize(),
                    dataType:'json',
                    success: function(data){
                        if(data.Status==true){
                            alertify.alert(data.Message, function(){
                                window.location='admin_user.php';
                            });
                        }
                        else if(data.Status==false){
                            alertify.warning(data.Message); 
                        }
                    }
                });
            });
        </script>
    </body>



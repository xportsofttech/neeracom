<?php
    require_once '../dbconnect.php';
    $vis=$_POST['vis'];
    $vis==1?0:1;
    $v = ($vis == 1 ? 0 : 1);
    $q="update user set visibility='".$v."' where id='".$_POST['id']."'";
    $res=mysqli_query($conn,$q);
    if($res){
        $ret=array('Status'=>true,'Message'=>'Visibility successfully changed');
    }
    else{
        $ret=array('Status'=>false,'Message'=>'Something went wring');
    }
    echo json_encode($ret);
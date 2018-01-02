<?php
    require_once '../dbconnect.php';
    $q="update user set deleted='1' where id='".$_POST['id']."'";
    $res=mysqli_query($conn,$q);
    if($res){
        $ret=array('Status'=>true,'Message'=>'User Successfully Deleted');
    }
    else{
        $ret=array('Status'=>false,'Message'=>'Something went wring');
    }
    echo json_encode($ret);
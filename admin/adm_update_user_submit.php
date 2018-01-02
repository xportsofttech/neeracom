<?php
    require_once '../dbconnect.php';
    $ufname=$_POST['ufname'];
    $ulname=$_POST['ulname'];
    $uemail=$_POST['uemail'];
    $ucname=$_POST['ucname'];
    $umob=$_POST['umob'];
    $uaddr=$_POST['uaddr'];
    $ute=$_POST['ute'];
    $uid=$_POST['uid'];
    $connections_available=$_POST['connections_available'];
    
    $q="update user set first_name='".$ufname."', last_name='".$ulname."', email='".$uemail."', company_name='".$ucname."', mobile='".$umob."', address='".$uaddr."', trial_expire='".$ute."', connections_available='".$connections_available."' where id='".$uid."'";
    $res=mysqli_query($conn,$q);
    if($res){
        $ret=array('Status'=>true,'Message'=>'User Successfully updated');
    }
    else{
        $ret=array('Status'=>false,'Message'=>'Something went wrong');
    }
    echo json_encode($ret);
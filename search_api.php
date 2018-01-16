<?php
$servername="localhost";
$username="split_info";
$password="Qi1FB8RBi]?*";
$database="split_info";

$conn=mysqli_connect($servername,$username,$password);
$db=mysqli_select_db($conn,$database);

if(!$db){
	echo "Can not connect to split_info".mysqli_error($conn);
}

/* for post call */
if(isset($_POST['authorize'])){
	if($_POST['authorize']=='CFTLGSCK'){
		$userSQL=mysqli_query($conn,"select * from user where id='".$_POST['userid']."'");
		$num_rows=mysqli_num_rows($userSQL);
		if($num_rows>0){
			$sqlQry=mysqli_query($conn,"select * from information where text='".$_POST['code']."'");
			$row=mysqli_fetch_assoc($sqlQry);
			$text=$row['text'];
			$text1=$row['text1'];
			$text2=$row['text2'];
			$array=array("text"=>$text,"text1"=>$text1,"text2"=>$text2);
			echo $data=json_encode($array);
		}else{
			echo "2";
		}
	}else{
		echo "1";
	}
}

/* for get call */
if(isset($_GET['authorize'])){
	if($_GET['authorize']=="CFTLGSCK"){
		$userSQL=mysqli_query($conn,"select * from user where id='".$_GET['userid']."'");
		$num_rows=mysqli_num_rows($userSQL);
		if($num_rows>0){
			$sqlQry=mysqli_query($conn,"select * from information where text='".$_GET['code']."'");
			$row=mysqli_fetch_assoc($sqlQry);
			$text=$row['text'];
			$text1=$row['text1'];
			$text2=$row['text2'];
			$array=array("text"=>$text,"text1"=>$text1,"text2"=>$text2);
			echo $data=json_encode($array);
		}else{
			echo "2";
		}
	}else{
		echo "1";
	}
}
?>
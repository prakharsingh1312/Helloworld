<?php
session_start();
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

include("Assets/php/dbconfig.php");
if(isset($_SESSION['email']))
header("location:redirect.php");
$email=mysqli_real_escape_string($dbconfig,$_GET['email']);
$hash=mysqli_real_escape_string($dbconfig,$_GET['hash']);
$query=$dbconfig->prepare("SELECT * from user_login WHERE email=? AND hash=?");
$query->bind_param("ss",$email,$hash);
$query->execute();
$query=$query->get_result();
$count=$query->num_rows;
if($count==1)
{
	$query=$dbconfig->prepare("UPDATE user_login SET activated=1 WHERE hash=? AND email=?");
	$query->bind_param("ss",$hash,$email);
	$query->execute();
	$_SESSION['acti']=1;
	
}
else
	alert("Invalid Token");
header("location:index.php");
	
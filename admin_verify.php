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
$query=mysqli_query($dbconfig,"SELECT * from admin_login WHERE email='$email' AND hash='$hash'");
$count=mysqli_num_rows($query);
if($count==1)
{
	$query=mysqli_query($dbconfig,"UPDATE admin_login SET activated=1 WHERE hash='$hash' AND email='$email'");
	$_SESSION['acti']=1;
	header("location:admin.php");
}
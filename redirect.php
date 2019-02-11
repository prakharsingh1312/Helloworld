<?php
session_start();
if(!isset($_SESSION['email']))
	header("location:index.php");
else
	header("location:dashboard.php");
?>
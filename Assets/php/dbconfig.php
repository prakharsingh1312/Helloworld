<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'test';
$dbconfig = new mysqli($host,$username,$password,$database);
if($dbconfig->connect_error)
{
	echo "Connection Failed : ".$dbconfig->connect_error;
}
?>
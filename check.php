<?php
session_start();
include('Assets/php/dbconfig.php');
$ans=array();
$points=0;$tie=0;$normal=0;
for($i=1;$i<=$_SESSION['numq'];$i++)
{
	$ans[$i]=mysqli_real_escape_string($dbconfig,$_POST[$i]);
	if($_SESSION['cans'][$i]==$ans[$i])
	{
		$points+=$_SESSION['points'][$i];
		if($_SESSION['tie'][$i]==1)
			$tie++;
		else
			$normal++;
			
	}
}
for($i=1;$i<=$_SESSION['numq'];$i++)
{
$query=mysqli_query($dbconfig,"INSERT INTO admin_{$_SESSION['id']}_{$_SESSION['cid']}_response (userid,qid,response) VALUES ({$_SESSION['uid']},{$_SESSION['qid'][$i]},'{$ans[$i]}')");

}
$query=mysqli_query($dbconfig,"UPDATE admin_{$_SESSION['id']}_{$_SESSION['cid']}_res SET total=$points, tie=$tie,normal=$normal WHERE userid={$_SESSION['uid']}");
header("location:test_submitted.php");
?>
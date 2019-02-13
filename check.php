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
$query=$dbconfig->prepare("INSERT INTO admin_{$_SESSION['id']}_{$_SESSION['cid']}_response (userid,qid,response) VALUES (?,?,?)");
	$query->bind_param("iis",$_SESSION['uid'],$_SESSION['qid'][$i],$ans[$i]);
	$query->execute();
	$query->close();

}
$query=$dbconfig->prepare("UPDATE admin_{$_SESSION['id']}_{$_SESSION['cid']}_res SET total=?, tie=?,normal=? WHERE userid=?");
$query->bind_param("iiii",$points,$tie,$normal,$_SESSION['uid']);
	$query->execute();
	$query->close();
header("location:test_submitted.php");
?>
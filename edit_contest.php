<?php
session_start();
include("Assets/php/dbconfig.php");
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
function check_pass($i,$dbconfig,$password){
	$password=crypt($password, '$2a$07$CCSCodersUnderSiegelul$');
	$query=$dbconfig->prepare("SELECT * from {$i}_login where email=? and password=?");
	$query->bind_param("ss",$_SESSION['email'],$password);
	$query->execute();
	$query=$query->get_result();
	return $query->num_rows;
}


if(!isset($_SESSION['email'])||$_SESSION['admin']!=1)
header("location:admin.php");
if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['submit']=="update")
{
		$cname=mysqli_real_escape_string($dbconfig,$_POST['cname']);
	$des=mysqli_real_escape_string($dbconfig,$_POST['des']);
		$oname=mysqli_real_escape_string($dbconfig,$_POST['oname']);
		$stime=strtotime($_POST['sdate']);
		$stime=date("Y:m:d H:i:s",$stime);
		$etime=strtotime($_POST['edate']);
		$etime=date("Y:m:d H:i:s",$etime);
		$ic=mysqli_real_escape_string($dbconfig,$_POST['ic']);
		$query=$dbconfig->prepare("UPDATE all_contests set name=?,org_name=?,start_time=?,end_time=?,ic=?,des=? where contestid=?");
	$query->bind_param("ssssssi",$cname,$oname,$stime,$etime,$ic,$des,$_SESSION['cid']);
	$query->execute();
		alert("Contest Successfully Updated");
}
if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['submit']!="update")
{
	$_SESSION['cid']=mysqli_real_escape_string($dbconfig,$_POST['submit']);
	$query=$dbconfig->prepare("SELECT userid from all_contests where contestid=?");
	$query->bind_param("i",$_SESSION['cid']);
	$query->execute();
	$query=$query->get_result();
	$row=$query->fetch_assoc();
	$_SESSION['id']=$row['userid'];
}
	$query=$dbconfig->prepare("SELECT * from all_contests where contestid=?");
	$query->bind_param("i",$_SESSION['cid']);
	$query->execute();
	$query=$query->get_result();
	$row=$query->fetch_assoc();
	$stime=strtotime($row['start_time']);
	$stime=date("d M Y H:i",$stime);
	$etime=strtotime($row['end_time']);
	$etime=date("d M Y H:i",$etime);


?>
<html>
<head>
<head>
<meta charset="utf-8">
<title>Creative Computing Society</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="Assets/css/bootstrap-datetimepicker.min.css">
	<script defer src="https://use.fontawesome.com/releases/v5.7.1/js/all.js" integrity="sha384-eVEQC9zshBn0rFj4+TU78eNA19HMNigMviK/PU/FFjLXqa/GKPgX58rvt5Z8PLs7" crossorigin="anonymous"></script>
	<script src="Assets/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
	<script type="text/javascript">
	function srvTime(){
    try {
        //FF, Opera, Safari, Chrome
        xmlHttp = new XMLHttpRequest();
    }
    catch (err1) {
        //IE
        try {
            xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
        }
        catch (err2) {
            try {
                xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
            }
            catch (eerr3) {
                //AJAX not supported, use CPU time.
                alert("AJAX not supported");
            }
        }
    }
    xmlHttp.open('HEAD',window.location.href.toString(),false);
    xmlHttp.setRequestHeader("Content-Type", "text/html");
    xmlHttp.send('');
    return xmlHttp.getResponseHeader("Date");
}</script>
</head>
</head>

<body>
	<nav class="navbar display-fixed navbar-toggleable-md navbar-inverse navbar-dark bg-dark text-white navbar-expand-lg">
  <a class="navbar-brand">HelloWorld</a>

   <div class="collapse navbar-collapse" id="navbarSupportedContent">
	   <ul class="navbar-nav mr-auto">
	   <li class="nav-item">
        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
      </li>
		   <?php
		   if($_SESSION['user']==1)
		   { echo '
      <li class="nav-item">
        <a class="nav-link" href="attempted_contests.php">Attempted Contests</a>
      </li>';}
		   if($_SESSION['admin']==1)
		   { echo '
      <li class="nav-item">
        <a class="nav-link" href="create_contest.php">Create Contest</a>
      </li><li class="nav-item">
        <a class="nav-link" href="manage_contests.php">Manage Contests</a>
      </li>';}
		   ?></ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown hidden-md-down">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php echo $_SESSION['name'] ?>
        </a>
        <div class="dropdown-menu position-absolute dropdown-menu-right text-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="account.php">Account</a>
          <a class="dropdown-item" href="notification.php">Notifications</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
      </li>
	  </ul>
    </div>
		</nav>
	<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="edit_contest.php">General Settings</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="view_questions.php">View Questions</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="add_question.php">Add Question</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="view_participants.php" >View Participants</a>
  </li>
		<li class="nav-item">
    <a class="nav-link" href="moderators.php" >Moderators</a>
  </li>
		<li class="nav-item">
    <a class="nav-link" href="leaderboard.php" >Leaderboard</a>
  </li>
</ul>
	<br>
<br>
<br>
<div class="row">
	<div class="col-sm-1"></div><h1 class="display-4">General Settings</h1></div>
	<br>
	<form class="form" action="edit_contest.php" method="post">
<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder"><label for="exampleInputEmail1">Contest Name:</label></div> <div class="col-sm-6"><input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Name" name='cname' value='<?php echo $row['name'];?>' required></div></div><br>

		<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder"><label for="exampleInputEmail1">Secret Key:</label></div> <div class="col-sm-6"><input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Name" name='ic' value='<?php echo $row['ic'];?>' required></div></div><br>

		<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder"><label for="exampleInputEmail1">Start Date:</label></div><div class="col-sm-6"><div class="form-group"> <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" name="sdate"  id="sdate" value='<?php echo $stime;?>' required readonly>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"><i class="far fa-calendar-alt fa-2x"></i></span>
		</span></div>
			</div></div><script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker({
					format:"dd MM yyyy hh:ii" ,
				
				});
            });
        </script></div><br>

		<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder"><label for="exampleInputEmail1">End Date:</label></div><div class="col-sm-6"><div class="form-group"> <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" name="edate"  id="edate" value='<?php echo $etime;?>' required readonly>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"><i class="far fa-calendar-alt fa-2x"></i></span>
		</span></div>
			</div></div><script type="text/javascript">
            $(function () {
                $('#datetimepicker2').datetimepicker({
					format:"dd MM yyyy hh:ii" ,
				
				});
            });
        </script></div>
		<br>

		<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder"><label for="exampleInputEmail1">Organization Name:</label></div> <div class="col-sm-6"><input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Name" name='oname' value='<?php echo $row['org_name'];?>'required></div></div><br>
		<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder"><label for="exampleInputEmail1">Description:</label></div> <div class="col-sm-6"><textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Description" name='des' required><?php echo $row['des'];?></textarea></div></div><br>

	<div class="row">
		<div class="col-sm-3 ml-auto"><button type="submit" class="btn btn-primary" name="submit" value="update">Update</button></div></div></form>
</body>
</html>
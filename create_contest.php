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
elseif($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['submit']=="create")
	{
		$cname=mysqli_real_escape_string($dbconfig,$_POST['cname']);
		$oname=mysqli_real_escape_string($dbconfig,$_POST['oname']);
		$des=mysqli_real_escape_string($dbconfig,$_POST['des']);
		$stime=strtotime($_POST['sdate']);
		$stime=date("Y:m:d h:i:s",$stime);
		$etime=strtotime($_POST['edate']);
		$etime=date("Y:m:d h:i:s",$etime);
		$ic=mysqli_real_escape_string($dbconfig,$_POST['ic']);
		$id=$_SESSION['uid'];
		$cid=$dbconfig->prepare("SELECT `AUTO_INCREMENT`
FROM  INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'test'
AND   TABLE_NAME   = 'all_contests';");
		$cid->execute();
		$cid=$cid->get_result();
		$cid=$cid->fetch_assoc();
		$cid=$cid['AUTO_INCREMENT'];
		$result=$dbconfig->prepare("INSERT INTO all_contests (ic,contestid,name,start_time,end_time,org_name,userid,des) VALUES (?,?,?,?,?,?,?,?)");
		$result->bind_param("sissssis",$ic,$cid,$cname,$stime,$etime,$oname,$id,$des);
		$result->execute();
		$result->close();
		$result1=$dbconfig->prepare("CREATE TABLE admin_{$id}_{$cid}_pre (qid int(10) NOT NULL AUTO_INCREMENT,question varchar(10000),choice1 varchar(1000),choice2 varchar(1000),choice3 varchar(1000),answer varchar(1000),tie int(1),text int(1),points int(10), PRIMARY KEY (`qid`))");
		$result1->execute();
		$result1->close();
		$result1=$dbconfig->prepare("CREATE TABLE admin_{$id}_{$cid}_res (userid int(10) NOT NULL,total int(10),normal int(10),tie int(10),dq int(1) NOT NULL DEFAULT '0', PRIMARY KEY (`userid`))");
		$result1->execute();
		$result1->close();
		$result1=$dbconfig->prepare("CREATE TABLE admin_{$id}_{$cid}_response (userid int(10),qid int(10),response varchar(1000))");
		$result1->execute();
		$result1->close();
		alert("Contest Created Successfully.");
		
	}
}
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
      <li class="nav-item active">
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
		</nav><br><br><br>
	<div class="row"><div class="col-sm-1"></div><h1 class="display-4">Create Contest</h1></div><br>
	<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-6"><form method="post" action="create_contest.php">
  <div class="form-group">
    <label for="exampleInputEmail1">Name Of Contest</label>
    <input type="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Name" required name="cname">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Start Date/Time</label>
    <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control" name="sdate"  id="sdate" required readonly>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"><i class="far fa-calendar-alt fa-2x"></i></span>
		</span>
                </div>
 
     
  </div><script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker({
					format:"dd MM yyyy hh:ii" ,
				
				});
            });
        </script>
		<div class="form-group">
    <label for="exampleInputPassword1">End Date/Time</label>
    <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" name="edate" required readonly>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"><i class="far fa-calendar-alt fa-2x"></i></span>
		</span>
                </div>
 
     
  </div><script type="text/javascript">
            $(function () {
                $('#datetimepicker2').datetimepicker({
					format:"dd MM yyyy hh:ii" ,
					minDate:document.getElementById('sdate').value
				});
            });
        </script> 
  <div class="form-group">
   <label for="exampleInputEmail1">Name Of Organization</label>
    <input type="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Organization Name" required name="oname">
  </div>
		<div class="form-group">
   <label for="exampleInputEmail1">Description</label>
    <textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Description" required name="des"></textarea>
  </div>
		<div class="form-group">
   <label for="exampleInputEmail1">Secret Key</label>
    <input type="password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Secret Key" required name="ic">
  </div>
  <button type="submit" class="btn btn-primary" name="submit" value="create">Create</button>
</form></div></div>
</body>
</html>
	
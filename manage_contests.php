<?php
session_start();
include("Assets/php/dbconfig.php");
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
function check_pass($i,$dbconfig,$password){
	$password=crypt($password, '$2a$07$CCSCodersUnderSiegelul$');
	$query=mysqli_query($dbconfig,"SELECT * from {$i}_login where email='{$_SESSION['email']}' and password='$password'");
	$count=mysqli_num_rows($query);
	return $count;
}


if(!isset($_SESSION['email'])||$_SESSION['admin']!=1)
header("location:admin.php");
elseif($_SERVER['REQUEST_METHOD']=="POST")
{
	$id=$_SESSION['uid'];
	$cid=mysqli_real_escape_string($dbconfig,$_POST['submit']);
	$result=mysqli_query($dbconfig,"DROP TABLE admin_{$id}_{$cid}_pre,admin_{$id}_{$cid}_res,admin_{$id}_{$cid}_response");
	$result=mysqli_query($dbconfig,"DELETE FROM all_contests where contestid=$cid");
	$result=mysqli_query($dbconfig,"DELETE FROM moderators where contestid=$cid");
	alert("Contest Deleted Successfully");
}
?>
<html>
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
      <li class="nav-item">
        <a class="nav-link" href="create_contest.php">Create Contest</a>
      </li><li class="nav-item active">
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
	
	<div class="row"><div class="col-sm-1"></div><h1 class="display-4">Manage Contests</h1></div><br><div class="row"><div class="col-sm-1"></div><h3><small class="text-muted">Administrated Contests</small></h3></div><br>
	<div class="row"><div class="col-sm-1"></div><div class="col-sm-6 font-weight-bolder">Contest Name</div><div class="col-sm-5 font-weight-bolder">Action</div></div>
	<?php
	$result="SELECT name,contestid from all_contests where userid={$_SESSION['uid']}";
	$query=mysqli_query($dbconfig,$result);
			while ($row1 = $query->fetch_assoc()){

    $row[] = $row1;

}
			$count=mysqli_num_rows($query);
			if($count==0)
				echo '<br>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-6">No contests created.</div></div>';
			
			else{
				
			foreach($row as $contest)
			{
				echo'<br>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-6">'.$contest['name'].'</div><div class="col-sm-5"><div class="btn-group" role="group" aria-label="Basic example"><form method="post" action="edit_contest.php"> <button type="submit" class="btn btn-dark" name="submit" value="'.$contest['contestid'].'">Edit</button></form><form method="post" action="manage_contests.php"> <button type="submit" class="btn btn-dark" name="submit" value="'.$contest['contestid'].'">Delete</button></form></div></div></div>';
			}}
	?>
	<br><div class="row"><div class="col-sm-1"></div><h3><small class="text-muted">Moderated Contests</small></h3></div><br>
	<div class="row"><div class="col-sm-1"></div><div class="col-sm-6 font-weight-bolder">Contest Name</div><div class="col-sm-5 font-weight-bolder">Action</div></div>
	<?php
	$result="SELECT name,all_contests.contestid from all_contests,moderators where moderators.userid={$_SESSION['uid']} and moderators.contestid=all_contests.contestid";
	$query=mysqli_query($dbconfig,$result);
			while ($row1 = $query->fetch_assoc()){

    $row[] = $row1;
			}
			$count=mysqli_num_rows($query);
			if($count==0)
				echo '<br>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-6">No contests moderated.</div></div>';
			
			else{
				
			foreach($row as $contest)
			{
				echo'<br>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-6">'.$contest['name'].'</div><div class="col-sm-5"><div class="btn-group" role="group" aria-label="Basic example"><form method="post" action="edit_contest.php"> <button type="submit" class="btn btn-dark" name="submit" value="'.$contest['contestid'].'">Edit</button></form></div></div></div>';
			}}
	?>
</body>
</html>
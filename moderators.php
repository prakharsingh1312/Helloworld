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
if($_SERVER['REQUEST_METHOD']=="POST")
{
	if($_POST['submit']=='add')
	{
		$email=mysqli_real_escape_string($dbconfig,$_POST['email']);
		$query3=mysqli_query($dbconfig,"SELECT userid FROM admin_login WHERE email='$email'");
		$result=mysqli_fetch_array($query3,MYSQLI_ASSOC);
		$id=$result['userid'];
		$query2=mysqli_query($dbconfig,"INSERT INTO moderators (userid,contestid) VALUES ($id,{$_SESSION['cid']})");
		alert("Moderator added successfully");
	}
	else{
		$id=mysqli_real_escape_string($dbconfig,$_POST['submit']);
		$query1=mysqli_query($dbconfig,"DELETE FROM moderators WHERE userid=$id AND contestid={$_SESSION['cid']}");
		alert("Moderator deleted successfully.");
	}
}
?>
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
    <a class="nav-link" href="edit_contest.php">General Settings</a>
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
    <a class="nav-link  active" href="moderators.php" >Moderators</a>
  </li>
		<li class="nav-item">
    <a class="nav-link" href="leaderboard.php" >Leaderboard</a>
  </li>
</ul>
<br>
<br>
<br><form action="moderators.php" method="post">
	<div class="row">
	
	<div class="col-sm-1"></div>
		<div class="col-sm-1">
  <div class="form-group">
    <label for="staticEmail" class="col-form-label"></label></div></div>
    <div class="col-sm-6">
      <label class="form-text text-muted">ADD MODERATOR</label> 
		</div></div>
<div class="row"><div class="col-sm-2"></div>
  <div class="col-sm-2"><div class="form-group">
	  <label for="inputPassword" class="col-form-label font-weight-bolder">Email:</label></div></div>
    <div class="col-sm-4">
      <input type="email" class="form-control" id="inputPassword" placeholder="Email" name='email'>
    </div>
	<div class='col-sm-2'><button type="submit" class="btn btn-dark" name="submit" value="add">Add Moderator</button>
  </div>
</div></form>
<div class="row">
	<div class="col-sm-1"></div><h1 class="display-4">Moderators</h1></div>
	<br>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder">Name</div><div class="col-sm-5 font-weight-bolder">Email</div><div class="col-sm-3 font-weight-bolder">Actions</div></div>
<?php
	$query=mysqli_query($dbconfig,"SELECT moderators.userid,admin_login.email,admin_login.name FROM admin_login,moderators WHERE moderators.contestid={$_SESSION['cid']} AND moderators.userid=admin_login.userid");
			while ($row = $query->fetch_assoc()){

    $result1[] = $row;
			

}
			$count=mysqli_num_rows($query);
			if($count==0)
			{
				echo'<br><div class="row"><div class="col-sm-1"></div><div class="col-sm-3">No Moderators Added.</div><div class="col-sm-5 font-weight-bolder"></div><div class="col-sm-3 font-weight-bolder"></div></div>';
			}
			else
			{
				foreach($result1 as $row)
				{
					echo'<div class="row"><div class="col-sm-1"></div><div class="col-sm-3">'.$row['name'].'</div><div class="col-sm-5 ">'.$row['email'].'</div><div class="col-sm-3"><form class="form" method="post" action="moderators.php"><button type="submit" class="btn btn-dark" name="submit" value="'.$row['userid'].'">Delete</button></form></div></div>';
				}
			}
	?>
	
</body>
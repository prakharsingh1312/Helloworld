<?php
session_start();
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

include("Assets/php/dbconfig.php");
if(!isset($_SESSION['email']))
header("location:redirect.php");
?>
<html>
<head>
<meta charset="utf-8">
<title>Creative Computing Society</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</head>

<body>
	<nav class="navbar fixed-top navbar-toggleable-md navbar-inverse navbar-dark bg-dark text-white navbar-expand-lg">
  <a class="navbar-brand">HelloWorld</a>

   <div class="collapse navbar-collapse" id="navbarSupportedContent">
	   <ul class="navbar-nav mr-auto">
	   <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">Home <span class="sr-only">(current)</span></a>
      </li>
		   <?php
		   //if($_SESSION['user']==1)
		   //{ echo '
     // <li class="nav-item">
       // <a class="nav-link" href="attempted_contests.php">Attempted Contests</a>
      //</li>';}
		   if($_SESSION['admin']==1)
		   { echo '
      <li class="nav-item">
        <a class="nav-link" href="create_contest.php">Create Contest</a>
      </li>
	  <li class="nav-item">
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
	<br><br><br>
	<div class="row"><div class="col-sm-1"></div><h1 class="display-4">Active Contests</h1></div><br>
	<?php 
	$query=mysqli_query($dbconfig,"SELECT * from all_contests");
	while ($result=mysqli_fetch_array($query,MYSQLI_ASSOC))
	{
		
	$stime=strtotime($result['start_time']);
		$stime=date("d M Y h:i",$stime);
	echo '<div class="row"><div class="col-sm-1"></div><div class="jumbotron w-75">
  <h1 class="display-4">'.$result['name'].'</h1>
  <p class="lead">'.$result['des'].'</p>';
		if(date("d M Y h:i")<$stime)echo '
  <hr class="my-4">
  <p>Starts On: '.$stime.'</p>';
		else 
			echo'
			<hr class="my-4"><form action="play.php" method="post">
  <button type="submit" value="'.$result['contestid'].'" class="btn btn-dark btn-lg" name="submit">Enter Contest</button>
</div> </div>'; 
	}?>
	</body>
</html>
	
<?php
session_start();
include("Assets/php/dbconfig.php");
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
function check_pass($i,$dbconfig,$password){
	$password=crypt($password, '$2a$07$CCSCodersUnderSiegelul$');
	$query=$dbconfig->prepare($dbconfig,"SELECT * from {$i}_login where email=? and password=?");
	$query->bind_param("ss",$_SESSION['email'],$password);
	$query->execute;
	return $query->affected_rows;
}


if(!isset($_SESSION['email']))
header("location:redirect.php");
else
{
	
	{
		if($_SERVER['REQUEST_METHOD']=="POST")
		{
			{
				if($_SESSION['user']==1)
					$i='user';
				else if($_SESSION['admin']==1)
					$i='admin';
			}
			{
			if($_POST['submit']=='name')
			{
				$j=check_pass($i,$dbconfig,$_POST['password']);
				if($j==1)
				{
				$name=mysqli_real_escape_string($dbconfig,$_POST['name']);
			$query=mysqli_query($dbconfig,"UPDATE {$i}_login SET name='$name' where email='{$_SESSION['email']}'");
				alert("Name Updated Successfully.");
			}
				else
					alert("Incorrect Password.");
			}
				
				else if($_POST['submit']=='enumber')
				{
					$j=check_pass($i,$dbconfig,$_POST['password']);
				if($j==1)
				{
					$enrollment_number=mysqli_real_escape_string($dbconfig,$_POST['enumber']);
					$query=mysqli_query($dbconfig,"UPDATE {$i}_login SET enrollment_number=$enrollment_number where email='{$_SESSION['email']}'");
					alert("Enrollment Number Updated Successfully.");
				}
					else
					alert("Incorrect Password.");
				}
				else if($_POST['submit']=='number')
				{
					$j=check_pass($i,$dbconfig,$_POST['password']);
				if($j==1)
				{
					$number=mysqli_real_escape_string($dbconfig,$_POST['number']);
					$query=mysqli_query($dbconfig,"UPDATE {$i}_login SET mobile=$number where email='{$_SESSION['email']}'");
					alert("Mobile Number Updated Successfully.");
				}
					else
					alert("Incorrect Password.");
				}
				else if($_POST['submit']=='password')
				{
					$j=check_pass($i,$dbconfig,$_POST['password']);
				if($j==1)
				{
					$npassword=mysqli_real_escape_string($dbconfig,$_POST['npassword']);
					$npassword=crypt($npassword, '$2a$07$CCSCodersUnderSiegelul$');
					$query=mysqli_query($dbconfig,"UPDATE {$i}_login SET password='$npassword' where email='{$_SESSION['email']}'");
					alert("Password Updated Successfully.");
				}
					else
					alert("Incorrect Password.");
				}
				
			
			}
		}
	}
	{
	if($_SESSION['user']==1)
	$query=mysqli_query($dbconfig,"SELECT name,enrollment_number,mobile,email FROM user_login WHERE email='{$_SESSION['email']}'");
	else
		$query=mysqli_query($dbconfig,"SELECT name,enrollment_number,mobile,email FROM admin_login WHERE email='{$_SESSION['email']}'");
	$row=mysqli_fetch_array($query,MYSQLI_ASSOC);
	}
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
          <a class="dropdown-item active" href="account.php">Account</a>
          <a class="dropdown-item" href="notification.php">Notifications</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
      </li>
	  </ul>
    </div>
		</nav><br><br><br><div class="row"><div class="col-sm-1"></div><h1 class="display-3">Account Details</h1></div><br>
	
	<div id="accordion" class="accordion " >
  <div class="card">
    <div class="card-header" id="headingOne">
		<div class="row"><div class="col-sm-1"></div><div class="col-sm-2 h5 font-weight-bolder">Name:</div><div class="col-sm-7 h6"><?php echo $row['name']; ?></div><div class="col-sm-2"> 
     
        <button class="btn btn-dark collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          Change
        </button>
	 </div></div>
		
    </div>

    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        <form method="post" action="account.php">
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Name" name="name" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required>
  </div>
  
  <button type="submit" class="btn btn-primary" name="submit" value="name">Update</button>
</form>
      </div>
	  </div> </div>
		<div class="card">
    <div class="card-header" id="headingTwo">
		<div class="row"><div class="col-sm-1"></div><div class="col-sm-2 h5 font-weight-bolder">Enrollment Number:</div><div class="col-sm-7 h6"><?php echo $row['enrollment_number']; ?></div><div class="col-sm-2"> 
     
        <button class="btn collapsed btn-dark" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          Change
        </button>
	 </div></div>
		
    </div>

    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
      <div class="card-body">
        <form method="post" action="account.php">
  <div class="form-group">
    <label for="exampleInputEmail1">Enrollment Number</label>
    <input type="text" class="form-control" id="enumber" aria-describedby="emailHelp" placeholder="Enrollment-Number" name="enumber" onKeyUp="check2();" required>
  </div><span id="message2"></span>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required>
  </div>
  
  <button type="submit" class="btn btn-primary" name="submit" value="enumber">Update</button>
</form>
      </div>
		</div> </div> 
		<div class="card">
    <div class="card-header" id="headingThree">
		<div class="row"><div class="col-sm-1"></div><div class="col-sm-2 h5 font-weight-bolder">Mobile Number:</div><div class="col-sm-7 h6"><?php echo $row['mobile']; ?></div><div class="col-sm-2"> 
     
        <button class="btn btn-dark collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          Change
        </button>
	 </div></div>
		
    </div>

    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
      <div class="card-body">
        <form method="post" action="account.php">
  <div class="form-group">
    <label for="exampleInputEmail1">Mobile Number</label>
    <input type="text" class="form-control" id="number" aria-describedby="emailHelp" placeholder="Mobile Number" name="number" onKeyUp="check();" required>
  </div><span id="message"></span>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password" required>
  </div>
  
  <button type="submit" class="btn btn-primary" name="submit" value="number">Update</button>
</form>
      </div>
			</div> </div>
		<div class="card">
    <div class="card-header" id="headingFour">
		<div class="row"><div class="col-sm-1"></div><div class="col-sm-2 h5 font-weight-bolder">Email:</div><div class="col-sm-7 h6"><?php echo $row['email']; ?></div><div class="col-sm-2"> 
     
        <button class="btn btn-dark collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          Change
        </button>
	 </div></div>
		
    </div>

    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
      <div class="card-body">
        This facility isn't currently available.
      </div>
			</div>  </div>
		<div class="card">
    <div class="card-header" id="headingFive">
		<div class="row"><div class="col-sm-1"></div><div class="col-sm-2 h5 font-weight-bolder">Password:</div><div class="col-sm-7 h6">As if we'll tell you that.</div><div class="col-sm-2"> 
     
        <button class="btn btn-dark collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
          Change
        </button>
	 </div></div>
		
    </div>

    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
      <div class="card-body">
        <form method="post" action="account.php">
  <div class="form-group">
    <label for="exampleInputEmail1">Current Password</label>
    <input type="password" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Current Password" name="password" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">New Password</label>
    <input type="password" class="form-control" id="password" placeholder="New Password" name="npassword" required>
  </div>
			<div class="form-group">
    <label for="exampleInputPassword1">Confirm Password</label>
    <input type="password" class="form-control" id="confirm_password" placeholder="Re-Enter New Password" onKeyUp="check1();" required >
  </div><span id="message1"></span><br>
  
  <button type="submit" class="btn btn-primary" name="submit" value="password">Update</button>
</form>
      </div>
			</div> </div>
		
	  </div>
	</div>
	</body>
</html>
<script type="text/javascript">
			 var check = function(){var val = document.getElementById('number').value
if (val.match(/^\d{10}$/)) {
    document.getElementById('message').innerHTML = '';
	return false;
} else {
    document.getElementById('message').style.color = 'red';
    document.getElementById('message').innerHTML = 'Please enter a 10 digit Mobile Number.';
    return false;
}}
			 var check1 = function() {
  if (document.getElementById('password').value ==
    document.getElementById('confirm_password').value) {
    document.getElementById('message1').innerHTML = '';
  } else {
    document.getElementById('message1').style.color = 'red';
    document.getElementById('message1').innerHTML = 'Passwords Do Not Match';
  }
}
			 var check2 = function(){var val = document.getElementById('enumber').value
if (val.match(/^\d{9}$/)) {
    document.getElementById('message2').innerHTML = '';
	return false;
} else {
    document.getElementById('message2').style.color = 'red';
    document.getElementById('message2').innerHTML = 'Please enter a 9 digit Enrollment Number.';
    return false;
}}</script>	
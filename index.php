<?php
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
session_start();
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

include("Assets/php/dbconfig.php");
if(isset($_SESSION['email']))
header("location:dashboard.php");
 else if($_SERVER["REQUEST_METHOD"] == "POST")
{ 
    if($_POST['submit']=='su')
	   {
    $password=mysqli_real_escape_string($dbconfig,$_POST['password']);
		$password=crypt($password, '$2a$07$CCSCodersUnderSiegelul$');
	
	$email=mysqli_real_escape_string($dbconfig,$_POST['email']);
	$name=mysqli_real_escape_string($dbconfig,$_POST['name']);
		$enrollment_number=mysqli_real_escape_string($dbconfig,$_POST['enumber']);
		$mobile=mysqli_real_escape_string($dbconfig,$_POST['number']);
    $sql_query=$dbconfig->prepare("SELECT userid FROM user_login WHERE email=?");
    $sql_query->bind_param("s",$email);
    $sql_query->execute();
	$sql_query=$sql_query->get_result();
    if($sql_query->num_rows===1)
        {
            alert("Email already in use. PLease Log-in to continue.");
        }
		
    else 
        {
		$hash = md5( rand(0,1000) );
                    $update=$dbconfig->prepare("INSERT INTO user_login (email,hash,password,name,mobile,enrollment_number) VALUES(?,?,?,?,?,?)");
					$update->bind_param("ssssss",$email,$hash,$password,$name,$mobile,$enrollment_number);
					$update->execute();
					$update->close();
		$to      = $email; // Send email to our user
$subject = 'Signup | Verification'; // Give the email a subject 
$message = '
 
Thanks for signing up!
Your account has been created, you can login after you have activated your account by pressing the url below.
 
Please click this link to activate your account:
http://www.quizmaker.000webhost.com/verify.php?email='.$email.'&hash='.$hash.'
 
'; // Our message above including the link
                     
$headers = 'From:noreply@helloworld.com' . "\r\n"; // Set from headers
mail($to, $subject, $message, $headers); // Send our email
	                alert("A verification email has been sent to you E-Mail address. Please verify your E-Mail for your account to be activated.");
		}
	}
	else if($_POST['submit']=='login')
	{
		$email=mysqli_real_escape_string($dbconfig,$_POST['email']);
$password=mysqli_real_escape_string($dbconfig,$_POST['password']);
	$password=crypt($password, '$2a$07$CCSCodersUnderSiegelul$');
$sql_query=$dbconfig->prepare("SELECT  userid,email,name,activated FROM user_login WHERE email=? and password=?");
$sql_query->bind_param("ss",$email,$password);
$sql_query->execute();
// If result matched $username and $password, table row must be 1 row
$result=$sql_query->get_result();
if($result->num_rows===1)
{
	$row=$result->fetch_assoc();
	if($row['activated']==0)
	alert("Account Not Activated. Please activate your account through the activation link sent on your email.");
	else{
$_SESSION['email']=$row['email'];
$_SESSION['name']=$row['name'];
$_SESSION['admin']=0;
$_SESSION['user']=1;
$_SESSION['uid']=$row['userid'];		
header("location:redirect.php");	
}}
else
{
alert("Wrong Credentials.");
}
}
	}
            
        
if(isset($_SESSION['acti']))
{
	alert("Account Successfully Activated. Please Log-In to continue.");
	unset($_SESSION['acti']);
}?>
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
	<nav class="navbar navbar-dark bg-dark text-white">
  <a class="navbar-brand">HelloWorld</a>
  <div class="navbar-nav">
      <a class="nav-item nav-link active" href="admin.php">Admin Login<span class="sr-only">(current)</span></a>
    </div>
</nav>
<br><br><br>
	<div class="row "><h1 class="col-sm-12 display-4 text-center">User Portal</h1></div><br>

	<div class="row ">
		<div class="col-sm-2"></div>
  <div class="col-sm-4">
    <div class="card">
      <div class="card-body">
		  <h5 class="card-title">Login</h5>
        <form action="index.php" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="password">
  </div>
  
  <button type="submit" class="btn btn-primary" name="submit" value="login">Login</button>
</form>
      </div>
    </div>
  </div>
		
  <div class="col-sm-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Sign-Up</h5>
		 <form method="post" action="index.php">
  <div class="form-group">
    <label for="exampleInputEmail1">Name</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Name" name="name" required>
  </div><div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" name="email" required>
  </div>
			 <div class="form-group">
    <label for="exampleInputEmail1">Enrollment Number</label>
    <input type="text" class="form-control" id="enumber" aria-describedby="emailHelp" placeholder="Enrollment Number" onKeyUp="check2();" name="enumber" required>
  </div> <span id="message2"></span>
			 <div class="form-group">
    <label for="exampleInputEmail1">Mobile Number</label>
    <input type="text" class="form-control" id="number" aria-describedby="emailHelp" placeholder="Mobile Number" onKeyUp="check();" name="number" required>
  </div>
	 <span id='message'></span>		
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
  </div>
		<div class="form-group">	 <label for="exampleInputPassword1">Confirm Password</label>
    <input type="password" class="form-control" id="confirm_password" onKeyUp="check1();" placeholder="Re-Enter Password" required>
  </div>
			 <span id='message1'></span><br>
  <button type="submit" class="btn btn-primary" value="su" name="submit">Sign-Up</button>
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
</form> 
      </div>
    </div>
  </div><div class="col-sm-2"
</div>
</body>
</html>
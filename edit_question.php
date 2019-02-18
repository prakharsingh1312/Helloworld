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
elseif($_SERVER['REQUEST_METHOD']=="POST" && $_POST['submit']=="update")
{
	$question=mysqli_real_escape_string($dbconfig,$_POST['question']);
	$type=mysqli_real_escape_string($dbconfig,$_POST['type']);
	$answer=mysqli_real_escape_string($dbconfig,$_POST['answer']);
	if($type==0)
	{
	$op1=mysqli_real_escape_string($dbconfig,$_POST['option1']);
		$op2=mysqli_real_escape_string($dbconfig,$_POST['option2']);
		$op3=mysqli_real_escape_string($dbconfig,$_POST['option3']);
		
	}
	if(isset($_POST['tie']))
		$tie=1;
	else
		$tie=0;
	$points=mysqli_real_escape_string($dbconfig,$_POST['points']);
	if($type==0)
	{
	$query=$dbconfig->prepare("UPDATE `admin_{$_SESSION['id']}_{$_SESSION['cid']}_pre` SET question=? , answer=? , choice1=? , choice2=? , choice3=? , points=? , text = ? , tie=? WHERE qid=?");
	$query->bind_param("sssssiiii",$question,$answer,$op1,$op2,$op3,$points,$type,$tie,$_SESSION['eq']);
	$query->execute();
	alert("Question Updated Successfully.");
	}
	else
	{
	$query=$dbconfig->prepare("UPDATE `admin_{$_SESSION['id']}_{$_SESSION['cid']}_pre` SET question=? , answer=? , points=? , text = ? , tie=? WHERE qid=?");
	$query->bind_param("ssiiii",$question,$answer,$points,$type,$tie,$_SESSION['eq']);
	$query->execute();
	alert("Question Updated Successfully.");
	}
	
}
if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['submit']!="update")
{
	$_SESSION['eq']=$_POST['submit'];
}
$query=$dbconfig->prepare("SELECT * FROM admin_{$_SESSION['id']}_{$_SESSION['cid']}_pre where qid=?");
$query->bind_param("i",$_SESSION['eq']);
$query->execute();
$query=$query->get_result();
$row=$query->fetch_assoc();
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
	<div class="col-sm-1"></div><h1 class="display-4">Edit Question</h1></div>
	<br>
	<form class="form" action="edit_question.php" method="post">
	<div class="row"><div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder"><label for="exampleInputEmail1">Question:</label></div><div class="col-sm-6"><textarea class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Question" name='question'  rows="3" required><?php echo $row['question'];?></textarea><small class="form-text text-muted">Input will be formatted in html (please use escape sequences for symbols)</small></div></div>
		<br>
<div class="row"><div class="col-sm-1"></div>
			<div class="col-sm-3 font-weight-bolder"><label for="exampleInputEmail1">Type:</label></div><div class="col-sm-6"><div class="form-check">
          <input class="form-check-input" type="radio" name="type" id="gridRadios1" value="1" <?php if($row['text']==1)
	echo 'checked';?>>
          <label class="form-check-label" for="gridRadios1">
            Short Answer
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="type" id="gridRadios2" value="0"<?php if($row['text']==0)
	echo 'checked';?>>
          <label class="form-check-label" for="gridRadios2">
            Multiple Choice
          </label>
        </div></div></div><br>

		<div class="row">
		<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder">Answer:</div><div class="col-sm-6"><input type="text" class="form-control" id="inputEmail3" placeholder="Answer" name="answer" value='<?php echo $row['answer'];?>'></div></div><br>

		<div class="row">
		<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder">Option 1:</div><div class="col-sm-6"><input type="text" class="form-control" id="inputEmail3" placeholder="Option 1" name="option1" value="<?php echo $row['choice1'];?>"></div></div><br>

		<div class="row">
		<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder">Option 2:</div><div class="col-sm-6"><input type="text" class="form-control" id="inputEmail3" placeholder="Option 2" name="option2" value="<?php echo $row['choice2'];?>"></div></div><br>

		<div class="row">
		<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder">Option 3:</div><div class="col-sm-6"><input type="text" class="form-control" id="inputEmail3" placeholder="Option 3" name="option3" value="<?php echo $row['choice3'];?>"></div></div><br>

		<div class="row">
		<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder">Points:</div><div class="col-sm-6"><input type="text" class="form-control" id="inputEmail3" placeholder="Points" name="points" value="<?php echo $row['points'];?>"></div></div><br>
		<div class="row">
		<div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder"></div><div class="col-sm-6"><div class="form-check">
        <input class="form-check-input" type="checkbox" id="gridCheck1" name="tie" value="1"<?php if($row['tie']==1)
	echo 'checked';?> >
        <label class="form-check-label" for="gridCheck1">
          Tie Breaker?
        </label>
      </div><br>
		<div class="row">
		<div class="col-sm-1 ml-auto"><button type="submit" class="btn btn-primary" name="submit" value="update">Update</button></div>
      </div>
			
 </div></div>
	</form>
</body>
</html>
<?php
session_start();
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

include("Assets/php/dbconfig.php");
if(!isset($_SESSION['email'])||$_SESSION['user']!=1)
{
	

header("location:index.php");
}
if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['submit']!='ic')
{
		$_SESSION['cid']=$_POST['submit'];
		$query=mysqli_query($dbconfig,"SELECT * from all_contests where contestid={$_SESSION['cid']}");
		$result=mysqli_fetch_array($query,MYSQLI_ASSOC);
		$_SESSION['id']=$result['userid'];
}
$query=mysqli_query($dbconfig,"SELECT * from all_contests where contestid={$_SESSION['cid']}");
		$result=mysqli_fetch_array($query,MYSQLI_ASSOC);
$query1=mysqli_query($dbconfig,"SELECT * from admin_{$_SESSION['id']}_{$_SESSION['cid']}_res where userid={$_SESSION['uid']}");
		$count1=mysqli_num_rows($query1);
if($_SERVER['REQUEST_METHOD']=="POST" && $_POST['submit']=='ic')
{
	if($result['ic']==$_POST['sec'])
	{
		$_SESSION['ic']=1;
		header("location:pre.php");
	}
	else{
		alert("Wrong Secret Key!!");
	}
}
$etime=date("d M Y H:i:s",strtotime($result['end_time']));?>

<head>
<meta charset="utf-8">
<title>Creative Computing Society</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="Assets/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="Assets/css/clock.css">
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
}
var deadline = Date.parse(<?php echo '"'.$etime.'"';?>)

var st = srvTime();
    var t = (deadline-Date.parse(new Date(st)))/1000;
    var downloadTimer = setInterval(function(){
    t--;
	var seconds = Math.floor( (t) % 60 );
	var minutes = Math.floor( (t/60) % 60 );
	var hours = Math.floor( (t/(60*60)) % 24 );
		document.getElementById("hours").textContent=('0'+hours).slice(-2);
		document.getElementById("minutes").textContent=('0'+minutes).slice(-2);
		document.getElementById("seconds").textContent=('0'+seconds).slice(-2);
    if(t <= 0)
{
        clearInterval(downloadTimer);
	document.finalSub.submit();
}
    },1000);
	

</script>
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
		   /*if($_SESSION['user']==1)
		   { echo '
      <li class="nav-item">
        <a class="nav-link" href="attempted_contests.php">Attempted Contests</a>
      </li>';}*/
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
	
<br>
<br>
<br>
	
<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-9"><h1 class="display-4"><?php echo $result['name']; ?></h1></div>
	<?php
	if(isset($_SESSION['ic']))
	{
		echo'<div class="sub_navbartimer "><div id="clockdiv" style="
    zoom: 0.5;
		  -moz-transform: scale(0.5);"><span id="tl">Time Left :</span>
	<div><span class="hours" id="hours">00</span><div class="smalltext">Hours</div></div>
	<div><span class="minutes" id="minutes">00</span><div class="smalltext">Minutes</div></div>
	<div><span class="seconds" id="seconds">00</span><div class="smalltext">Seconds</div></div>
	</div></div>';
	}
	?></div>
	<br>
	<?php
	if(!isset($_SESSION['ic']) && $count1==0)
	{
		echo '
<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-8 form-text  text-muted">'.$result['des'].'</div></div>
<div class="row"><div class="col-sm-1"></div><div class="col-sm-3 font-weight-bolder">Enter Secret Key :</div><div class="col-sm-6"><form class="form-inline" method="post" action"play.php"><input type="text" class="form-control" name="sec"><button type="submit" class="btn btn-dark" name="submit" value="ic">Start</button> </form></div></div>';}
	elseif(!isset($_SESSION['ic']) && $count1!=0)
	{
		echo '
<div class="row">
	<div class="col-sm-1"></div><div class="col-sm-8 form-text  text-muted">You have already attempted the contest.</div></div>';}
	else{
		echo'<form class="form" method="post" action="check.php" name="finalSub"><div class="accordion" id="accordionExample">';
		for( $i=1;$i<=$_SESSION['numq'];$i++)
		{
			echo '<div class="row"><div class="col-sm-1"></div><div class="col-sm-6"><div class="card">
    <div class="card-header" id="heading'.$i.'">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse'.$i.'" aria-expanded="true" aria-controls="collapse'.$i.'">
         Question '.$i.'<label class="text-muted">('.$_SESSION['points'][$i].'Points)</label> 
        </button>
      </h2>
    </div>
	<div id="collapse'.$i.'" class="collapse" aria-labelledby="heading'.$i.'" data-parent="#accordionExample">
      <div class="card-body">
       <div class="form-group">
    <label for="exampleInputEmail1"> '.$_SESSION['q'][$i].'</label>';
			if($_SESSION['text'][$i]==1)
				echo'<input type="text" class="form-control" name="'.$i.'" placeholer="Answer">
      ';
			else
			{for($j=0;$j<4;$j++)
				echo '<div class="form-check">
  <input class="form-check-input" type="radio" name="'.$i.'" id="'.$i.$j.'" value="'.$_SESSION['choices'][$i][$j].'">
  <label class="form-check-label" for="'.$i.$j.'">
    '.$_SESSION['choices'][$i][$j].'
  </label>
</div>';}
			echo'
  </div>
    </div>
  </div></div></div></div><br>
  ';
		}
		echo'<div class="row"><div class="col-sm-1"></div><div class="col-sm-11"><button type="submit" class="btn btn-primary" name="check">Submit Test</button></div></div></form>';
	}?>
</body>

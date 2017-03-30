<?php
include("dbconnect.php");
include("console_lib.php");
session_start();
if(!isset($_SESSION['erroCount'])){
		$_SESSION['erroCount']=0;
}
if(isset($_GET['plateNum'])){
	$_SESSION['plateNum']=$_GET['plateNum'];
	
}

if(isset($_GET['entrance'])){
	echo "Entrance to the first floor has been provided<br/>";
	
	unset($_SESSION['erroCount']);
	unset($_SESSION['plateNum']);
	exit;
	
}
else if(isset($_GET['confEnter'])){
		echo"
		<h3>Please Enter the 10-digit Confirmation Number</h3>
		<form action='walkin.php' method = 'post'>
		<input type='text' name='confNum' maxlength=10>
		<input type = 'submit' value = 'Enter'>
		</form>";
	}
else if(isset($_POST['confNum'])){
	$confNum  = $_POST['confNum'];
	$reserve= mysqli_fetch_assoc(mysqli_query($dbconnect, "SELECT * FROM reservations WHERE confirmNum=".$confNum));
	$currentTime = getCurrentTime();
	

	if(isReserveAvaliable($reserve,$currentTime)){
		header("Location: registered.php?reserve_id=".$reserve['reserve_id']."&plateNum=".$_SESSION['plateNum']);	
	}
	else{
		$_SESSION['erroCount']=$_SESSION['erroCount']+1;
		if($_SESSION['erroCount']>=3){
			echo "Too many attempts<br/>
			Entrance to the first floor has been provided<br/>";
			unset($_SESSION['erroCount']);
			unset($_SESSION['plateNum']);
			exit;
		}
		echo "Incorrect Confirmation Number or Reservation Not avaliable<br/>
		<form action='walkin.php' >
		<input type = 'submit' name='entrance' value = 'I am a walk-in user'>
		</form>
		<form action='walkin.php'>
		<input type = 'submit' name ='confEnter' value = 'Re-enter confirmation Number'>
		</form>";
	}
}
else{
?>
<h1>WELCOME</h1>
<form action='walkin.php' >
<input type = "submit" name='entrance' value = "I am a walk-in user">
</form>
<form action='walkin.php'>
<input type = "submit" name ='confEnter' value = "I have a confirmation Number">
</form>

<?php
}
?>
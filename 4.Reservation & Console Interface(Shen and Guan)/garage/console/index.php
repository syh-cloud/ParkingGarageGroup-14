


<?php
	include("dbconnect.php");
	include("console_lib.php");
	//first identify the role of incoming user
	
	if(isset($_GET['plateNum'])){
		$plateNum = $_GET['plateNum'];
		$user_sql = "SELECT user_id FROM users WHERE plateNum='".$plateNum."'";
		$user_query = mysqli_query($dbconnect,$user_sql);
		$user = mysqli_fetch_assoc($user_query);
		if($user['user_id']){//if user is found via plateNum
			header("Location: registered.php?user_id=".$user['user_id']);
	
		}
		else{
			header("Location: walkin.php?plateNum=".$plateNum);
		}
		//$reserve_sql = "SELECT spot_id FROM spots JOIN reservations WHERE plateNum=".$GET['plateNum'];
		//$reserve = mysqli_fetch_assoc(mysqli_query($dbconnect,$user_sql));
	}
	else{
		echo "plateNum not provided";
		exit;
	}
?>

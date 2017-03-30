<?php
include("dbconnect.php");
include("console_lib.php");



$currentTime = roundToNearestHalfHour(getCurrentTime());

if(isset($_GET['user_id'])){
	//first find whether there is avaliable reservation
	$user= mysqli_fetch_assoc(mysqli_query($dbconnect,
	"SELECT * FROM users WHERE user_id=".$_GET['user_id']));
	

	
	$reserve = mysqli_fetch_assoc(mysqli_query($dbconnect,
	"SELECT * FROM reservations WHERE user_id='".$_GET['user_id']."' ORDER BY beginTime"));
	

	
	if(isReserveAvaliable($reserve,$currentTime)){
		//found avaliable reservation;
		$spot = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM spots WHERE spot_id=".$reserve['spot_id']));
		if($spot['state']){//if spot not avaliable
			$spot = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM spots WHERE futurestate1&1 = 0 AND state = 0 "));
			
			activateReservation($reserve,$spot,$currentTime,$user['plateNum'],$dbconnect);
			
			echo"Sorry, your registered spot is currently unavaliable.<br/>
			Your spot number is".$spot['spotNum']."<br/>Entrance to floor ".(int)($spot['spotNum']/1000)." is provided.";
		}
		else{//if spot avaliable
			
			activateReservation($reserve,$spot,$currentTime,$user['plateNum'],$dbconnect);
			echo"Your spot number is".$spot['spotNum']."<br/>Entrance to floor ".(int)($spot['spotNum']/1000)." is provided.";
			
		}

		exit;
	}
	else{//user has no avaliable reservation
		echo"No avaliable reservations found<br/>";
		$spot = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM spots WHERE futurestate1&1 = 0 AND state = 0 "));
		$beginTime = date("y-m-d H:i:s",strtotime($currentTime."+30 minute"));
		$endTime = date("y-m-d H:i:s",strtotime($beginTime."+3 hour"));
		
		newEntrance($user,$beginTime,$endTime,$dbconnect);
		echo "after new Entrance function<br/>";
		upDateSpots($spot,$currentTime,$beginTime,$endTime,$dbconnect);
	
		echo"Your spot number is".$spot['spotNum']."<br/>Entrance to floor ".(int)($spot['spotNum']/1000)." is provided.";
		unset($_SESSION['plateNum']);
		exit;
	}
}
else if(isset($_GET['reserve_id'])){
		


		$reserve = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM reservations WHERE reserve_id=".$_GET['reserve_id']));
		$user = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM users WHERE user_id=".$reserve['user_id']));
		if(isset($_GET['plateNum'])){
			$user['plateNum']=$_GET['plateNum'];
		}
		
		$spot = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM spots WHERE spot_id=".$reserve['spot_id']));
		
		if($spot['state']){//if spot not avaliable
			$spot = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM spots WHERE futurestate1&1 = 0 AND state = 0 "));
			
			activateReservation($reserve,$spot,$currentTime,$user['plateNum'],$dbconnect);
			
			echo"Sorry, your registered spot is currently unavaliable.<br/>
			Your spot number is".$spot['spotNum']."<br/>Entrance to floor ".(int)($spot['spotNum']/1000)." is provided.";
		}
		else{//if spot avaliable
			
			activateReservation($reserve,$spot,$currentTime,$user['plateNum'],$dbconnect);
			echo"Your spot number is".$spot['spotNum']."<br/>Entrance to floor ".(int)($spot['spotNum']/1000)." is provided.";
			
		}
		exit;
	
}
else{
	echo"ERROR: unknow user";
	exit;
	
}
?>
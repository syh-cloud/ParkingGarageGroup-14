<?php
function getCurrentTime($offset=0){
	$minute = $offset * 30;
	return date("y-m-d H:i:s",strtotime(date("y-m-d H:i:s")."+".$minute."minute"));
}
function newEntrance($user,$beginTime,$endTime,$dbconnect){
	$sql = "INSERT INTO parkingusers (beginTime,endTime,plateNum,user_id)
	VALUES ('".$beginTime."','".$endTime."','".$user['plateNum']."',".$user['user_id'].")";
	echo $sql."<br/>";
	if(mysqli_query($dbconnect,$sql)){
		echo "user entered<br/>";
	}
	else{
		echo "entrance creation failed";
		
	}
	
}
function activateReservation($reserve,$spot,$currentTime,$plateNum,$dbconnect){
	$user['user_id']=$reserve['user_id'];
	$user['plateNum']=$plateNum;
	
	newEntrance($user,$reserve['beginTime'],$reserve['endTime'],$dbconnect);
	
	$beginTime=date("y-m-d H:i:s",strtotime($currentTime.'+30 minute'));
	upDateSpots($spot,$currentTime,$beginTime,$reserve['endTime'],$dbconnect);
	//delete old reservation record
	mysqli_query($dbconnect,"DELETE FROM reservations
	WHERE reserve_id=".$reserve['reserve_id']);
	echo "old reservation deleted<br/>";
}
function roundToNearestHalfHour($d){
		$date = date_create($d);
		$datestring = date_format($date,"y-m-d");
		$minute = date_format($date,"i");
		$hour = date_format($date,"H");
		if($minute<15){
			return $datestring." ".$hour.":00:00";
		}
		else if($minute>45){
			if($hour==23)//if hour is 23, needs to add one day
			{
				return date("y-m-d H:i:s",strtotime($datestring.'+1 day'));
				
			}
			else{
				$hour++;
				return $datestring." ".$hour.":00:00";
			}
		}
		else{
			return $datestring." ".$hour.":30:00";			
		}
	}	

function upDateSpots($spot,$currentTime,$beginTime,$endTime,$dbconnect){//upDateSpots is similar with checking availability
	$currentTime=strtotime($currentTime);
	$beginTime = strtotime($beginTime);
	$endTime = strtotime($endTime);
	$p1 = ($beginTime-$currentTime)/1800;
	$p2 = ($endTime-$currentTime)/1800;
	if($p1>32){//if both larger than 32, check second future state only
		$p1 = $p1-32;
		$p2 = $p2-32;
		$checkBits = ((1<<$p2)-1)&(1<<($p1-1));
		
		$spot['futurestate2'] = $spot['futurestate2']|$checkBits;
		
	}
	
	else if($p2>32){//if only p2 larger than 32, needs to check second future state
		$checkBits1 = (0-1)<<($p1-1);
		$checkBits2 = (1<<($p2-31)-1);
		$spot['futurestate1'] = $spot['futurestate1']|$checkBits1;
		$spot['futurestate2'] = $spot['futurestate2']|$checkBits2;
		
	}
	else{//check futurestate1 only
		$checkBits = ((1<<$p2)-1)&(1<<($p1-1));
		$spot['futurestate1'] = $spot['futurestate1']|$checkBits;	
	}
	
	mysqli_query($dbconnect,"UPDATE spots 
	SET futurestate1 = ".$spot['futurestate1'].",
	futurestate2 = ".$spot['futurestate2']."
	WHERE spot_id = '".$spot['spot_id']."';");
}

function isReserveAvaliable($reserve,$currentTime){
	echo "Reservation_id is ".$reserve['reserve_id']."<br/>CurrentTime is ".$currentTime."<br/>Reservation Time is".$reserve['beginTime']."<br/>";
	if($reserve['reserve_id']==NULL){
	return false;
	}
	//check reservation time 
	//the arriving time could be half-hour before reservation or anytime before reservation ends

	$beginTime = $reserve['beginTime'];
	$endTime = $reserve['endTime'];
	$timeDiff1 = (int)(strtotime($currentTime)-strtotime($beginTime))/60;
	$timeDiff2 = (int)(strtotime($endTime)-strtotime($currentTime))/60;
	if($timeDiff1< -30 || $timeDiff2 < 0){
		return false;
	}
	return true;

}

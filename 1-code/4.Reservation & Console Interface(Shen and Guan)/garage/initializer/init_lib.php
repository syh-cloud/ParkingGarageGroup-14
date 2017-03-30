<?php
function getCurrentTime($offset=0){
	return date("y-m-d H:i:s");
}

function plateGenerator(){
	$p = "";
	for($i = 0;$i<6;$i++){
		if(rand(0,1)){
		
			$p=$p.chr(rand(65,90));
		}
		else{
			$p=$p.rand(0,9);
			
		}
	}
	return $p;
}

function nameGenerator(){
	$s = chr(rand(65,90));
	$l = rand(3,10);
	for($i=0;$i<$l;$i++){
		$s=$s.chr(rand(97,122));		
	}
	return $s;
}

function passWordGenerator(){
	$s = "";
	$l = rand(8,10);
	for($i=0;$i<$l;$i++){
		$s=$s.chr(rand(40,126));		
	}
	return $s;
}

function roundToNearestHalfHour($date){
		$date = date_create($date);
		$datestring = date_format($date,"y-m-d");
		$minute = date_format($date,"i");
		$hour = date_format($date,"H");
		if($minute<=30){
			return $datestring." ".$hour.":30:00";			
		}
		else {
			if($hour==23)//if hour is 23, needs to add one day
			{
				return date("y-m-d H:i:s",strtotime($date.'+1 day'));
				
			}
			else{
				$hour++;
				return $datestring." ".$hour.":00:00";
			}
		}

	}

function addRandTime($time,$low,$high){//randomly add period 
	$ti = rand($low,$high);
	$time = $time.'+'.((int)($ti/2)).'hour';
	$time = $time.'+'.($ti%2*30).'minute';
	$newtime = date("y-m-d H:i:s",strtotime($time));
	return $newtime;
	}

function spotGenerator($floorSize,$spotSize,$dbconnect){
	for($i=2;$i<=$floorSize+2;$i++){
		for($j=1;$j<=$spotSize;$j++){
			$spotNum = $i*1000+$j;
			mysqli_query($dbconnect,"INSERT INTO spots (spotNum) VALUES (".$spotNum.");");
		}
	}
	
}
function randomUserGenerator($userSize,$dbconnect){
	for($i=0;$i<$userSize;$i++){
		mysqli_query($dbconnect,"INSERT INTO users(username,plateNUM,password)
		VALUES('".nameGenerator()."','".plateGenerator()."','".passWordGenerator()."');");
	}
}	
function randomReserveGenerator($time,$spotsSize,$userSize,$dbconnect){
	
	$reservationSize = 0.7*$userSize;//assuming 70% of registered users currently have reservation
	for($i=0;$i<$reservationSize;$i++){
		$beginTime = addRandTime($time,1,20);
		$endTime = addRandTime($beginTime,2,30);
		$spot = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM spots WHERE spot_id=".rand(1,$spotsSize).";"));
		
		$trial=0;
		while(!checkSpotAvaliability($spot,$time,$beginTime,$endTime)&& ($trial<100) ){
			$spot = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT * FROM spots WHERE spot_id=".rand(1,$spotsSize).";"));
			$trial++;
		}
		if($trial>=100){
			echo "no random spot available";
			return;
		}
		//will get the available spot after loop
		//start making reservation
		$confNum = (int)(1000000000+time()%(3600*24*7)*1000+(int)(microtime()*1000));
		//confNum is generated as a conbination of Unix time and Unix microtime that garantees no repitition in a week
		
		mysqli_query($dbconnect,"INSERT INTO reservations (beginTime,confirmNum,endTime,spot_id,user_id)
		VALUES ('".$beginTime."',".$confNum.",'".$endTime."',".$spot['spot_id'].",".rand(1,$userSize)." );");
		//then update the futurestate of the spots database
		upDateSpots($spot,$time,$beginTime,$endTime,$dbconnect);
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

function checkSpotAvaliability($spot,$currentTime,$beginTime,$endTime){//all time needs to be rounded
	
	$currentTime=strtotime($currentTime);
	$beginTime = strtotime($beginTime);
	$endTime = strtotime($endTime);
	$p1 = ($beginTime-$currentTime)/1800;
	$p2 = ($endTime-$currentTime)/1800;
	if($p1>32){//if both larger than 32, check second future state only
		$p1 = $p1-32;
		$p2 = $p2-32;
		$checkBits = ((1<<$p2)-1)&(1<<($p1-1));
		if($checkBits&$spot['futurestate2']){//if result is 0, then available
			return false;
		}
		return true;			
	}
	
	else if($p2>32){//if only p2 larger than 32, needs to check second future state
		$checkBits1 = (0-1)<<($p1-1);
		$checkBits2 = (1<<($p2-31)-1);
		if( ($checkBits1&$spot['futurestate1'])|| ($checkBits2&$spot['futurestate2']) ){
			return false;
		}
		return true;
	}
	else{//check futurestate1 only
		$checkBits = ((1<<$p2)-1)&(1<<($p1-1));
		if($checkBits&$spot['futurestate1']){//if result is 0, then available
			return false;
		}
		return true;			
	}
}

function tableGenerator($dbconnect){
	mysqli_query($dbconnect,
	 "CREATE TABLE users(
		user_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(user_id),
		username VARCHAR(10) NOT NULL UNIQUE,
		plateNum CHAR(6) UNIQUE,
		password VARCHAR(10) NOT NULL,
		role VARCHAR(10) NOT NULL DEFAULT 'user',
		email VARCHAR,
		phoneNum VARCHAR,
		billing INT DEFAULT 0
	)");
	mysqli_query($dbconnect,
	"CREATE TABLE spots(
		spot_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(spot_id),
		spotNum INT(4) NOT NULL,
		state BOOLEAN DEFAULT 0,
		futurestate1 INT DEFAULT 0,
		futurestate2 INT DEFAULT 0
	)");
	mysqli_query($dbconnect,
	"CREATE TABLE reservations(
		reserve_id INT NOT NULL AUTO_INCREMENT,
		user_id INT NOT NULL,
		confirmNum INT(10),
		spot_id INT(3) NOT NULL,
		PRIMARY KEY (reserve_id), 
		FOREIGN KEY (user_id) REFERENCES users(user_id), 
		FOREIGN KEY (spot_id) REFERENCES spots(spot_id),
		beginTime DATETIME NOT NULL,
		endTime DATETIME NOT NULL
	) ENGINE=INNODB;");
	mysqli_query($dbconnect,
	"CREATE TABLE parkingUsers(
		plateNum CHAR(6)NOT NULL UNIQUE,
		PRIMARY KEY(user_id),
		user_id INT NOT NULL,
		FOREIGN KEY (user_id) REFERENCES users(user_id),
		beginTime DATETIME NOT NULL,
		endTime DATETIME NOT NULL
	)");

	
}
?>
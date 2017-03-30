<?php
include("init_lib.php");
$password = "";
$dbconnect = mysqli_connect("localhost","root",$password,"garage");
$userSize=60;//define amount of registered users
$floorSize = 3;//define how many floors for registered users
$spotSize=40;//define amount of spots in each floor

$time = roundToNearestHalfHour(date("y-m-d H:i:s"));


if(true){
tableGenerator($dbconnect);
echo"Tables Generated";
spotGenerator($floorSize,$spotSize,$dbconnect);
echo"Spots Initialized";
randomUserGenerator($userSize,$dbconnect);
echo "Users Initialized";
randomReserveGenerator($time,($spotSize*$floorSize),$userSize,$dbconnect);
echo "reserevations Initialized";
}

?>


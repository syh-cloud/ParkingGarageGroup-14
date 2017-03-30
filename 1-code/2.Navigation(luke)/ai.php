<?php
	include 'connection.php';
	$con=10;
	$od = '2017-03-08 09:00:00';
	$d = date ("Y-m-d H:i:s", strtotime($od));
	$query1= "SELECT COUNT(*) as weight FROM reservations WHERE spot_id<$con";
	$query2= "SELECT COUNT(*) as weight FROM reservations WHERE spot_id<(2*$con)AND spot_id>=$con AND (beginTime == $d OR endTime == $d)";
	$query3= "SELECT COUNT(*) as weight FROM reservations WHERE spot_id<(3*$con)AND spot_id>=(2*$con)";// AND ($d==beginTime OR $d==endTime)";
	$query4= "SELECT COUNT(*) as weight FROM reservations WHERE spot_id<(4*$con)AND spot_id>=(3*$con)";// AND ($d==beginTime OR $d==endTime)";
	$query5= "SELECT COUNT(*) as weight FROM reservations WHERE spot_id<(5*$con)AND spot_id>=(4*$con)";// AND ($d==beginTime OR $d==endTime)";
	$query6= "SELECT COUNT(*) as weight FROM reservations WHERE spot_id<(6*$con)AND spot_id>=(5*$con)";// AND ($d==beginTime OR $d==endTime)";
	
	
	$response1 = @mysqli_query($conn, $query1);
	$response2 = @mysqli_query($conn, $query2);
	$response3 = @mysqli_query($conn, $query3);
	$response4 = @mysqli_query($conn, $query4);
	$response5 = @mysqli_query($conn, $query5);
	$response6 = @mysqli_query($conn, $query6);


	if($response1)
	{
		$num1=mysqli_fetch_assoc($response1);
		echo $num1['weight'].'<br />';
	}
	if($response2)
	{
		$num2=mysqli_fetch_assoc($response2);
		echo $num2['weight'].'<br />';
	}
	if($response3)
	{
		$num3=mysqli_fetch_assoc($response3);
		echo $num3['weight'].'<br />';
	}
	if($response4)
	{
		$num4=mysqli_fetch_assoc($response4);
		echo $num4['weight'].'<br />';
	}
	if($response5)
	{
		$num5=mysqli_fetch_assoc($response5);
		echo $num5['weight'].'<br />';
	}
	if($response6)
	{
		$num6=mysqli_fetch_assoc($response6);
		echo $num6['weight'].'<br />';
	}
	
	$weight12=$num1['weight']+$num2['weight'];
	$weight15=$num1['weight']+$num5['weight'];
	$weight23=$num2['weight']+$num3['weight'];
	$weight24=$num2['weight']+$num4['weight'];
	$weight34=$num3['weight']+$num4['weight'];
	$weight45=$num4['weight']+$num5['weight'];
	$weight56=$num5['weight']+$num6['weight'];
	
	echo $weight12;
?>
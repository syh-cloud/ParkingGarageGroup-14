if(isset($_GET['spot_id'])&&isset($_SESSION['beginTime'])&&isset($_SESSION['endTime'])){
	$confNum = (int)(1000000000+time()%(3600*24*7)*1000+(int)(microtime()*1000));
	$f= mysqli_query($dbconnect,"INSERT INTO reservations (beginTime,confirmNum,endTime,spot_id,user_id)
		VALUES ('".$_SESSION['beginTime']."',".$confNum.",'".$_SESSION['endTime']."',".$_GET['spot_id'].",".$_SESSION['user_id']." );");
	if($f){
		
		echo "Reservation success";
		
	}
	else{
		echo "Reservation failed";
		
	}
}	  
else 
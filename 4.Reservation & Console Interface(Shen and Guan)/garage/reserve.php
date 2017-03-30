<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Superlot</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

 <body>
 <?php
 function checkSpotAvaliability($spot,$currentTime,$beginTime,$endTime){//all time needs to be rounded
	
	$currentTime=strtotime($currentTime);
	$beginTime = strtotime($beginTime);
	$endTime = strtotime($endTime);
	$p1 = (int)(($beginTime-$currentTime)/1800);
	$p2 = (int)(($endTime-$currentTime)/1800);
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
 function roundToNearestHalfHour($d){
		if($d==NULL)return NULL;
		
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
  session_start();
  include('../garage/connect/dbconnect.php');
  	  
  if(!isset($_SESSION['role'])){
	  header('Location:../garage/index.php');
  }
	
  ?>
 <div class="container">
      <!-- Main component for a primary marketing message or call to action -->
	  <?php
	  include('../garage/navbar.php');
	  ?>
	<div class="jumbotron">
	
	  <?php
	  include("timePicker.php");
if(isset($_GET['spot_id'])&&isset($_SESSION['beginTime'])&&isset($_SESSION['endTime'])){
	$confNum = (int)(1000000000+time()%(3600*24*7)*1000+(int)(microtime()*1000));
	$f= mysqli_query($dbconnect,"INSERT INTO reservations (beginTime,confirmNum,endTime,spot_id,user_id)
		VALUES ('".$_SESSION['beginTime']."',".$confNum.",'".$_SESSION['endTime']."',".$_GET['spot_id'].",".$_SESSION['user_id']." );");
	if($f){
		
		echo "<h4>Reservation success<br/> Your confirmation number is ".$confNum."</h4>";
		
	}
	else{
		echo "<h4>Reservation failed</h4>";
		
	}
}	  
else if(isset($_POST['beginTime'])&&isset($_POST['endTime'])){
		 $beginTime = roundToNearestHalfHour($_POST['beginTime']);
		 $endTime = roundToNearestHalfHour($_POST['endTime']);
		 $currentTime = roundToNearestHalfHour( date('y-m-d H:i:s'));
	 if(($_POST['beginTime']==NULL) ||($_POST['endTime']==NULL)){ 
		echo "<h2>Input error</h2>";
	 }
	 else if((strtotime($endTime)-strtotime($beginTime))<7200){
		echo "Reservation duration not acceptible";
	 }
	 else{
		 $size = mysqli_fetch_assoc(mysqli_query($dbconnect,"SELECT Count(*) as size FROM spots "))['size'];
		 if((strtotime($beginTime)-strtotime($currentTime))<1800){
			 $beginTime=date("y-m-d H:i:s",strtotime($currentTime."+30 minute"));	 
		 }
		 $_SESSION['beginTime']=$beginTime;
		 $_SESSION['endTime']=$endTime;
		 $rowSize =10;
		 $columSize = $size/10;
	  
		 $spot = mysqli_query($dbconnect,"SELECT * FROM spots ");
?>
	<Table>
	  <?php
	  for($i=0;$i<$rowSize;$i++){
		?>
		<tr><?php		
		  for($j=0;$j<$columSize;$j++){
		?>
		<td>
			  <a style='min-height:30px;background-color:<?php 
			  if(checkSpotAvaliability(mysqli_fetch_assoc($spot),$currentTime,$beginTime,$endTime)){
				  echo"green' href='../garage/reserve.php?spot_id=30";
			  }  
			  else
			  {echo "red";
			  } 
			  ?>'class='btn'></button>
			 
			
		</td><?php		  
		  }
		  
		?>
		</tr><?php
	  }
		  
	  ?>
	  </Table>
	 <?php
	 	 }
}
	 ?>
      </div>
</div> <!-- /container -->

	<!--
    <div class="container">
     Example row of columns
      <div class="row">
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
       </div>
        <div class="col-md-4">
          <h2>Heading</h2>
          <p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
          <p><a class="btn btn-default" href="#" role="button">View details &raquo;</a></p>
        </div>
      </div>

      

      <footer>
        <p>&copy; 2016 Company, Inc.</p>
      </footer>
    </div> <!-- /container -->

 
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>

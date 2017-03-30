<?php
	$dbconnect = mysqli_connect("localhost","root","1234","garage");
	if(mysqli_connect_errno()){
	echo"Connect failed:".mysqli_connect_error();
	exit;
}
?>
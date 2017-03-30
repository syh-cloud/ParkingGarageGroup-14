<?php

include 'dbh.php';
$email=$_POST['email'];
$password=$_POST['psw'];
$sql="SELECT * FROM user_acount WHERE email='$email'AND password='$password'";
$result=$mysqli->query($sql);
if(!$row=mysqli_fetch_assoc($result)){
    echo "Your email address or password is incorrect!";
}
else{
   header("location:homepage.php");
}

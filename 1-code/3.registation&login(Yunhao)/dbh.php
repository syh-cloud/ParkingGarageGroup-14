<?php
$mysqli=mysqli_connect("localhost","root","root","account");
if(!$mysqli){
    die("Connection failed:".mysqli_connect_error);
}

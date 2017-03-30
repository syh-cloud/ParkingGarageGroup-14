<?php
session_start();
$_SESSION['message']='';
include 'dbh.php';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['password'] == $_POST['confirmpassword']){
        $firstname=$mysqli->real_escape_string($_POST['firstname']);
        $lastname=$mysqli->real_escape_string($_POST['lastname']);
        $email=$mysqli->real_escape_string($_POST['email']);
        $password=$_POST['password'];
        $license=$mysqli->real_escape_string($_POST['license']);
        $phone=$_POST['phone'];
        $conn="SELECT * FROM user_acount WHERE email='$email'";
        $result=$mysqli->query($conn);
        if(!$row=mysqli_fetch_assoc($result)){
            $sql="INSERT INTO user_acount(first,last,password,email,license,phone)
            VALUES('$firstname','$lastname','$password','$email','$license','$phone')";
            if($mysqli->query($sql)===true){
                $_SESSION['message']="Registration successful!";
                header("location:homepage.php");
            }
             else{
            $_SESSION['message']="Sorry, the registration is failed!";
        } 
    }
        else{
            $_SESSION['message']="The email address has been used!";
        }
    }
        else{
            $_SESSION['message']="Two password does not match!";
        }
        
}
?>
<link href="//db.onlinewebfonts.com/c/a4e256ed67403c6ad5d43937ed48a77b?family=Core+Sans+N+W01+35+Light" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="account.css" type="text/css">
<div class="body-content">
  <div class="module">
    <h1>Create an account</h1>
    <form class="form" action="account.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="alert alert-error"><?=$_SESSION['message']?></div>
      <input type="text" placeholder="First Name" name="firstname" required />
      <input type="text" placeholder="Last Name" name="lastname" required />
      <input type="email" placeholder="Email" name="email" required />
      <input type="password" placeholder="Password" name="password" autocomplete="new-password" required />
      <input type="password" placeholder="Confirm Password" name="confirmpassword" autocomplete="new-password" required />
      <input type="text" placeholder="Licnese" name="license" required />
      <input type="tel" placeholder="Phone" name="phone" required />
      <input type="submit" value="Register" name="register" class="btn btn-block btn-primary" />
    </form>
  </div>
</div>

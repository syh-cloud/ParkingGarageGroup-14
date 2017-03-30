
<?php
ini_set("SMTP", "ssl:smtp.gmail.com");
ini_set("smtp_port", "465");
$to       = '7325568496@tmomail.net';
$subject  = 'Reservation Confirmation';
$message  = 'You have made a reservation for Tuesday March 28, 2017 for 12:30pm to 1:30pm';
$headers  = 'From: superlot14@gmail.com' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=utf-8';
if(mail($to, $subject, $message, $headers))
    echo "Email sent";
else
    echo "Email sending failed";
?>

<!DOCTYPE html>
<head><br>Enter your number<br></head>
<body>
<form>
<input type = "text" name = "number"><br>
<input type = "submit" name = "Submit" >

</form>

</body>
</html>

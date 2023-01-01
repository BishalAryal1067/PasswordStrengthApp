<?php
 session_start();

 unset($_SESSION['username']);
 unset($_SESSION['email']);
 unset($_SESSION['logged_in']);
 unset($_SESSION['verification_code']);

 header("Location:login.php");

?>
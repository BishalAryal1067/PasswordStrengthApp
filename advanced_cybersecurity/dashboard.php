<?php
include('operations.php');

if(isset($_POST['change-password'])){
    redirect("change_password.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--external CSS-->
    <link rel="stylesheet" href="./css/dashboard.css">
    <title>Dashboard</title>
</head>

<body>
    <nav class="navbar">
        <p>Dashboard</p>
        <span>
            <input type="button" value="Logout" name="logout_btn" onclick="window.location.href='logout.php'" id="logout-btn">
        </span>
    </nav>

    <h2>Welcome !</h2>
    <div class="container">
        <span>
            <p>Username:</p>
            <p><?php echo $_SESSION['username'];?></p>
        </span>
        <span>
            <p>Status:</p>
            <p style="color:green">Active</p>
        </span>
        <form action="" method="post" style="width:100%;">
            <input type="submit" value="Change Password" name="change-password">
        </form>
       
    </div>

    <div style="margin-top:1.75em ;">
        <?php echo checkPasswordExpiration($_SESSION['username']) ?>
    </div>

</body>

</html>
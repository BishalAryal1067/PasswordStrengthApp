<?php
include('operations.php');

// if(empty($_SESSION['logged-in']) || $_SESSION['logged_in'] == ''){
//     redirect('login.php');
// }

if (isset($_POST['proceed_btn'])) {
    $verification_code = $_POST['verification_code'];
    redirect('dashboard.php');
} else {
    $message = "<p style='position:absolute; left:50%; transform:translateX(-50%);'>Wrong verification code !</p>";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/confirm_login.css">
    <title>Login Verification</title>
</head>

<body>
    <div class="container">
        <form action="" method="post">
            <p>Check your email for confirmation code</p>
            <input type="text" placeholder="Confirmation Code" name="verification_code" autocomplete="off">
            <input type="submit" value="Proceed" name="proceed_btn">
        </form>
    </div>
</body>

</html>
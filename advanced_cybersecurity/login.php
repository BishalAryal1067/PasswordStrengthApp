<?php

include('operations.php');

if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $user_password = $_POST['user_password'];

    //array for storing error during validation
    $error = [
        'empty_fields' => '',
        'wrong_data' => '',
    ];

    //checking for errors
    if ($email == '' || $user_password == '') {
        $error['empty_fields'] = "
        <p style='position:absolute; left:50%; transform:translateX(-50%);'>No field can be empty !";
    }
  
    else if(!user_login($email, $user_password)){
        $error['wrong_data'] = "Username or password is wrong !";        
    }


    foreach ($error as $key => $value) {
        if (empty($value)) {
            unset($error[$key]);
        }
    }
    //registering user when there is no error
    if (empty($error)) {
        user_login($email, $user_password);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <div class="error-text">
        <p class><?php echo isset($error['empty_fields']) ? $error['empty_fields'] : '' ?></p>

    </div>
    <div class="error-text">
        <p class><?php echo isset($error['wrong_data']) ? $error['wrong_data'] : '' ?></p>
    </div>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!--fontawesome icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--css file-->
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <form action="" method="post" class="login-form">
        <p class="heading">Log In</p>
        <div class="container">
            <div class="input-block">
                <input type="text" name="email" placeholder="Email / Username" id="email" autocomplete="off">
            </div>
            <div class="input-block">
                <input type="password" name="user_password" placeholder="Password" id="password" autocomplete="off">
                <i class="fa-solid fa-eye" id="view-password"></i>
            </div>
            <input type="submit" value="Log In" name="login_btn">
        </div>
        <a href="register.php">Create an account</a>
        <i class="fa-solid fa-right-to-bracket" id="login-icon"></i>
    </form>


    <script src="./js/login.js"></script>
</body>

</html>
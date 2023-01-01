<?php

include('operations.php');

if (isset($_POST['register_btn'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $user_password = $_POST['user_password'];
    $confirm_password = $_POST['confirm_password'];

    //array for storing error during validation
    $error = [
        'empty_fields' => '',
        'different_passwords' => '',
        'captcha_input' => '',
        'weak_password' => '',
        'user_exists' => ''
    ];

    //regex for checking password strength
    $strongRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/";

    // if(!preg_match($strongRegex))

    //checking for errors
    if ($full_name == '' || $username == '' || $user_password == '' || $confirm_password == '') {
        $error['empty_fields'] = " <div style='position:absolute; right: 3.5%; margin: 5% 0; display: flex; align-items: center;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold;'>No field can be empty</p>
    </div>";
        echo "{$error['empty_fields']}";
    } else if ($user_password != '' and $confirm_password != '') {
        if (!($user_password == $confirm_password)) {
            $error['different_passwords'] = " <div style='position:absolute; right: 3.5%; margin:7.5% 0; display: flex; align-items: center;'>
            <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
            <p style='color:red; font-weight:bold;'>Passwords don't match</p>
        </div>";
            echo "{$error['different_passwords']}";
        } else if (!preg_match($strongRegex, $user_password)) {
            $error['weak_password'] = " <div style='position:absolute; right: 3.5%; margin: 7.5% 0; display: flex; align-items: center;'>
            <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
            <p style='color:red; font-weight:bold;'>Password is not strong enough</p>
        </div>";
            echo "{$error['weak_password']}";
        }
    }
    if (empty($_POST['g-recaptcha-response'])) {
        $error['captcha_input'] = " <div style='position:absolute; right: 3.5%; margin: 7.5% 0; display: flex; align-items: center;'>
            <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
            <p style='color:red; font-weight:bold;'>Please tick the captcha box</p>
        </div>";
        echo "{$error['captcha_input']}";
    }

    $emailExists = check_email($email);
    if ($emailExists) {
        $error['user_exists'] = " <div style='position:absolute; right: 3.5%; margin: 10% 0; display: flex; align-items: center;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold;'>User with this email already exists</p>
    </div>";
        echo "{$error['user_exists']}";
    }

    $usernameExists = check_email($username);
    if ($emailExists) {
        $error['user_exists'] = " <div style='position:absolute; right: 3.5%; margin: 12% 0; display: flex; align-items: center;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold;'>User with this username already exists</p>
    </div>";
        echo "{$error['user_exists']}";
    }


    foreach ($error as $key => $value) { 
        if (empty($value)) {
            unset($error[$key]);
        }
    }
    //registering user when there is no error
    if (empty($error)) {
        register_user($full_name, $email, $username, $user_password);
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!--fontawesome icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--css file-->
    <link rel="stylesheet" href="./css/register.css">
</head>

<body>

    <!-- displaying error-->

    <!-- <div class="error-text">
        <p class><?php echo isset($error['empty_fields']) ? $error['empty_fields'] : '' ?></p>
    </div>
    <div class="error-text">
        <p><?php echo isset($error['different_passwords']) ? $error['different_passwords'] : '' ?></p>
    </div>
    <div class="error-text">
        <p><?php echo isset($error['captcha_input']) ? $error['captcha_input'] : '' ?></p>
    </div> -->

    <!--Registration -->
    <form action="" method="POST" class="register-form">
        <p class="heading">Create an account</p>
        <div class="container">
            <div class="input-block">
                <input type="text" name="full_name" placeholder="Full Name" id="full-name" autocomplete="off">
            </div>
            <div class="input-block">
                <input type="email" name="email" placeholder="Email" id="email" autocomplete="off">
            </div>
            <div class="input-block">
                <input type="text" name="username" placeholder="Username" id="username" autocomplete="off">
            </div>
            <div class="input-block">-
                <input type="password" name="user_password" placeholder="Password" id="password" autocomplete="off">
                <i class="fa-solid fa-eye" id="view-password"></i>
            </div>
            <div class="input-block">
                <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm-password" autocomplete="off">
                <i class="fa-solid fa-eye" id="view-confirm-password"></i>
            </div>
            <!--captcha-->
            <dv class="g-recaptcha" data-sitekey="6LcpWI0gAAAAAJBqx8-71x91oefgyAhMMiGZCBGq" style="margin-bottom: .5em;"></div>
            <input type="submit" name="register_btn" value="Register">
        </div>
        <a href="login.php">Login with an account</a>
        <i class="fa-solid fa-circle-plus" id="add-icon"></i>
        <!--Password Requirements-->
        <div class="password-requirements">
            <p>Your password must contain:</p>
            <span>
                <i class="fa-solid fa-circle-check" id="character-count"></i>
                <p>at least 8 characters</p>
            </span>
            <span>
                <i class="fa-solid fa-circle-check" id="character-case"></i>
                <p>both uppercase and lowercase</p>
            </span>
            <span>
                <i class="fa-solid fa-circle-check" id="character-number"></i>
                <p>number</p>
            </span>
            <span>
                <i class="fa-solid fa-circle-check" id="character-special"></i>
                <p>special character</p>
            </span>

            <div class="password-strength">
                <div class="strength-bar"></div>
                <p class="strength-message"></p>
            </div>
        </div>
    </form>
    <!--javascript-->
    <script src="./js/register.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer>
    </script>
</body>

</html>
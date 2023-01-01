<?php
include('operations.php');

//regex for checking password strength
$strongRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/";

if (isset($_POST['change_btn'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];

    $empty_error = [
        'current_password' => '',
        'wrong_current_password' => '',
        'new_password' => '',
        'password_strength' => '',
        'confirm_password' => '',
        

    ];

    if ($current_password == "") {
        $error['current_password'] = " <div style='position:absolute; right: -50%; margin: 5% 0; display: flex; align-items: center; width:100%;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold; width:auto;'>Please enter your current password</p>
    </div>";
    }

    if ($current_password == $new_password) {
        if($new_password != ''){
            $error['new_password'] = " <div style='position:absolute; right: 3.5%; margin: 5% 0; display: flex; align-items: center;'>
            <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
            <p style='color:red; font-weight:bold;'>New Password can't be same as old password</p>
        </div>";
        }
        else{
            $error['empty_fields'] = " <div style='position:absolute; right: 3.5%; margin: 5% 0; display: flex; align-items: center;'>
            <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
            <p style='color:red; font-weight:bold;'>Please enter a new password</p>
        </div>";
        }
    }

    if(!checkUsedPasswords($username, $new_password)){
        $error['new_password'] = " <div style='position:absolute; left: 50%; transform:translateX(-40%); margin: 5% 0; display: flex; align-items: center; width:500px;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold;'>Password was used before! Try a new one</p>
    </div>";
    }

    if($confirm_password == ''){
        $error['empty_fields'] = " <div style='position:absolute; right: 3.5%; margin: 5% 0; display: flex; align-items: center;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold;'>Confirm Password can't be left empty</p>
    </div>";
    }

    if ($new_password != $confirm_password) {
        $error['confirm_password'] = " <div style='position:absolute; right: 3.5%; margin: 5% 0; display: flex; align-items: center;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold;'>Password's dont match</p>
    </div>";
    }

    if (!checkCurrentPassword($email, $current_password)) {
        $error['wrong_current_password'] = " <div style='position:absolute; right: 3.5%; margin: 5% 0; display: flex; align-items: center;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold;'>Wrong Password</p>
    </div>";
    }

    else if (!preg_match($strongRegex, $new_password)) {
        $error['new_password'] = " <div style='position:absolute; right: 3.5%; margin: 5% 0; display: flex; align-items: center;'>
        <i class='fa-solid fa-triangle-exclamation' style='color: red; margin-right:.5em;'></i>
        <p style='color:red; font-weight:bold;'>Please select a new password</p>
    </div>";
    }

    foreach ($error as $key => $value) {
        if (empty($value)) {
            unset($error[$key]);
        } else if (changePassword($email, $new_password)) {
            recordPasswords($email);
            $message = "<p style='color:green'>Password Successfully changed ! </p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/change_password.css">
    <!--fontawesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Change Password</title>
</head>

<body>
    <div class="error-container" style="  position:absolute;
    left:50%; top:2.5%; 
    transform:translateX(-50%); 
    display:flex;
    flex-direction: column;
    align-items: flex-start;">
       <p style="color:red; padding:0.25em .25em; margin:0.01em"><?php echo isset($error['current_password']) ? $error['current_password'] : '' ?></p>
       <p style="color:red; padding:0.5em .25em;"><?php echo isset($error['confirm_password']) ? $error['confirm_password'] : '' ?></p>
       <p style="color:red; padding:0.5em .25em;"><?php echo isset($error['new_password']) ? $error['new_password'] : '' ?></p>
       <p style="color:red; padding:0.5em .25em;"><?php echo isset($error['password_strength']) ? $error['password_strength'] : '' ?></p>
        
        
    </div>


    <div class="container">
        <div style="width: 100%; display:flex; justify-content:center">
            <p>
                <?php if (isset($_SESSION['password-expiration'])) {
                    echo $_SESSION['password-expiration'];
                    unset($_SESSION['password-expiration']);
                }
                ?>
            </p>
        </div>
        <form action="" method="POST" class="login-form">
            <p class="heading">Change Password</p>
            <div class="container">
                <div class="input-block">
                    <input type="password" name="current_password" placeholder="Current Password" id="password" autocomplete="off">
                    <i class="fa-solid fa-eye" id="view-current-password"></i>
                </div>
                <div class="input-block">
                    <input type="password" name="new_password" placeholder="New Password" id="password" autocomplete="off">
                    <i class="fa-solid fa-eye" id="view-new-password"></i>
                </div>
                <div class="input-block">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" id="password" autocomplete="off">
                    <i class="fa-solid fa-eye" id="view-confirm-password"></i>
                </div>
                <input type="submit" value="Change Password" name="change_btn">
            </div>
        </form>
    </div>

    <script>

let togglePassword = () => {
    let password = document.querySelector('#current_password');
    let type = password.getAttribute('type');
    if (type == 'password') {
        password.setAttribute('type', 'text');
        viewPasswordBtn.style.color = 'rgb(2, 157, 204)';
    }
    else if (type == 'text') {
        password.setAttribute('type', 'password');
        viewPasswordBtn.style.color = 'rgb(78, 78, 78)';
    }
}

let toggleConfirmPassword = () => {
    let confirmPassword = document.querySelector('#confirm-password');
    let type = confirmPassword.getAttribute('type');
    if (type == 'password') {
        confirmPassword.setAttribute('type', 'text');
        viewConfirmPasswordBtn.style.color = 'rgb(2, 157, 204)';
    }
    else if (type == 'text') {
        confirmPassword.setAttribute('type', 'password');
        viewConfirmPasswordBtn.style.color = 'rgb(78, 78, 78)';
    }
}
    </script>

</body>

</html>
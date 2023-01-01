    <?php



    include('db_connection.php');

    //starting session
    session_start();

    //function for confirmation of query execution
    function confirm_Query($result)
    {
        global $db_connection;
        if (!$result) {
            die('Query Failed' . mysqli_error($db_connection));
        }
    }

    //operation for user registration
    function register_user($full_name, $email, $username, $user_password)
    {
        global $db_connection;
        $full_name = mysqli_real_escape_string($db_connection, trim($full_name));
        $email = mysqli_real_escape_string($db_connection, trim($email));
        $username = mysqli_real_escape_string($db_connection, trim($username));
        $user_password = mysqli_real_escape_string($db_connection, trim($user_password));

        $auth_status = 0;
        date_default_timezone_set("Asia/Kathmandu");
        $date = date("Y-m-d");
        $auth_token = md5($email . $full_name . $username);

        //performing hashing on password
        $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 10));

        //executing statments
        $stmt = mysqli_prepare($db_connection, "INSERT INTO user_information(full_name, email, username, user_password, auth_token, auth_status, date) VALUES(?, ?, ?, ?,?,?,?)");
        mysqli_stmt_bind_param($stmt, 'sssssss', $full_name, $email, $username, $user_password, $auth_token, $auth_status, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        //sending mail for verification when user registration is attempted
        if ($stmt) {
            $_SESSION['email'] = $email;
            $_SESSION['logged_in'] = "logged_in";
            $subject = "Email Verification";
            $from = "noreply@acs_auth.com";
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            $message = "
        <html>
        <head>
        <title>Email Verification</title>
        </head>
        <body>
        <h2>Dear $full_name,</h2>
        <p>Thank you for requesting user registration. Please click below to complete registration.</p>
        <center><a href='http://localhost/advanced_cybersecurity/verification_completion.php?auth_token=$auth_token&email=$email' 
        style='padding:0 0.75em; background-color:blue; color:white'>Verify</a><center>
        </body>
        </html>";

            mail($email, $subject, $message, $headers);

            echo '<script type="text/javascript"> window.open("verification.php", "_blank")</script>';
        }
    }

    //login function
    function user_login($email, $user_password)
    {
        //running query to check for email and authentication status
        global $db_connection;
        $email = mysqli_real_escape_string($db_connection, trim($email));
        $user_password = mysqli_real_escape_string($db_connection, trim($user_password));
        $query = "SELECT * FROM user_information WHERE email = '{$email}' AND auth_status=1";
        $select_user = mysqli_query($db_connection, $query);
        confirm_Query($select_user);

       //comparing password and logging in
        while ($row = mysqli_fetch_array($select_user)) {
            $db_username = $row['username'];
            $db_user_email = $row['email'];
            $db_user_password = $row['user_password'];
            if (password_verify($user_password, $db_user_password)) {
                $_SESSION['username'] = $db_username;
                $_SESSION['email'] = $db_user_email;
                $_SESSION['logged_in'] = "logged_in";
                sendVerificationCode($db_user_email);
                redirect("login_verification.php");
                return true;
            } else {
                return false;
            }
        }
    }

    //redirect function
    function redirect($location)
    {
        return header("Location: " . $location);
        exit;
    }

    function getAuthToken($email, $auth_token)
    {
        global $db_connection;

        $email = mysqli_real_escape_string($db_connection, $email);
        $auth_token = mysqli_real_escape_string($db_connection, $auth_token);

        $query = "UPDATE user_information SET auth_token='0', auth_status = 1 WHERE email='{$email}' AND auth_token = '{$auth_token}'";
        $result = mysqli_query($db_connection, $query);
        confirm_Query($result);
        if (!$result) {
            return false;
        }
        recordPasswords($email);
        return true;
    }

    //saving passwords is separate table
    function recordPasswords($email)
    {
        global $db_connection;
        $query = "SELECT * FROM user_information WHERE email = '{$email}'";
        $select_user = mysqli_query($db_connection, $query);

        while ($row = mysqli_fetch_array($select_user)) {
            $db_username = $row['username'];
            $db_user_password = $row['user_password'];
            $stmt = mysqli_prepare($db_connection, "INSERT INTO password_tbl(username, password) VALUES (?,?)");
            mysqli_stmt_bind_param($stmt, 'ss', $db_username, $db_user_password);
            mysqli_stmt_execute($stmt);
            confirm_Query($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    //checking password expiration time
    function days_difference($registration_date, $current_date)
    {
        $registration_date = strtotime($registration_date);
        $datediff = $current_date - $registration_date;
        return round($datediff / (60 * 60 * 24));
    }

    //password expiration
    function checkPasswordExpiration($username)
    {
        global $db_connection;
        $current_date = time();
        $username = mysqli_real_escape_string($db_connection, $username);
        $select_query = "SELECT date FROM user_information where username='$username'";
        $query_result = mysqli_query($db_connection, $select_query);

        while ($row = mysqli_fetch_array($query_result)) {
            $registration_date = $row['date'];

            $count = 60 - days_difference($registration_date, $current_date);

            if ($count > 0 && $count <= 60) {
                echo "Last password created on: {$registration_date}\r\n <br>";
                echo "Password expires in: {$count} day/s.";
            } else {
                redirect("change_password.php");
                $_SESSION['password-expiration'] = "Password has expired! Please change your paasword";
            }
        }
    }

    //function to send verification code 
    function sendVerificationCode($email)
    {
        $verification_code = rand(100000, 999999);
        $_SESSION['verification_code'] = $verification_code;
        $_SESSION['email'] = $email;

        $subject = 'Two Factor Authentication';
        $from = 'noreply@acs.com';
        $headers = 'MIME-Version: 1.0' . "\r\n";

        $headers = 'From: ' . $from . "\r\n" . 
        'Reply-To:' . $from . "\r\n" . 
        'X-Mailer: PHP/' . phpversion();
        $message = "
        Use this verification code for login confirmation: {$verification_code}
    ";

        mail($email, $subject, $message, $headers);
    }

    //change password
    function changePassword($email, $newPassword)
    {
        global $db_connection;
        $email = mysqli_real_escape_string($db_connection, $email);
        $password = mysqli_real_escape_string($db_connection, $newPassword);
        date_default_timezone_set("Asia/Kathmandu");
        $date = date("Y/m/d");
        $hashed_password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));
        $query = "UPDATE user_information SET user_password = '{$hashed_password}', date = '{$date}' WHERE email = '{$email}'";
        $result = mysqli_query($db_connection, $query);
        confirm_Query($result);
        if (!$result) {
            return false;
            redirect('dashboard.php');
        }
        return true;
    }

    //checking old password
    function checkCurrentPassword($email, $current_password)
    {
        global $db_connection;
        $email = mysqli_real_escape_string($db_connection, $email);
        $current_password = mysqli_real_escape_string($db_connection, $current_password);
        $query = "SELECT user_password FROM user_information WHERE email = 'email'";
        $query_result = mysqli_query($db_connection, $query);

        while ($row = mysqli_fetch_array($query_result)) {
            $db_user_password = $row('user_password');
            if (password_verify($current_password, $db_user_password)) {
                return true;
            } else {
                return false;
            }
        }
    }

    //checking if password was already used before
    function checkUsedPasswords($username, $password){
        global $db_connection;
        $query = "SELECT * FROM password_tbl WHERE username = '$username'";
        $result = mysqli_query($db_connection, $query);
        confirm_Query($result);
        while ($row = mysqli_fetch_array ($result)) {
            $row_password = $row['password'];
                if(password_verify($password, $row_password)){
                    return false;
                }
        }
        return true;
    }

    //function to check if user exists
    function check_email($email)
    {
        global $db_connection;
        $query = "SELECT email FROM user_information WHERE email ='$email'";
        $result = mysqli_query($db_connection, $query);
        confirm_Query($result);
        $row = mysqli_num_rows($result);
        if ($row > 0) {
            return true;
        } else {
            return false;
        }
    }

    //function to check if username already exists
    function check_username($username)
    {
        global $db_connection;
        $query = "SELECT username FROM user_information WHERE username ='$username'";
        $result = mysqli_query($db_connection, $query);
        confirm_Query($result);
        $row = mysqli_num_rows($result);
        if ($row > 0) {
            return true;
        } else {
            return false;
        }
    }

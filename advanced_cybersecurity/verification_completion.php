<?
include ('operations.php');

if (isset($_POST["proceed"])) {
    redirect("login.php");
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Registration</title>
</head>

<body>
    <?php
    include('operations.php');
    if (isset($_GET['auth_token']) && isset($_GET['email'])) {
        $auth_token = $_GET['auth_token'];
        $email = $_GET['email'];
        if (getAuthToken($email, $auth_token)) {
            echo "
             <div style='position:absolute;
              top:50%;
               left:50%;
             transform:translate(-50%,-50%);
             disply:flex;
             flex-direction:column;
             align-items:center;
             '>
              <p> Account activated.. please proceed to login</p>
              <form method='POST' style='width:auto; action='login.php'>
                 <a href='login.php' style='width:auto; height:auto; padding:.5em; text-decoration:none; background:blue; color:white;'>Proceed</a>
            </form>
              </div>
            ";
        } else {
            echo "<p>Account Activation Failed! </p>";
        }
    }
    ?>
</body>

</html>
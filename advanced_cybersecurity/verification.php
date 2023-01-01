<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
</head>
<body>
    <div class="container" style="display: flex; flex-direction: column; justify-content:center; align-items:center; width:100%; height:100vh">
        <h3>Just one step away !</h3>
        <p>Check your email <?php echo isset($_SESSION['email'])? $_SESSION['email']: ''?> to verify user registration</p>
    </div>
</body>
</html>
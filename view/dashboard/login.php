<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../../css/style_admin.css">
    <script src="../js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta charset="UTF-8">
</head>

<body class="login">>
<div class="ring">
        <i style="--clr:#19266b;"></i>
        <i style="--clr:#ffffff;"></i>
        <i style="--clr:#ffd000;"></i>
        <div class="login">
            <h2>Login</h2>
            <form action="../services/login.php" method="POST">
                <div class="inputBx">
                    <input type="text" name="username" placeholder="Username">
                </div>
                <br>
                <div class="inputBx">
                    <input type="password" name="password" placeholder="Password">
                </div>
                <br>
                <div class="inputBx">
                    <input type="submit" value="Sign in">
                </div>
                <br>
                <div class="links">
                    <a href="#">Forget Password</a>
                    <a href="#">Signup</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

<?php 
?>
</html>

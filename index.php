<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Non hai un account? <a href="register.php">Registrati</a></p>
    </div>
</body>
</html>

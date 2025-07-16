
<?php
// register.php - Pagina di registrazione utente
require_once 'db.php';
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $error = "Username già in uso.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Errore durante la registrazione.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-box">
        <h2>Registrati</h2>
        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Crea Account</button>
        </form>
        <p>Hai già un account? <a href="index.php">Login</a></p>
    </div>
</body>
</html>
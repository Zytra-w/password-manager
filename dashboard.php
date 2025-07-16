<?php
// dashboard.php - Gestione password dell'utente
session_start();
require_once 'db.php';
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $site = $_POST['site'];
        $username = $_POST['site_user'];
        $password = $_POST['site_pass'];
        $stmt = $conn->prepare("INSERT INTO passwords (user_id, site, site_user, site_pass) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $site, $username, $password);
        $stmt->execute();
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];
        $stmt = $conn->prepare("DELETE FROM passwords WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
    }
    if (isset($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $site = $_POST['edit_site'];
        $username = $_POST['edit_user'];
        $password = $_POST['edit_pass'];
        $stmt = $conn->prepare("UPDATE passwords SET site=?, site_user=?, site_pass=? WHERE id=? AND user_id=?");
        $stmt->bind_param("sssii", $site, $username, $password, $id, $user_id);
        $stmt->execute();
    }
    header("Location: dashboard.php");
    exit();
}
$stmt = $conn->prepare("SELECT id, site, site_user, site_pass FROM passwords WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function openPasswordGenerator() {
            document.getElementById('popup').style.display = 'block';
        }
        function closePasswordGenerator() {
            document.getElementById('popup').style.display = 'none';
        }
        function generatePassword() {
            const length = document.getElementById('length').value;
            const upper = document.getElementById('upper').checked;
            const lower = document.getElementById('lower').checked;
            const numbers = document.getElementById('numbers').checked;
            const special = document.getElementById('special').checked;
            let charset = '';
            if (upper) charset += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            if (lower) charset += 'abcdefghijklmnopqrstuvwxyz';
            if (numbers) charset += '0123456789';
            if (special) charset += '!@#$%^&*()_+{}[]';
            let password = '';
            for (let i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            document.getElementById('site_pass').value = password;
            closePasswordGenerator();
        }
    </script>
</head>
<body>
    <h2>Le tue password</h2>
    <a href="logout.php" style="position:absolute; top:20px; right:20px;">Logout</a>
    <form method="post">
        <input type="text" name="site" placeholder="Sito" required>
        <input type="text" name="site_user" placeholder="Username" required>
        <input type="text" name="site_pass" id="site_pass" placeholder="Password" required>
        <button type="button" onclick="openPasswordGenerator()">Genera Password</button>
        <button type="submit" name="add">Aggiungi</button>
    </form>
    <table>
        <tr><th>Sito</th><th>Username</th><th>Password</th><th>Azioni</th></tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><input name="edit_site" value="<?= $row['site'] ?>"></td>
                <td><input name="edit_user" value="<?= $row['site_user'] ?>"></td>
                <td><input name="edit_pass" value="<?= $row['site_pass'] ?>"></td>
                <td>
                    <button type="submit" name="edit_id" value="<?= $row['id'] ?>">Modifica</button>
                    <button type="submit" name="delete" value="<?= $row['id'] ?>">Elimina</button>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>

    <div id="popup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePasswordGenerator()">&times;</span>
            <h3>Generatore Password</h3>
            <label>Lunghezza: <input type="number" id="length" value="12" min="4" max="32"></label><br>
            <label><input type="checkbox" id="upper" checked> Lettere Maiuscole</label><br>
            <label><input type="checkbox" id="lower" checked> Lettere Minuscole</label><br>
            <label><input type="checkbox" id="numbers" checked> Numeri</label><br>
            <label><input type="checkbox" id="special" checked> Caratteri Speciali</label><br>
            <button onclick="generatePassword()">Genera</button>
        </div>
    </div>
</body>
</html>

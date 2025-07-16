<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'password_manager';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>

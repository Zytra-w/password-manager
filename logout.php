<?php
session_start();
session_destroy(); // Termina la sessione
header("Location: index.php"); // Torna alla pagina di login
exit();

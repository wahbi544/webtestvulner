<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require 'config.php';
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Vulnerable App</title></head>
<body>
<h1>Vulnerable App — Sök produkter</h1>

<form method="get" action="index.php">
    <input type="text" name="q" placeholder="Sök produkt">
    <button type="submit">Sök</button>
</form>

<?php
if (!empty($_GET['q'])) {
    $q = $_GET['q'];
    // Medvetet osäkert: direkt interpolering i SQL (möjlig SQL injection)
    $sql = "SELECT * FROM products WHERE name LIKE '%$q%'";
    $res = $mysqli->query($sql);
    if ($res) {
        echo "<ul>";
        while ($row = $res->fetch_assoc()) {
            // Medvetet osäkert: ingen HTML-escaping => XSS möjlig
            echo "<li><a href='view.php?id={$row['id']}'>{$row['name']}</a> - {$row['price']} SEK</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>Fel vid fråga.</p>";
    }
}
?>

<p><a href="login.php">Logga in</a> | <a href="register.php">Registrera</a></p>
</body>
</html>


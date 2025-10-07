<?php
// config.php - medvetet enkel / osäker konfiguration för labb
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'W@Hbi1967b2'; // sätt ditt root-lösen eller skapa en labb-användare (se setup nedan)
$db_name = 'vuln_app';

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
    die("Connect failed: " . $mysqli->connect_error);
}
?>


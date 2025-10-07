<?php
require 'config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';

    // Ingen validering eller hashning => avsiktligt osäkert
    $stmt = "INSERT INTO users (username, password, email) VALUES ('$user', '$pass', '$email')";
    if ($mysqli->query($stmt)) {
        $msg = "Registrering lyckades!";
    } else {
        $msg = "Fel: " . $mysqli->error;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Registrera</title></head><body>
<h1>Registrera</h1>
<?php echo $msg ? "<p>$msg</p>" : ""; ?>
<form method="post" action="register.php">
    <input name="username" required placeholder="Användarnamn"><br>
    <input name="password" required type="password" placeholder="Lösenord"><br>
    <input name="email" placeholder="E-post"><br>
    <button type="submit">Skapa konto</button>
</form>
<p><a href="index.php">Hem</a></p>
</body></html>


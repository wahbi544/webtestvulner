<?php
require 'config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $mysqli->real_escape_string($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    // Här använder vi fortfarande klartextlösen -> sårbart
    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass' LIMIT 1";
    $res = $mysqli->query($sql);
    if ($res && $res->num_rows === 1) {
        // Inget sessionshanteringssäkerhet -> avsiktligt simpelt
        $row = $res->fetch_assoc();
        $msg = "Inloggad som " . $row['username'];
    } else {
        $msg = "Felaktiga uppgifter.";
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Login</title></head><body>
<h1>Logga in</h1>
<?php echo $msg ? "<p>$msg</p>" : ""; ?>
<form method="post" action="login.php">
    <input name="username" required placeholder="Användarnamn"><br>
    <input name="password" required type="password" placeholder="Lösenord"><br>
    <button type="submit">Logga in</button>
</form>
<p><a href="index.php">Hem</a></p>
</body></html>


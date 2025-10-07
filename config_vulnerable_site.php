<?php
// اتصال بقاعدة البيانات
$servername = "localhost";
$username = "root";
$password = "WAHbi1967bb2";
$dbname = "vulnerable_site";

$conn = new mysqli($servername, $username, $password, $dbname);

// صفحة دخول vulnerable
if ($_POST['username']) {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    
    // SQL Injection vulnerable
    $sql = "SELECT * FROM users WHERE username = '$user' AND password = '$pass'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "Login successful!";
    } else {
        echo "Login failed!";
    }
}
?>

<form method="post">
Username: <input type="text" name="username"><br>
Password: <input type="password" name="password"><br>
<input type="submit" value="Login">
</form>

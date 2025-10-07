<?php
include 'index.php'; // inkluderar huvudfilen

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (name, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $username, $email, $password]);

    echo "Signup successful! <a href='sign.php'>Login here</a>";
    exit;
}
?>

<form method="post">
    Name: <input type="text" name="name" required><br>
    Username: <input type="text" name="username" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Sign Up</button>
</form>

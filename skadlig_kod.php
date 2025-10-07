<?php
// ✅✅✅ EXTREMT SÅRBAR KOD - BARA FÖR LABBEN ✅✅✅

// Hög Risk: SQL Injection
// Anslut till DB (skapa en databas först)
$conn = new mysqli("localhost", "root", "password", "test_db");

// Ta emot input direkt från användaren utan validering - DÅLIGT!
$user_id = $_GET['id'];

// Bygg en query direkt med användarinput - FARLIGT!
$sql = "SELECT * FROM users WHERE id = " . $user_id; 
$result = $conn->query($sql);

// Medel Risk: Reflected XSS
$search_query = $_GET['search'];
echo "<p>Du sökte på: " . $search_query . "</p>"; // Input skrivs direkt ut!

// Hög Risk: Command Injection
$ip = $_GET['ip'];
system("ping -c 4 " . $ip); // Användardata skickas direkt till systemkommandot!

// Hög Risk: File Include
$page = $_GET['page'];
include($page . '.php'); // Användare kan inkludera godtyckliga filer!

?>
<!DOCTYPE html>
<html>
<body>
    <h1>Farlig Inloggning</h1>
    <!-- Låg Risk: CSRF (Cross-Site Request Forgery) -->
    <!-- Formuläret har ingen CSRF-token -->
    <form action="change_email.php" method="POST">
        Ny E-post: <input type="text" name="email">
        <input type="submit" value="Byt E-post">
    </form>
</body>
</html>

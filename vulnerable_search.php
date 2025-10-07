sudo tee /var/www/html/social_app/vulnerable_search.php > /dev/null <<'PHP'
<?php
// vulnerable_search.php
// Simple SQL injection demo (lab only)
include 'index.php'; // förutsätter att index.php initierar $db PDO

if (isset($_GET['q'])) {
    $q = $_GET['q'];
    $sql = "SELECT id, name, username, email FROM users WHERE username LIKE '%$q%'";
    $rows = $db->query($sql);
    echo "<h3>Search results for: " . htmlspecialchars($q) . "</h3>";
    echo "<ul>";
    foreach ($rows as $r) {
        echo "<li>" . htmlspecialchars($r['username']) . " (" . htmlspecialchars($r['email']) . ")</li>";
    }
    echo "</ul>";
    exit;
}
?>
<form method="get">
    Search username: <input type="text" name="q">
    <button type="submit">Search</button>
</form>
PHP

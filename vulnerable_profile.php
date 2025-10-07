sudo tee /var/www/html/social_app/vulnerable_profile.php > /dev/null <<'PHP'
<?php
// vulnerable_profile.php
// Stored XSS + IDOR demo (lab only)
include 'index.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $db->prepare("SELECT id, name, username, email FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $newname = $_POST['name'];
    $upd = $db->prepare("UPDATE users SET name = ? WHERE id = ?");
    $upd->execute([$newname, $id]);
    echo "Name updated.<br>";
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

echo "<h2>Profile for user: " . htmlspecialchars($user['username']) . "</h2>";
// Intentionally not escaped to demonstrate stored XSS in lab
echo "<p>Name (may contain HTML - stored XSS demo): " . $user['name'] . "</p>";
echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
?>
<form method="post">
    Edit name (try &lt;script&gt;alert('XSS')&lt;/script&gt;):<br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"><br>
    <button type="submit">Save</button>
</form>
PHP

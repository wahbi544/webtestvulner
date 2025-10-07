cd /var/www/html/social_app

# Skapar add_test_users.php med innehåll
sudo tee add_test_users.php > /dev/null <<'PHP'
<?php
// add_test_users.php
// Insert test users into SQLite database with hashed passwords.
// Usage: php add_test_users.php /path/to/database.db
// Only for isolated lab.

if ($argc < 2) {
    echo "Usage: php add_test_users.php /path/to/database.db\n";
    exit(1);
}

$dbfile = $argv[1];

if (!file_exists($dbfile)) {
    echo "Database file not found: $dbfile\n";
    exit(1);
}

try {
    $db = new PDO('sqlite:' . $dbfile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test users: username => [name, email, plaintext_password]
    $users = [
        'userdb1' => ['Test User','test1@example.local','123'],
        'admin'   => ['Admin User','admin@example.local','admin123'],
        'weakpass'=> ['Weak Pass','weak@example.local','password'],
    ];

    // Prepare insert (ignore if already exists)
    $insert = $db->prepare('INSERT OR IGNORE INTO users (name, username, email, password) VALUES (?, ?, ?, ?)');

    foreach ($users as $uname => $info) {
        $name = $info[0];
        $email = $info[1];
        $plain = $info[2];
        $hash = password_hash($plain, PASSWORD_DEFAULT);
        $insert->execute([$name, $uname, $email, $hash]);
        echo "Inserted/ignored user: {$uname} (password: {$plain})\n";
    }

    echo "Done.\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}
?>
PHP

# Sätt ägare/rättigheter (rekommenderat)
sudo chown www-data:www-data add_test_users.php
sudo chmod 644 add_test_users.php

# Kör skriptet och peka på din databas
sudo php add_test_users.php /var/www/html/social_app/database.db

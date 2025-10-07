<?php
try {
    $db = new PDO('sqlite:database.db');
    echo "SQLite connection OK!<br>";

    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table';");
    foreach ($result as $row) {
        echo "Table: " . $row['name'] . "<br>";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<?php
require 'config.php';
$id = isset($_GET['id']) ? $_GET['id'] : '';
// Medvetet osäkert: ingen validering
$sql = "SELECT * FROM products WHERE id = $id";
$res = $mysqli->query($sql);
$row = $res ? $res->fetch_assoc() : null;
?>
<!doctype html><html><head><meta charset="utf-8"><title>Produkt</title></head><body>
<?php if ($row): ?>
    <h2><?php echo $row['name']; ?></h2>
    <p><?php echo $row['description']; ?></p>
    <p>Pris: <?php echo $row['price']; ?> SEK</p>
<?php else: ?>
    <p>Produkt hittades inte.</p>
<?php endif; ?>
<p><a href="index.php">Tillbaka</a></p>
</body></html>


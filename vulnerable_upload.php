sudo tee /var/www/html/social_app/vulnerable_upload.php > /dev/null <<'PHP'
<?php
// vulnerable_upload.php
// Insecure file upload demo (lab only)
include 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploaddir = __DIR__ . '/uploads/';
    if (!is_dir($uploaddir)) {
        mkdir($uploaddir, 0755, true);
    }
    $uploadfile = $uploaddir . basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
        echo "File uploaded: " . htmlspecialchars(basename($_FILES['file']['name'])) . "<br>";
        echo "Access it at: uploads/" . htmlspecialchars(basename($_FILES['file']['name']));
    } else {
        echo "Upload failed.";
    }
    exit;
}
?>
<form method="post" enctype="multipart/form-data">
    Upload file: <input type="file" name="file">
    <button type="submit">Upload</button>
</form>
<p>Tip: upload a PHP file in lab to simulate webshell risk.</p>
PHP

<?php
// موقع تجريبي مليء بالثغرات - للاختبار التعليمي فقط
error_reporting(0);
session_start(); // يجب أن تكون في الأعلى

// اتصال غير آمن بقاعدة البيانات
$host = "localhost";
$user = "root"; 
$password = "";
$database = "vulnerable_db";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// إدارة جلسات ضعيفة
if (isset($_GET['admin']) && $_GET['admin'] == '1') {
    // يمكن لأي شخص أن يصبح مدير!
    $_SESSION['admin'] = true;
    $_SESSION['username'] = 'admin';
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: vulnerable_site.php");
    exit;
}

// ثغرة SQL Injection في البحث
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM products WHERE name LIKE '%$search%'";
    $result = mysqli_query($conn, $query);
}

// ثغرة XSS في عرض التعليقات
if (isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    // لا يوجد أي تنقية للمدخلات!
}

// ثغرة Upload ملفات
if (isset($_FILES['file'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
    echo "<p>تم رفع الملف: " . htmlspecialchars($target_file) . "</p>";
}

// ثغرة في الصلاحيات
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
}

// كلمات مرور ضعيفة
$admin_user = "admin";
$admin_pass = "admin123";

// تسجيل الدخول الضعيف
if (isset($_POST['username']) && isset($_POST['password'])) {
    if ($_POST['username'] == $admin_user && $_POST['password'] == $admin_pass) {
        $_SESSION['admin'] = true;
        $_SESSION['username'] = $_POST['username'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>موقع تجريبي - مليء بالثغرات</title>
</head>
<body>
    <h1>موقع اختبار الثغرات الأمنية</h1>
    
    <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
        <h3 style="color: green;">وضع المدير مفعل! - user: <?php echo $_SESSION['username']; ?></h3>
        <a href="?logout=1">تسجيل الخروج</a>
    <?php endif; ?>
    
    <!-- ثغرة SQL Injection -->
    <h3>بحث المنتجات (ثغرة SQL Injection)</h3>
    <form method="GET">
        <input type="text" name="search" placeholder="ابحث عن منتج...">
        <input type="submit" value="بحث">
    </form>

    <!-- ثغرة XSS -->
    <h3>التعليقات (ثغرة XSS)</h3>
    <form method="POST">
        <textarea name="comment" placeholder="أضف تعليق..."></textarea>
        <input type="submit" value="إرسال">
    </form>

    <!-- ثغرة Upload -->
    <h3>رفع الملفات (ثغرة Upload)</h3>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" value="رفع ملف">
    </form>

    <!-- تسجيل دخول ضعيف -->
    <h3>تسجيل الدخول (كلمات مرور ضعيفة)</h3>
    <form method="POST">
        <input type="text" name="username" placeholder="اسم المستخدم">
        <input type="password" name="password" placeholder="كلمة المرور">
        <input type="submit" value="دخول">
    </form>

    <!-- ثغرة Command Injection -->
    <h3>فحص البينغ (ثغرة Command Injection)</h3>
    <form method="POST">
        <input type="text" name="ip" placeholder="أدخل IP للفحص">
        <input type="submit" value="فحص">
    </form>

    <!-- ثغرة File Inclusion -->
    <h3>عرض الملفات (ثغرة LFI/RFI)</h3>
    <a href="?page=../../../../etc/passwd">عرض passwd</a><br>
    <a href="?page=../../../../etc/hosts">عرض hosts</a><br>
    <a href="?page=../../../vulnerable_site.php">عرض الكود المصدري</a>

    <!-- ثغرة IDOR -->
    <h3>ملفات المستخدمين (ثغرة IDOR)</h3>
    <?php
    // عرض روابط لملفات المستخدمين
    for ($i = 1; $i <= 10; $i++) {
        echo "<a href='?file=user$i.txt'>ملف المستخدم $i</a><br>";
    }
    ?>

    <hr>

    <?php
    // عرض النتائج مع ثغرات
    if (isset($result) && $_GET['search']) {
        echo "<h4>نتائج البحث:</h4>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div>" . $row['name'] . " - $" . $row['price'] . "</div>";
        }
    }

    // عرض التعليقات مع XSS
    if (isset($comment)) {
        echo "<h4>تعليق جديد:</h4>";
        echo "<div>" . $comment . "</div>"; // ثغرة XSS هنا
    }

    // ثغرة Command Injection
    if (isset($_POST['ip'])) {
        $ip = $_POST['ip'];
        echo "<h4>نتيجة فحص البينغ:</h4>";
        echo "<pre>";
        // ثغرة خطيرة - تنفيذ أوامر نظام
        system("ping -c 2 " . $ip);
        echo "</pre>";
    }

    // ثغرة File Inclusion
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        echo "<h4>محتويات الملف:</h4>";
        // ثغرة File Inclusion
        include($page);
    }

    // ثغرة IDOR
    if (isset($_GET['file'])) {
        $file = $_GET['file'];
        echo "<h4>محتويات الملف:</h4>";
        // لا توجد فحص للصلاحيات!
        if (file_exists("uploads/" . $file)) {
            readfile("uploads/" . $file);
        } else {
            echo "الملف غير موجود: " . htmlspecialchars($file);
        }
    }
    ?>
</body>
</html>

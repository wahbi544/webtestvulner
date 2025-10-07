<?php
/**
 * موقع تدريبي للثغرات الأمنية - الإصدار المهيكل
 * 
 * @package    Vulnerable_Training_Site
 * @author     Student
 * @version    1.0
 * @description موقع مصمم عمداً يحتوي على ثغرات أمنية متعددة لأغراض التدريب والتعلم
 */

// =============================================================================
// الإعدادات الأساسية والتهيئة
// =============================================================================

// بدء الجلسة في الأعلى دائماً
session_start();

// إعدادات عرض الأخطاء
error_reporting(0);

// الثوابت والتكوين
define('SECRET_DEBUG_KEY', 'DEBUG_2024_KEY');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'vulnerable_db');
define('UPLOAD_DIR', 'uploads/');
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'admin123');

// =============================================================================
// دوال الاتصال بقاعدة البيانات
// =============================================================================

/**
 * إنشاء اتصال بقاعدة البيانات
 * 
 * @return mysqli|null كائن الاتصال أو null في حالة الفشل
 */
function connectToDatabase() {
    $connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (!$connection) {
        die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
    }
    
    return $connection;
}

/**
 * إغلاق اتصال قاعدة البيانات
 * 
 * @param mysqli $connection كائن الاتصال
 */
function closeDatabaseConnection($connection) {
    if ($connection) {
        mysqli_close($connection);
    }
}

// =============================================================================
// دوال إدارة الجلسات والمصادقة
// =============================================================================

/**
 * تفعيل وضع المدير بدون مصادقة (ثغرة أمنية متعمدة)
 */
function enableAdminWithoutAuth() {
    if (isset($_GET['admin']) && $_GET['admin'] == '1') {
        $_SESSION['admin'] = true;
        $_SESSION['username'] = 'admin';
    }
}

/**
 * معالجة تسجيل الدخول بكلمات مرور ضعيفة (ثغرة أمنية متعمدة)
 */
function handleWeakLogin() {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD) {
            $_SESSION['admin'] = true;
            $_SESSION['username'] = $_POST['username'];
        }
    }
}

/**
 * تسجيل الخروج
 */
function handleLogout() {
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: vulnerable_site.php");
        exit;
    }
}

// =============================================================================
// دوال معالجة الثغرات الأمنية المتعمدة
// =============================================================================

/**
 * معالجة البحث مع ثغرة SQL Injection متعمدة
 * 
 * @param mysqli $connection اتصال قاعدة البيانات
 * @return mysqli_result|bool نتيجة الاستعلام
 */
function handleVulnerableSearch($connection) {
    if (isset($_GET['search'])) {
        $search = $_GET['search'];
        // ثغرة SQL Injection متعمدة - لا تستخدم Prepared Statements
        $query = "SELECT * FROM products WHERE name LIKE '%$search%'";
        return mysqli_query($connection, $query);
    }
    return false;
}

/**
 * معالجة التعليقات مع ثغرة XSS متعمدة
 * 
 * @return string|null التعليق المدخل بدون تنقية
 */
function handleVulnerableComments() {
    if (isset($_POST['comment'])) {
        // ثغرة XSS متعمدة - لا يوجد تنقية للمدخلات
        return $_POST['comment'];
    }
    return null;
}

/**
 * معالجة رفع الملفات مع ثغرة Upload متعمدة
 */
function handleVulnerableFileUpload() {
    $upload_message = '';
    
    if (isset($_FILES['file'])) {
        $target_dir = UPLOAD_DIR;
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        
        // ثغرة Upload متعمدة - لا يوجد تحقق من نوع الملف
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $upload_message = "<p>تم رفع الملف: " . htmlspecialchars($target_file) . "</p>";
        }
    }
    
    return $upload_message;
}

/**
 * معالجة ثغرة الصلاحيات (IDOR) متعمدة
 * 
 * @param mysqli $connection اتصال قاعدة البيانات
 * @return mysqli_result|bool نتيجة الاستعلام
 */
function handleVulnerableUserAccess($connection) {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        // ثغرة IDOR متعمدة - لا توجد فحوصات صلاحيات
        $query = "SELECT * FROM users WHERE id = $user_id";
        return mysqli_query($connection, $query);
    }
    return false;
}

/**
 * معالجة ثغرة Command Injection متعمدة
 * 
 * @return string|null نتيجة تنفيذ الأمر
 */
function handleCommandInjection() {
    if (isset($_POST['ip'])) {
        $ip = $_POST['ip'];
        // ثغرة Command Injection متعمدة - تنفيذ أوامر نظام مباشرة
        return shell_exec("ping -c 2 " . $ip);
    }
    return null;
}

/**
 * معالجة ثغرة ImageMagick Command Injection متعمدة
 */
function handleImageMagickVulnerability() {
    if (isset($_POST['process_image']) && isset($_FILES['image'])) {
        $image_path = UPLOAD_DIR . uniqid() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        
        // ثغرة ImageMagick Command Injection متعمدة
        $output_path = "uploads/processed_" . basename($image_path);
        $command = "convert " . $image_path . " -resize 50% " . $output_path;
        system($command);
        
        return "<p>تم معالجة الصورة: <a href='$output_path'>$output_path</a></p>";
    }
    return '';
}

/**
 * معالجة ثغرة File Inclusion متعمدة
 */
function handleFileInclusion() {
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        // ثغرة File Inclusion متعمدة
        ob_start();
        include($page);
        return ob_get_clean();
    }
    return '';
}

/**
 * معالجة ثغرة Insecure Deserialization متعمدة
 */
function handleInsecureDeserialization() {
    if (isset($_POST['serialized_data'])) {
        // ثغرة Insecure Deserialization متعمدة
        return unserialize($_POST['serialized_data']);
    }
    return null;
}

/**
 * معالجة الـ API المخفي (ثغرة أمنية متعمدة)
 * 
 * @param mysqli $connection اتصال قاعدة البيانات
 */
function handleHiddenAPI($connection) {
    if (isset($_GET['api']) && $_GET['api'] == 'v1') {
        header('Content-Type: application/json');
        
        if ($_GET['action'] == 'get_users') {
            // لا يوجد authentication للـ API (ثغرة متعمدة)
            $query = "SELECT id, username, email FROM users";
            $result = mysqli_query($connection, $query);
            $users = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
            echo json_encode($users);
            exit;
        }
        
        if ($_GET['action'] == 'exec' && isset($_GET['cmd'])) {
            // backdoor مخفي - للطوارئ فقط (ثغرة متعمدة)
            $output = shell_exec($_GET['cmd']);
            echo json_encode(['output' => $output]);
            exit;
        }
    }
}

/**
 * معالجة ثغرة Session Fixation متعمدة
 */
function handleSessionFixation() {
    if (isset($_GET['session_fix'])) {
        // ثغرة Session Fixation متعمدة
        session_id($_GET['session_fix']);
        session_start();
    }
}

// =============================================================================
// دوال العرض والواجهة
// =============================================================================

/**
 * عرض رأس HTML
 */
function renderHTMLHeader() {
    echo '<!DOCTYPE html>
    <html lang="ar">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>موقع تجريبي - مليء بالثغرات</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            .section { border: 1px solid #ccc; padding: 15px; margin: 10px 0; }
            .vulnerable { background-color: #ffe6e6; }
            .admin-panel { background-color: #e6ffe6; padding: 10px; }
        </style>
    </head>
    <body>
        <h1>موقع اختبار الثغرات الأمنية - الإصدار المهيكل</h1>';
}

/**
 * عرض لوحة التحكم للمدير
 */
function renderAdminPanel() {
    if (isset($_SESSION['admin']) && $_SESSION['admin']) {
        echo '<div class="admin-panel">
            <h3 style="color: green;">وضع المدير مفعل! - user: ' . $_SESSION['username'] . '</h3>
            <a href="?logout=1">تسجيل الخروج</a>
        </div>';
    }
}

/**
 * عرض نماذج الثغرات الأمنية
 */
function renderVulnerabilityForms() {
    // نموذج SQL Injection
    echo '<div class="section vulnerable">
        <h3>بحث المنتجات (ثغرة SQL Injection)</h3>
        <form method="GET">
            <input type="text" name="search" placeholder="ابحث عن منتج...">
            <input type="submit" value="بحث">
        </form>
    </div>';

    // نموذج XSS
    echo '<div class="section vulnerable">
        <h3>التعليقات (ثغرة XSS)</h3>
        <form method="POST">
            <textarea name="comment" placeholder="أضف تعليق..."></textarea>
            <input type="submit" value="إرسال">
        </form>
    </div>';

    // نموذج Upload
    echo '<div class="section vulnerable">
        <h3>رفع الملفات (ثغرة Upload)</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="file">
            <input type="submit" value="رفع ملف">
        </form>
    </div>';

    // نموذج تسجيل الدخول الضعيف
    echo '<div class="section vulnerable">
        <h3>تسجيل الدخول (كلمات مرور ضعيفة)</h3>
        <form method="POST">
            <input type="text" name="username" placeholder="اسم المستخدم">
            <input type="password" name="password" placeholder="كلمة المرور">
            <input type="submit" value="دخول">
        </form>
    </div>';

    // نموذج Command Injection
    echo '<div class="section vulnerable">
        <h3>فحص البينغ (ثغرة Command Injection)</h3>
        <form method="POST">
            <input type="text" name="ip" placeholder="أدخل IP للفحص">
            <input type="submit" value="فحص">
        </form>
    </div>';

    // نموذج ImageMagick
    echo '<div class="section vulnerable">
        <h3>معالجة الصور (ثغرة ImageMagick)</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/*">
            <input type="submit" name="process_image" value="معالجة الصورة">
        </form>
    </div>';

    // نموذج Deserialization
    echo '<div class="section vulnerable">
        <h3>إدخال بيانات متسلسلة (ثغرة Deserialization)</h3>
        <form method="POST">
            <input type="text" name="serialized_data" placeholder="أدخل بيانات متسلسلة">
            <input type="submit" value="معالجة">
        </form>
    </div>';

    // روابط File Inclusion
    echo '<div class="section vulnerable">
        <h3>عرض الملفات (ثغرة LFI/RFI)</h3>
        <a href="?page=../../../../etc/passwd">عرض passwd</a><br>
        <a href="?page=../../../../etc/hosts">عرض hosts</a><br>
        <a href="?page=../../../vulnerable_site.php">عرض الكود المصدري</a>
    </div>';

    // روابط IDOR
    echo '<div class="section vulnerable">
        <h3>ملفات المستخدمين (ثغرة IDOR)</h3>';
    for ($i = 1; $i <= 10; $i++) {
        echo "<a href='?file=user$i.txt'>ملف المستخدم $i</a><br>";
    }
    echo '</div>';
}

/**
 * عرض النتائج والبيانات
 * 
 * @param mysqli $connection اتصال قاعدة البيانات
 * @param array $results نتائج العمليات
 */
function renderResults($connection, $results) {
    echo '<div class="section">
        <h2>النتائج والمعطيات</h2>';
    
    // عرض نتائج البحث
    if (isset($results['search_result']) && $results['search_result'] && isset($_GET['search'])) {
        echo "<h4>نتائج البحث:</h4>";
        while ($row = mysqli_fetch_assoc($results['search_result'])) {
            echo "<div>" . $row['name'] . " - $" . $row['price'] . "</div>";
        }
    }

    // عرض التعليقات مع XSS
    if (isset($results['comment'])) {
        echo "<h4>تعليق جديد:</h4>";
        echo "<div>" . $results['comment'] . "</div>"; // ثغرة XSS متعمدة
    }

    // عرض نتائج Command Injection
    if (isset($results['command_output'])) {
        echo "<h4>نتيجة فحص البينغ:</h4>";
        echo "<pre>" . $results['command_output'] . "</pre>";
    }

    // عرض نتائج File Inclusion
    if (isset($results['file_content'])) {
        echo "<h4>محتويات الملف:</h4>";
        echo $results['file_content'];
    }

    // عرض نتائج IDOR
    if (isset($_GET['file'])) {
        $file = $_GET['file'];
        echo "<h4>محتويات الملف:</h4>";
        if (file_exists(UPLOAD_DIR . $file)) {
            readfile(UPLOAD_DIR . $file);
        } else {
            echo "الملف غير موجود: " . htmlspecialchars($file);
        }
    }

    // عرض رسالة رفع الملف
    if (isset($results['upload_message'])) {
        echo $results['upload_message'];
    }

    // عرض نتيجة معالجة الصور
    if (isset($results['image_processing_result'])) {
        echo $results['image_processing_result'];
    }

    echo '</div>';
}

/**
 * عرض تذييل HTML
 */
function renderHTMLFooter() {
    echo '</body>
    </html>';
}

// =============================================================================
// التنفيذ الرئيسي
// =============================================================================

// تفعيل وضع التصحيح إذا طلب
if (isset($_GET['debug_key']) && $_GET['debug_key'] === SECRET_DEBUG_KEY) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    echo "<h3>وضع التصحيح مفعل!</h3>";
}

// الاتصال بقاعدة البيانات
$db_connection = connectToDatabase();

// معالجة الطلبات
$results = [];

// إدارة الجلسات والمصادقة
enableAdminWithoutAuth();
handleWeakLogin();
handleLogout();

// معالجة الثغرات الأمنية
$results['search_result'] = handleVulnerableSearch($db_connection);
$results['comment'] = handleVulnerableComments();
$results['upload_message'] = handleVulnerableFileUpload();
$results['user_access_result'] = handleVulnerableUserAccess($db_connection);
$results['command_output'] = handleCommandInjection();
$results['image_processing_result'] = handleImageMagickVulnerability();
$results['file_content'] = handleFileInclusion();
$results['deserialized_data'] = handleInsecureDeserialization();

// معالجة الـ API المخفي وثغرات إضافية
handleHiddenAPI($db_connection);
handleSessionFixation();

// العرض
renderHTMLHeader();
renderAdminPanel();
renderVulnerabilityForms();
renderResults($db_connection, $results);
renderHTMLFooter();

// تنظيف الموارد
closeDatabaseConnection($db_connection);
?>

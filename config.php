 <?php
// إعدادات قاعدة البيانات
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'file_sharing_site');

// إعدادات الموقع
define('SITE_NAME', 'مشاركة الملفات');
define('UPLOAD_DIR', 'uploads/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB

// بدء الجلسة
session_start();

// الاتصال بقاعدة البيانات
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");
} catch(PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}

// دالة للتحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// دالة للحصول على معلومات المستخدم الحالي
function getCurrentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// دالة لتشفير كلمة المرور
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// دالة للتحقق من كلمة المرور
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// دالة لإنشاء رمز مشاركة عشوائي
function generateShareToken() {
    return bin2hex(random_bytes(16));
}
?>

<?php
/**
 * ملف الإعدادات الرئيسي
 * تعديل البيانات حسب احتياجاتك
 */

// ===== إعدادات قاعدة البيانات =====
define('DB_HOST', '31.97.198.54');      // مثال: localhost
define('DB_USER', 'u527029479_mahmoud');  // مثال: root
define('DB_PASS', '~o6PbxE&LLs');  // كلمة المرور
define('DB_NAME', 'u527029479_file');      // اسم قاعدة البيانات

// ===== إعدادات الموقع =====
define('SITE_NAME', 'MS Tour Fun');
define('SITE_URL', 'https://mstour.fun');
define('SITE_DESCRIPTION', 'منصة آمنة لمشاركة الملفات');

// ===== إعدادات البريد الإلكتروني =====
define('MAIL_FROM', 'info@mstour.fun');
define('MAIL_FROM_NAME', 'MS Tour Fun');

// ===== إعدادات المساحة التخزينية =====
define('STORAGE_LIMIT', 2 * 1024 * 1024 * 1024);  // 2 GB
define('MAX_FILE_SIZE', 500 * 1024 * 1024);       // 500 MB
define('UPLOAD_DIR', __DIR__ . '/../uploads/');

// ===== إعدادات رمز التحقق =====
define('VERIFICATION_CODE_LENGTH', 6);
define('VERIFICATION_CODE_EXPIRY', 600);  // 10 دقائق بالثواني
define('MAX_VERIFICATION_ATTEMPTS', 5);

// ===== إعدادات الأمان =====
define('JWT_SECRET', 'YOUR_SECRET_KEY_CHANGE_THIS');
define('SESSION_TIMEOUT', 86400);  // 24 ساعة بالثواني

// ===== إعدادات اللغات =====
define('DEFAULT_LANGUAGE', 'ar');
define('SUPPORTED_LANGUAGES', ['ar', 'en']);

// ===== الثوابت الأخرى =====
<<<<<<< HEAD
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'txt', 'php', 'html', 'css', 'js']);
=======
define('ALLOWED_FILE_TYPES', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'txt']);
>>>>>>> 03a7eaf3cc07107b36c95589b7bd91e4012d78ed

// إنشاء اتصال قاعدة البيانات
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('خطأ في الاتصال بقاعدة البيانات: ' . $e->getMessage());
}

// دالة الحصول على اللغة الحالية
function getCurrentLanguage() {
    if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGUAGES)) {
        $_SESSION['language'] = $_GET['lang'];
        return $_GET['lang'];
    }
    return $_SESSION['language'] ?? DEFAULT_LANGUAGE;
}

// دالة الحصول على اتجاه النص
function getDirection($lang = null) {
    $lang = $lang ?? getCurrentLanguage();
    return $lang === 'ar' ? 'rtl' : 'ltr';
}
?>

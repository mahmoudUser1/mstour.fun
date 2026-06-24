<?php
// إعدادات قاعدة البيانات
define('DB_HOST', '31.97.198.54');
define('DB_USER', 'u527029479_MahmoudMaher');
define('DB_PASS', '$9V7~5aVc');
define('DB_NAME', 'u527029479_fileSharing');

// إعدادات الموقع
define('SITE_NAME', 'MS Tool');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // 100MB

// بدء الجلسة
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// الاتصال بقاعدة البيانات
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");
} catch(PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}
?>
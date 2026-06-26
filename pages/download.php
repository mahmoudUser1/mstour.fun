<?php
require_once __DIR__ . '/../includes/header.php';

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    redirect(SITE_URL . '/pages/login.php');
}

$fileId = $_GET['id'] ?? null;
$user = getCurrentUser();

if (!$fileId) {
    die("ملف غير موجود.");
}

// الحصول على معلومات الملف من قاعدة البيانات والتأكد من ملكيته للمستخدم
$stmt = $pdo->prepare('SELECT * FROM files WHERE id = ? AND user_id = ?');
$stmt->execute([$fileId, $user['id']]);
$file = $stmt->fetch();

if (!$file) {
    die("ليس لديك صلاحية للوصول لهذا الملف أو الملف غير موجود.");
}

$filepath = $file['file_path'];

if (file_exists($filepath)) {
    // إعداد الهيدرز للتحميل
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    
    // قراءة الملف وإرساله للمتصفح
    readfile($filepath);
    exit;
} else {
    die("الملف الفيزيائي غير موجود على السيرفر.");
}
?>

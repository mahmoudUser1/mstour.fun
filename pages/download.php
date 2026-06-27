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
    // تحديد نوع المحتوى بناءً على امتداد الملف
    $mime_types = [
        'pdf'  => 'application/pdf',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'gif'  => 'image/gif',
        'txt'  => 'text/plain',
        'html' => 'text/html',
        'css'  => 'text/css',
    ];
    
    $ext = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
    $content_type = $mime_types[$ext] ?? 'application/octet-stream';
    
    // إعداد الهيدرز
    header('Content-Type: ' . $content_type);
    
    // إذا كان الملف صورة أو PDF، نعرضه مباشرة، وإلا نقوم بتحميله
    $inline_types = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'text/plain'];
    $disposition = in_array($content_type, $inline_types) ? 'inline' : 'attachment';
    
    header('Content-Disposition: ' . $disposition . '; filename="' . $file['original_name'] . '"');
    header('Content-Length: ' . filesize($filepath));
    header('Cache-Control: public, max-age=3600');
    
    // قراءة الملف وإرساله للمتصفح
    readfile($filepath);
    exit;
} else {
    die("الملف الفيزيائي غير موجود على السيرفر.");
}
?>

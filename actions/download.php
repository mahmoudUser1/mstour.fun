<?php
require_once '../includes/functions.php';

if (!isLoggedIn()) exit('Unauthorized');

$file_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = getCurrentUser();

// جلب معلومات الملف والتأكد من ملكيته
$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$file_id, $user['id']]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if ($file) {
    $file_path = $file['file_path'];
    
    // التأكد من وجود الملف فعلياً على السيرفر
    if (file_exists($file_path)) {
        // تنظيف الاسم الأصلي للملف لمنع المشاكل في المتصفحات
        $original_name = basename($file['original_name']);
        
        // إرسال الهيدرز الصحيحة للتحميل
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $original_name . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        
        // مسح البافير لتجنب تلف الملفات الكبيرة
        ob_clean();
        flush();
        
        // قراءة وإرسال الملف الأصلي
        readfile($file_path);
        exit;
    } else {
        die("خطأ: الملف الأصلي غير موجود على السيرفر في المسار: " . htmlspecialchars($file_path));
    }
} else {
    die("خطأ: لم يتم العثور على الملف أو ليس لديك صلاحية الوصول إليه.");
}
?>

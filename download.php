 <?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$user = getCurrentUser();
$file_id = isset($_GET['file']) ? (int)$_GET['file'] : 0;

if (!$file_id) {
    die('معرف الملف غير صحيح');
}

// جلب معلومات الملف
$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$file_id, $user['id']]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    die('الملف غير موجود أو ليس لديك صلاحية للوصول إليه');
}

$file_path = $file['file_path'];

if (!file_exists($file_path)) {
    die('الملف غير موجود على الخادم');
}

// إعداد headers للتحميل
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
header('Content-Length: ' . filesize($file_path));
header('Cache-Control: must-revalidate');
header('Pragma: public');

// إرسال الملف
readfile($file_path);
exit;
?>

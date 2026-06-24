<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$file_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$user = getCurrentUser();

$stmt = $pdo->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
$stmt->execute([$file_id, $user['id']]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$file) {
    die("الملف غير موجود أو ليس لديك صلاحية لعرضه.");
}

$file_path = $file['file_path'];
$file_ext = strtolower(pathinfo($file['original_name'], PATHINFO_EXTENSION));
$mime_type = $file['mime_type'];

// تصنيف أنواع الملفات
$image_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
$video_exts = ['mp4', 'webm', 'ogg'];
$text_exts = ['txt', 'php', 'html', 'css', 'js', 'sql', 'json', 'md'];

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags('عرض ملف: ' . $file['original_name'], 'معاينة ملف ' . $file['original_name'] . ' مباشرة على المنصة.', 'معاينة, عرض ملف, ' . $file_ext); ?>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .viewer-container { max-width: 1000px; margin: 20px auto; }
        .viewer-content { background: #fff; border-radius: 12px; padding: 20px; text-align: center; box-shadow: var(--shadow); }
        .media-preview { max-width: 100%; max-height: 80vh; border-radius: 8px; }
        .text-preview { text-align: left; background: #f4f4f4; padding: 20px; border-radius: 8px; overflow-x: auto; white-space: pre-wrap; font-family: monospace; max-height: 70vh; }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="index.php" class="brand">📁 عودة للملفات</a>
            <div class="file-title"><?php echo htmlspecialchars($file['original_name']); ?></div>
            <a href="actions/download.php?id=<?php echo $file['id']; ?>" class="btn btn-primary">تحميل الملف</a>
        </div>
    </nav>

    <div class="container viewer-container">
        <div class="viewer-content">
            <?php if (in_array($file_ext, $image_exts)): ?>
                <!-- عرض الصور -->
                <img src="actions/stream.php?id=<?php echo $file['id']; ?>" class="media-preview" alt="Image">
                
            <?php elseif (in_array($file_ext, $video_exts)): ?>
                <!-- عرض الفيديو -->
                <video controls class="media-preview">
                    <source src="actions/stream.php?id=<?php echo $file['id']; ?>" type="<?php echo $mime_type; ?>">
                    متصفحك لا يدعم تشغيل الفيديو.
                </video>
                
            <?php elseif (in_array($file_ext, $text_exts)): ?>
                <!-- عرض الملفات النصية -->
                <div class="text-preview"><?php echo htmlspecialchars(file_get_contents($file_path)); ?></div>
                
            <?php else: ?>
                <!-- ملف غير مدعوم للمعاينة -->
                <div style="padding: 50px;">
                    <span style="font-size: 5rem;">📄</span>
                    <h3>هذا النوع من الملفات لا يدعم المعاينة المباشرة</h3>
                    <p>يمكنك تحميل الملف لمشاهدته على جهازك.</p>
                    <a href="actions/download.php?id=<?php echo $file['id']; ?>" class="btn btn-primary" style="margin-top: 20px;">تحميل الآن</a>
                </div>
            <?php endif; ?>
        </div>
        
        <div style="margin-top: 20px; color: var(--text-muted); text-align: center;">
            حجم الملف: <?php echo formatSize($file['file_size']); ?> | تاريخ الرفع: <?php echo $file['created_at']; ?>
        </div>
    </div>
</body>
</html>

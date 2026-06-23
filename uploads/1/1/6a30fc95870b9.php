 <?php
require_once 'config.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';
$error = '';

if (empty($token)) {
    $error = 'رمز المشاركة غير صحيح';
} else {
    // جلب معلومات المشاركة
    $stmt = $pdo->prepare("SELECT s.*, f.original_name as file_name, f.file_path, f.file_size, 
                          fo.name as folder_name, u.username as owner_name 
                          FROM shares s 
                          LEFT JOIN files f ON s.file_id = f.id 
                          LEFT JOIN folders fo ON s.folder_id = fo.id 
                          LEFT JOIN users u ON s.shared_by = u.id 
                          WHERE s.share_token = ?");
    $stmt->execute([$token]);
    $share = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$share) {
        $error = 'رابط المشاركة غير صحيح أو منتهي الصلاحية';
    } elseif ($share['expires_at'] && strtotime($share['expires_at']) < time()) {
        $error = 'رابط المشاركة منتهي الصلاحية';
    } elseif (!$share['is_public'] && (!isLoggedIn() || $_SESSION['user_id'] != $share['shared_with'])) {
        $error = 'ليس لديك صلاحية للوصول إلى هذا المحتوى';
    }
}

// معالجة تحميل الملف
if (isset($_GET['download']) && !$error && $share['file_id']) {
    $file_path = $share['file_path'];
    
    if (file_exists($file_path)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $share['file_name'] . '"');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        $error = 'الملف غير موجود على الخادم';
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض المشاركة - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <h1>📁 <?php echo SITE_NAME; ?></h1>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if ($error): ?>
            <div class="error-section">
                <h2>خطأ</h2>
                <p><?php echo $error; ?></p>
                <a href="index.php" class="btn btn-primary">العودة للرئيسية</a>
            </div>
        <?php else: ?>
            <div class="shared-content">
                <h2>محتوى مشارك</h2>
                <p><strong>مشارك بواسطة:</strong> <?php echo htmlspecialchars($share['owner_name']); ?></p>
                
                <?php if ($share['file_id']): ?>
                    <!-- عرض الملف -->
                    <div class="shared-file">
                        <div class="file-info">
                            <div class="file-icon">📄</div>
                            <div class="file-details">
                                <h3><?php echo htmlspecialchars($share['file_name']); ?></h3>
                                <p>الحجم: <?php echo number_format($share['file_size'] / 1024, 2); ?> KB</p>
                                <p>تاريخ المشاركة: <?php echo date('Y-m-d H:i', strtotime($share['created_at'])); ?></p>
                            </div>
                        </div>
                        
                        <div class="file-actions">
                            <a href="?token=<?php echo $token; ?>&download=1" class="btn btn-primary">تحميل الملف</a>
                        </div>
                    </div>
                    
                <?php elseif ($share['folder_id']): ?>
                    <!-- عرض المجلد -->
                    <div class="shared-folder">
                        <div class="folder-info">
                            <div class="folder-icon">📁</div>
                            <div class="folder-details">
                                <h3><?php echo htmlspecialchars($share['folder_name']); ?></h3>
                                <p>تاريخ المشاركة: <?php echo date('Y-m-d H:i', strtotime($share['created_at'])); ?></p>
                            </div>
                        </div>
                        
                        <?php
                        // جلب محتويات المجلد
                        $stmt = $pdo->prepare("SELECT * FROM files WHERE folder_id = ? ORDER BY original_name");
                        $stmt->execute([$share['folder_id']]);
                        $folder_files = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        $stmt = $pdo->prepare("SELECT * FROM folders WHERE parent_id = ? ORDER BY name");
                        $stmt->execute([$share['folder_id']]);
                        $subfolders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        
                        <div class="folder-contents">
                            <h4>محتويات المجلد:</h4>
                            
                            <?php if (!empty($subfolders)): ?>
                                <div class="subfolders">
                                    <h5>المجلدات الفرعية:</h5>
                                    <?php foreach ($subfolders as $subfolder): ?>
                                        <div class="item-card">
                                            <span class="folder-icon">📁</span>
                                            <span><?php echo htmlspecialchars($subfolder['name']); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($folder_files)): ?>
                                <div class="folder-files">
                                    <h5>الملفات:</h5>
                                    <?php foreach ($folder_files as $file): ?>
                                        <div class="item-card">
                                            <span class="file-icon">📄</span>
                                            <span><?php echo htmlspecialchars($file['original_name']); ?></span>
                                            <span class="file-size">(<?php echo number_format($file['file_size'] / 1024, 2); ?> KB)</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (empty($subfolders) && empty($folder_files)): ?>
                                <p>المجلد فارغ</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

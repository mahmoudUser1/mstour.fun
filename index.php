<?php
require_once 'includes/functions.php';

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$user = getCurrentUser();
$current_folder = isset($_GET['folder']) ? (int)$_GET['folder'] : null;

// جلب البيانات (تبسيطاً للعرض)
$stmt = $pdo->prepare("SELECT * FROM folders WHERE user_id = ? AND parent_id " . ($current_folder ? "= ?" : "IS NULL"));
$current_folder ? $stmt->execute([$user['id'], $current_folder]) : $stmt->execute([$user['id']]);
$folders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ? AND folder_id " . ($current_folder ? "= ?" : "IS NULL"));
$current_folder ? $stmt->execute([$user['id'], $current_folder]) : $stmt->execute([$user['id']]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags('الرئيسية', 'إدارة ورفع ملفاتك ومجلداتك بسهولة وأمان.', 'ملفاتي, رفع, تحميل, مشاركة'); ?>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="index.php" class="brand">📁 <?php echo SITE_NAME; ?></a>
            <div class="user-menu">
                <span>مرحباً، <?php echo htmlspecialchars($user['username']); ?></span>
                <a href="notebook.php" class="btn btn-outline" style="margin-right: 10px;">📓 المفكرة</a>
                <?php if ($user['is_admin']): ?>
                    <a href="admin/index.php" class="btn btn-outline" style="margin-right: 10px;">لوحة المسؤول</a>
                <?php endif; ?>
                <a href="actions/auth.php?action=logout" class="btn btn-primary">خروج</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="main-layout">
            <!-- المحتوى الرئيسي -->
            <div class="content-area">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">رفع ملفات جديدة</h2>
                    </div>
                    <form action="actions/upload.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="folder_id" value="<?php echo $current_folder; ?>">
                        <div class="form-group">
                            <input type="file" name="files[]" multiple class="form-input" required>
                        </div>
                        <button type="submit" class="btn btn-primary">بدء الرفع</button>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">ملفاتي</h2>
                        <button class="btn btn-outline" onclick="document.getElementById('newFolderModal').style.display='block'">+ مجلد جديد</button>
                    </div>
                    
                    <div class="grid-items">
                        <!-- المجلدات -->
                        <?php foreach ($folders as $f): ?>
                            <div class="item-node">
                                <span class="item-icon">📁</span>
                                <a href="?folder=<?php echo $f['id']; ?>" class="item-name"><?php echo htmlspecialchars($f['name']); ?></a>
                                <span class="item-meta">مجلد</span>
                            </div>
                        <?php endforeach; ?>

                        <!-- الملفات -->
                        <?php foreach ($files as $f): ?>
                            <div class="item-node">
                                <span class="item-icon">📄</span>
                                <span class="item-name"><?php echo htmlspecialchars($f['original_name']); ?></span>
                                <span class="item-meta"><?php echo formatSize($f['file_size']); ?></span>
                                <div style="margin-top: 10px; display: flex; gap: 5px; justify-content: center;">
                                    <a href="viewer.php?id=<?php echo $f['id']; ?>" class="btn btn-primary" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">عرض</a>
                                    <a href="actions/download.php?id=<?php echo $f['id']; ?>" class="btn btn-outline" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">تحميل</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- الشريط الجانبي (الإعلانات) -->
            <div class="sidebar">
                <div class="ad-sidebar">
                    <?php
                    $ads = getActiveAds('sidebar');
                    if ($ads):
                        $ad = $ads[0];
                    ?>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">إعلان ممول</p>
                        <a href="<?php echo htmlspecialchars($ad['ad_link']); ?>" target="_blank">
                            <?php if ($ad['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($ad['image_url']); ?>" alt="AD">
                            <?php endif; ?>
                            <h4 style="margin-top: 10px;"><?php echo htmlspecialchars($ad['title']); ?></h4>
                        </a>
                    <?php else: ?>
                        <div style="color: var(--text-muted);">
                            <p>📢</p>
                            <p>مساحة إعلانية متوفرة</p>
                            <p style="font-size: 0.8rem;">تواصل معنا للإعلان</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال مجلد جديد (تبسيط) -->
    <div id="newFolderModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:2000;">
        <div class="card" style="width:400px; margin:100px auto;">
            <h3 class="card-title">إنشاء مجلد جديد</h3>
            <form action="actions/folder.php" method="POST">
                <input type="hidden" name="parent_id" value="<?php echo $current_folder; ?>">
                <div class="form-group">
                    <input type="text" name="name" class="form-input" placeholder="اسم المجلد" required>
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primary">إنشاء</button>
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('newFolderModal').style.display='none'">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

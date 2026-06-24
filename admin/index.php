<?php
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: ../index.php');
    exit;
}

$user = getCurrentUser();
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

// جلب الإحصائيات
$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$files_count = $pdo->query("SELECT COUNT(*) FROM files")->fetchColumn();
$ads_count = $pdo->query("SELECT COUNT(*) FROM advertisements")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags('لوحة المسؤول', 'إدارة المستخدمين والإعلانات والنظام.', 'إدارة, لوحة التحكم, مسؤول'); ?>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-sidebar { background: #fff; border-left: 1px solid var(--border); height: 100vh; position: sticky; top: 0; padding: 20px; }
        .admin-nav-link { display: block; padding: 10px 15px; border-radius: 8px; text-decoration: none; color: var(--text-dark); margin-bottom: 5px; transition: 0.2s; }
        .admin-nav-link:hover, .admin-nav-link.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        .stat-card { background: var(--bg-card); padding: 20px; border-radius: var(--radius); border: 1px solid var(--border); text-align: center; }
        .stat-number { font-size: 2rem; font-weight: 800; color: var(--primary); }
    </style>
</head>
<body>
    <div style="display: grid; grid-template-columns: 250px 1fr;">
        <!-- القائمة الجانبية للمسؤول -->
        <div class="admin-sidebar">
            <h2 style="color: var(--primary); margin-bottom: 30px;">إدارة النظام</h2>
            <a href="index.php" class="admin-nav-link active">الرئيسية</a>
            <a href="ads.php" class="admin-nav-link">إدارة الإعلانات</a>
            <a href="users.php" class="admin-nav-link">المستخدمين</a>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid var(--border);">
            <a href="../index.php" class="admin-nav-link">العودة للموقع</a>
        </div>

        <!-- محتوى لوحة التحكم -->
        <div class="container">
            <header style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center;">
                <h1>لوحة التحكم الرئيسية</h1>
                <div class="user-info">مرحباً، <?php echo htmlspecialchars($user['username']); ?></div>
            </header>

            <?php if ($message): ?>
                <div class="card" style="background: #d1fae5; color: #065f46; border-color: #34d399;">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="grid-items" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 40px;">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $users_count; ?></div>
                    <div class="stat-label">مستخدم مسجل</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $files_count; ?></div>
                    <div class="stat-label">ملف مرفوع</div>
                </div>
                <div class="stat-card">
                    <?php $notes_count = $pdo->query("SELECT COUNT(*) FROM notes")->fetchColumn(); ?>
                    <div class="stat-number"><?php echo $notes_count; ?></div>
                    <div class="stat-label">ملاحظة/رابط</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $ads_count; ?></div>
                    <div class="stat-label">إعلان نشط</div>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title">إضافة إعلان سريع</h3>
                <form action="../actions/admin_ads.php" method="POST" enctype="multipart/form-data">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label class="form-label">عنوان الإعلان</label>
                            <input type="text" name="title" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">رابط الإعلان</label>
                            <input type="url" name="ad_link" class="form-input" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">صورة الإعلان</label>
                        <input type="file" name="ad_image" class="form-input" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">نشر الإعلان</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

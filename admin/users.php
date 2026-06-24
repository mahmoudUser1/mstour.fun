<?php
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: ../index.php');
    exit;
}

$user = getCurrentUser();

// جلب المستخدمين مع إحصائياتهم
$stmt = $pdo->query("
    SELECT u.id, u.username, u.email, u.is_verified, 
    (SELECT COUNT(*) FROM files WHERE user_id = u.id) as files_count,
    (SELECT COUNT(*) FROM notes WHERE user_id = u.id) as notes_count
    FROM users u ORDER BY u.created_at DESC
");
$users_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// عرض ملفات مستخدم معين (نصوص فقط)
$selected_user_files = [];
$selected_user_notes = [];
if (isset($_GET['view_user'])) {
    $uid = (int)$_GET['view_user'];
    
    // جلب الملفات النصية فقط (حماية الخصوصية)
    $stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ? AND (mime_type LIKE 'text/%' OR original_name LIKE '%.txt' OR original_name LIKE '%.php' OR original_name LIKE '%.sql')");
    $stmt->execute([$uid]);
    $selected_user_files = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // جلب الملاحظات
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ?");
    $stmt->execute([$uid]);
    $selected_user_notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags('إدارة المستخدمين', 'عرض إحصائيات المستخدمين وملفاتهم النصية.', 'إدارة, مستخدمين, مسؤول'); ?>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .admin-sidebar { background: #fff; border-left: 1px solid var(--border); height: 100vh; position: sticky; top: 0; padding: 20px; }
        .admin-nav-link { display: block; padding: 10px 15px; border-radius: 8px; text-decoration: none; color: var(--text-dark); margin-bottom: 5px; transition: 0.2s; }
        .admin-nav-link:hover, .admin-nav-link.active { background: var(--primary-light); color: var(--primary); font-weight: 600; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
        th, td { padding: 15px; text-align: right; border-bottom: 1px solid var(--border); }
        th { background: var(--primary-light); color: var(--primary); }
    </style>
</head>
<body>
    <div style="display: grid; grid-template-columns: 250px 1fr;">
        <div class="admin-sidebar">
            <h2 style="color: var(--primary); margin-bottom: 30px;">إدارة النظام</h2>
            <a href="index.php" class="admin-nav-link">الرئيسية</a>
            <a href="users.php" class="admin-nav-link active">المستخدمين</a>
            <hr style="margin: 20px 0; border: none; border-top: 1px solid var(--border);">
            <a href="../index.php" class="admin-nav-link">العودة للموقع</a>
        </div>

        <div class="container">
            <h1>إدارة المستخدمين</h1>
            
            <div class="card" style="margin-top: 30px;">
                <table>
                    <thead>
                        <tr>
                            <th>المستخدم</th>
                            <th>البريد</th>
                            <th>الملفات</th>
                            <th>المفكرة</th>
                            <th>الحالة</th>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users_list as $u): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($u['username']); ?></strong></td>
                                <td><?php echo htmlspecialchars($u['email']); ?></td>
                                <td><?php echo $u['files_count']; ?> ملف</td>
                                <td><?php echo $u['notes_count']; ?> ملاحظة</td>
                                <td><?php echo $u['is_verified'] ? '✅ مفعل' : '⏳ معلق'; ?></td>
                                <td><a href="?view_user=<?php echo $u['id']; ?>" class="btn btn-outline" style="font-size: 0.8rem;">تصفح النصوص</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (isset($_GET['view_user'])): ?>
                <div class="card" style="margin-top: 40px; border-top: 5px solid var(--primary);">
                    <h2 class="card-title">تصفح ملفات وملاحظات المستخدم (نصوص فقط 🔒)</h2>
                    
                    <h3 style="margin: 20px 0 10px;">الملاحظات والروابط:</h3>
                    <div class="grid-items">
                        <?php foreach ($selected_user_notes as $n): ?>
                            <div class="card" style="padding: 10px; font-size: 0.9rem;">
                                <strong><?php echo htmlspecialchars($n['title']); ?>:</strong>
                                <p><?php echo nl2br(htmlspecialchars($n['content'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <h3 style="margin: 20px 0 10px;">الملفات النصية المرفوعة:</h3>
                    <div class="grid-items">
                        <?php foreach ($selected_user_files as $f): ?>
                            <div class="card" style="padding: 10px; font-size: 0.9rem;">
                                <strong><?php echo htmlspecialchars($f['original_name']); ?></strong>
                                <a href="../viewer.php?id=<?php echo $f['id']; ?>" target="_blank" style="display: block; color: var(--primary); margin-top: 5px;">فتح المعاينة</a>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($selected_user_files)): ?>
                            <p style="color: var(--text-muted);">لا توجد ملفات نصية متاحة للعرض.</p>
                        <?php endif; ?>
                    </div>
                    
                    <p style="margin-top: 20px; font-size: 0.8rem; color: var(--text-muted);">* تنبيه: لا يمكن للمسؤول رؤية الصور أو الفيديوهات حفاظاً على خصوصية المستخدم.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

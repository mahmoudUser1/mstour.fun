<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$user = getCurrentUser();
$message = '';

// معالجة إضافة ملاحظة/رابط/كلمة مرور
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_note'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $type = $_POST['type'];
    
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO notes (title, content, type, user_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $type, $user['id']]);
        $message = 'تم الحفظ بنجاح في المفكرة.';
    }
}

// حذف ملاحظة
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);
    header('Location: notebook.php');
    exit;
}

// جلب الملاحظات
$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags('المفكرة الذكية', 'احفظ روابطك، كلمات مرورك، وملاحظاتك الهامة في مكان واحد آمن.', 'مفكرة, روابط, كلمات مرور, ملاحظات'); ?>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .note-card { border-right: 5px solid var(--primary); }
        .note-type { font-size: 0.75rem; padding: 2px 8px; border-radius: 10px; background: var(--primary-light); color: var(--primary); }
        .type-url { border-right-color: var(--success); }
        .type-password { border-right-color: var(--danger); }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-inner">
            <a href="index.php" class="brand">📁 عودة للملفات</a>
            <div class="user-menu">
                <span>مفكرتي الشخصية</span>
                <a href="actions/auth.php?action=logout" class="btn btn-primary" style="margin-right: 10px;">خروج</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="main-layout">
            <div class="content-area">
                <?php if ($message): ?>
                    <div class="card" style="background: #d1fae5; color: #065f46;"><?php echo $message; ?></div>
                <?php endif; ?>

                <div class="card">
                    <h2 class="card-title">إضافة للمفكرة</h2>
                    <form method="POST">
                        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">
                            <div class="form-group">
                                <label class="form-label">العنوان / الاسم</label>
                                <input type="text" name="title" class="form-input" placeholder="مثال: رابط Manus، كلمة مرور الفيسبوك" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">النوع</label>
                                <select name="type" class="form-input">
                                    <option value="note">ملاحظة عامة</option>
                                    <option value="url">رابط URL</option>
                                    <option value="password">كلمة مرور</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">المحتوى</label>
                            <textarea name="content" class="form-input" style="height: 100px;" placeholder="أدخل الرابط أو الملاحظة هنا..."></textarea>
                        </div>
                        <button type="submit" name="add_note" class="btn btn-primary">حفظ في المفكرة</button>
                    </form>
                </div>

                <div class="grid-items">
                    <?php foreach ($notes as $n): ?>
                        <div class="card note-card type-<?php echo $n['type']; ?>">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <span class="note-type"><?php echo $n['type'] == 'url' ? 'رابط' : ($n['type'] == 'password' ? 'كلمة مرور' : 'ملاحظة'); ?></span>
                                <a href="?delete=<?php echo $n['id']; ?>" style="color: var(--danger); font-size: 0.8rem;" onclick="return confirm('هل أنت متأكد؟')">حذف</a>
                            </div>
                            <h3 style="font-size: 1rem; margin-bottom: 10px;"><?php echo htmlspecialchars($n['title']); ?></h3>
                            <div style="background: var(--bg-body); padding: 10px; border-radius: 8px; font-family: monospace; font-size: 0.9rem; word-break: break-all;">
                                <?php if ($n['type'] == 'url'): ?>
                                    <a href="<?php echo htmlspecialchars($n['content']); ?>" target="_blank" style="color: var(--primary);"><?php echo htmlspecialchars($n['content']); ?></a>
                                <?php else: ?>
                                    <?php echo nl2br(htmlspecialchars($n['content'])); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="sidebar">
                <div class="ad-sidebar">
                    <p>📢 مساحة إعلانية</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

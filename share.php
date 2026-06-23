 <?php
require_once 'config.php';

if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$user = getCurrentUser();
$file_id = isset($_GET['file']) ? (int)$_GET['file'] : 0;
$folder_id = isset($_GET['folder']) ? (int)$_GET['folder'] : 0;
$message = '';
$error = '';

// التحقق من وجود الملف أو المجلد
$item = null;
$item_type = '';

if ($file_id) {
    $stmt = $pdo->prepare("SELECT * FROM files WHERE id = ? AND user_id = ?");
    $stmt->execute([$file_id, $user['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    $item_type = 'file';
} elseif ($folder_id) {
    $stmt = $pdo->prepare("SELECT * FROM folders WHERE id = ? AND user_id = ?");
    $stmt->execute([$folder_id, $user['id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    $item_type = 'folder';
}

if (!$item) {
    die('العنصر غير موجود أو ليس لديك صلاحية للوصول إليه');
}

// معالجة إنشاء رابط مشاركة
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_share'])) {
    $share_token = generateShareToken();
    $is_public = isset($_POST['is_public']) ? 1 : 0;
    $expires_at = !empty($_POST['expires_at']) ? $_POST['expires_at'] : null;
    
    $stmt = $pdo->prepare("INSERT INTO shares (file_id, folder_id, shared_by, share_token, is_public, expires_at) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($stmt->execute([$file_id ?: null, $folder_id ?: null, $user['id'], $share_token, $is_public, $expires_at])) {
        $message = 'تم إنشاء رابط المشاركة بنجاح';
    } else {
        $error = 'حدث خطأ أثناء إنشاء رابط المشاركة';
    }
}

// معالجة المشاركة مع مستخدم محدد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['share_with_user'])) {
    $shared_with_email = trim($_POST['shared_with_email']);
    
    if (!empty($shared_with_email)) {
        // البحث عن المستخدم
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$shared_with_email]);
        $shared_user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($shared_user) {
            $share_token = generateShareToken();
            $stmt = $pdo->prepare("INSERT INTO shares (file_id, folder_id, shared_by, shared_with, share_token) VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$file_id ?: null, $folder_id ?: null, $user['id'], $shared_user['id'], $share_token])) {
                $message = 'تم مشاركة العنصر مع المستخدم بنجاح';
            } else {
                $error = 'حدث خطأ أثناء المشاركة';
            }
        } else {
            $error = 'المستخدم غير موجود';
        }
    }
}

// جلب روابط المشاركة الحالية
$shares_query = "SELECT s.*, u.username, u.email FROM shares s 
                 LEFT JOIN users u ON s.shared_with = u.id 
                 WHERE s.shared_by = ? AND ";
$shares_query .= $file_id ? "s.file_id = ?" : "s.folder_id = ?";
$shares_query .= " ORDER BY s.created_at DESC";

$stmt = $pdo->prepare($shares_query);
$stmt->execute([$user['id'], $file_id ?: $folder_id]);
$shares = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مشاركة - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <h1>📁 <?php echo SITE_NAME; ?></h1>
            </div>
            <div class="user-info">
                <span>مرحباً، <?php echo htmlspecialchars($user['username']); ?></span>
                <a href="index.php" class="btn btn-small">العودة للرئيسية</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="share-section">
            <h2>مشاركة: <?php echo htmlspecialchars($item_type == 'file' ? $item['original_name'] : $item['name']); ?></h2>
            
            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="share-forms">
                <!-- إنشاء رابط مشاركة عام -->
                <div class="form-container">
                    <h3>إنشاء رابط مشاركة</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="is_public" value="1">
                                رابط عام (يمكن لأي شخص الوصول إليه)
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label for="expires_at">تاريخ انتهاء الصلاحية (اختياري):</label>
                            <input type="datetime-local" id="expires_at" name="expires_at">
                        </div>
                        
                        <button type="submit" name="create_share" class="btn btn-primary">إنشاء رابط المشاركة</button>
                    </form>
                </div>
                
                <!-- مشاركة مع مستخدم محدد -->
                <div class="form-container">
                    <h3>مشاركة مع مستخدم</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="shared_with_email">البريد الإلكتروني للمستخدم:</label>
                            <input type="email" id="shared_with_email" name="shared_with_email" required>
                        </div>
                        
                        <button type="submit" name="share_with_user" class="btn btn-primary">مشاركة</button>
                    </form>
                </div>
            </div>
            
            <!-- عرض روابط المشاركة الحالية -->
            <?php if (!empty($shares)): ?>
                <div class="shares-list">
                    <h3>روابط المشاركة الحالية</h3>
                    
                    <?php foreach ($shares as $share): ?>
                        <div class="share-item">
                            <div class="share-info">
                                <?php if ($share['shared_with']): ?>
                                    <p><strong>مشارك مع:</strong> <?php echo htmlspecialchars($share['email']); ?></p>
                                <?php else: ?>
                                    <p><strong>نوع المشاركة:</strong> <?php echo $share['is_public'] ? 'رابط عام' : 'رابط خاص'; ?></p>
                                <?php endif; ?>
                                
                                <p><strong>الرابط:</strong></p>
                                <div class="share-link">
                                    <input type="text" value="<?php echo $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . '/view.php?token=' . $share['share_token']; ?>" readonly>
                                    <button onclick="copyToClipboard(this)" class="btn btn-small">نسخ</button>
                                </div>
                                
                                <?php if ($share['expires_at']): ?>
                                    <p><strong>ينتهي في:</strong> <?php echo date('Y-m-d H:i', strtotime($share['expires_at'])); ?></p>
                                <?php endif; ?>
                                
                                <p><strong>تم الإنشاء:</strong> <?php echo date('Y-m-d H:i', strtotime($share['created_at'])); ?></p>
                            </div>
                            
                            <div class="share-actions">
                                <a href="delete_share.php?id=<?php echo $share['id']; ?>" class="btn btn-small btn-danger" onclick="return confirm('هل أنت متأكد من حذف هذه المشاركة؟')">حذف</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function copyToClipboard(button) {
            const input = button.previousElementSibling;
            input.select();
            document.execCommand('copy');
            
            const originalText = button.textContent;
            button.textContent = 'تم النسخ!';
            setTimeout(() => {
                button.textContent = originalText;
            }, 2000);
        }
    </script>
</body>
</html>

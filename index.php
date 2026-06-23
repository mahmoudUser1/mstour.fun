 <?php
require_once 'config.php';

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$user = getCurrentUser();
$current_folder = isset($_GET['folder']) ? (int)$_GET['folder'] : null;
$message = '';
$error = '';

// معالجة رفع الملفات
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
    $upload_dir = UPLOAD_DIR . $user['id'] . '/';
    if ($current_folder) {
        $upload_dir .= $current_folder . '/';
    }
    
    // إنشاء المجلد إذا لم يكن موجوداً
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    $uploaded_files = [];
    $files = $_FILES['files'];
    
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($files['error'][$i] == UPLOAD_ERR_OK) {
            $original_name = $files['name'][$i];
            $tmp_name = $files['tmp_name'][$i];
            $file_size = $files['size'][$i];
            $mime_type = $files['type'][$i];
            
            // التحقق من حجم الملف
            if ($file_size > MAX_FILE_SIZE) {
                $error .= "الملف {$original_name} كبير جداً. ";
                continue;
            }
            
            // إنشاء اسم فريد للملف
            $file_extension = pathinfo($original_name, PATHINFO_EXTENSION);
            $unique_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $unique_name;
            
            if (move_uploaded_file($tmp_name, $file_path)) {
                // حفظ معلومات الملف في قاعدة البيانات
                $stmt = $pdo->prepare("INSERT INTO files (name, original_name, file_path, file_size, mime_type, folder_id, user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$unique_name, $original_name, $file_path, $file_size, $mime_type, $current_folder, $user['id']]);
                $uploaded_files[] = $original_name;
            }
        }
    }
    
    if (!empty($uploaded_files)) {
        $message = 'تم رفع الملفات بنجاح: ' . implode(', ', $uploaded_files);
    }
}

// معالجة إنشاء مجلد جديد
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_folder'])) {
    $folder_name = trim($_POST['folder_name']);
    
    if (!empty($folder_name)) {
        $stmt = $pdo->prepare("INSERT INTO folders (name, parent_id, user_id) VALUES (?, ?, ?)");
        if ($stmt->execute([$folder_name, $current_folder, $user['id']])) {
            $message = "تم إنشاء المجلد '{$folder_name}' بنجاح";
        } else {
            $error = 'حدث خطأ أثناء إنشاء المجلد';
        }
    }
}

// جلب المجلدات
$folders_query = "SELECT * FROM folders WHERE user_id = ? AND parent_id ";
$folders_query .= $current_folder ? "= ?" : "IS NULL";
$folders_query .= " ORDER BY name";

$stmt = $pdo->prepare($folders_query);
if ($current_folder) {
    $stmt->execute([$user['id'], $current_folder]);
} else {
    $stmt->execute([$user['id']]);
}
$folders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// جلب الملفات
$files_query = "SELECT * FROM files WHERE user_id = ? AND folder_id ";
$files_query .= $current_folder ? "= ?" : "IS NULL";
$files_query .= " ORDER BY original_name";

$stmt = $pdo->prepare($files_query);
if ($current_folder) {
    $stmt->execute([$user['id'], $current_folder]);
} else {
    $stmt->execute([$user['id']]);
}
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

// جلب معلومات المجلد الحالي
$current_folder_info = null;
if ($current_folder) {
    $stmt = $pdo->prepare("SELECT * FROM folders WHERE id = ? AND user_id = ?");
    $stmt->execute([$current_folder, $user['id']]);
    $current_folder_info = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <h1>📁 <?php echo SITE_NAME; ?></h1>
            </div>
            <div class="user-info">
                <span>مرحباً، <?php echo htmlspecialchars($user['username']); ?></span>
                <a href="auth.php?logout=1" class="logout-btn">تسجيل الخروج</a>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Upload Section -->
        <div class="upload-section">
            <h2>رفع الملفات</h2>
            
            <?php if ($message): ?>
                <div class="message success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="upload-forms">
                <!-- رفع الملفات -->
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="file-input-wrapper">
                        <input type="file" name="files[]" id="files" multiple required>
                        <label for="files">اختر الملفات</label>
                    </div>
                    <button type="submit" class="btn btn-primary">رفع الملفات</button>
                </form>
                
                <!-- إنشاء مجلد -->
                <form method="POST" class="folder-form">
                    <div class="form-group">
                        <input type="text" name="folder_name" placeholder="اسم المجلد الجديد" required>
                        <button type="submit" name="create_folder" class="btn btn-secondary">إنشاء مجلد</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Navigation -->
        <div class="navigation">
            <?php if ($current_folder_info): ?>
                <div class="breadcrumb">
                    <a href="index.php">الرئيسية</a>
                    <?php if ($current_folder_info['parent_id']): ?>
                        <span> / </span>
                        <a href="index.php?folder=<?php echo $current_folder_info['parent_id']; ?>">العودة</a>
                    <?php endif; ?>
                    <span> / </span>
                    <span class="current"><?php echo htmlspecialchars($current_folder_info['name']); ?></span>
                </div>
            <?php else: ?>
                <div class="breadcrumb">
                    <span class="current">الرئيسية</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Files and Folders Display -->
        <div class="files-section">
            <h2>الملفات والمجلدات</h2>
            
            <div class="files-grid">
                <!-- المجلدات -->
                <?php foreach ($folders as $folder): ?>
                    <div class="item-card folder-card">
                        <div class="item-icon">📁</div>
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($folder['name']); ?></h3>
                            <p>مجلد</p>
                            <div class="item-actions">
                                <a href="index.php?folder=<?php echo $folder['id']; ?>" class="btn btn-small">فتح</a>
                                <a href="share.php?folder=<?php echo $folder['id']; ?>" class="btn btn-small btn-share">مشاركة</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- الملفات -->
                <?php foreach ($files as $file): ?>
                    <div class="item-card file-card">
                        <div class="item-icon">📄</div>
                        <div class="item-info">
                            <h3><?php echo htmlspecialchars($file['original_name']); ?></h3>
                            <p><?php echo number_format($file['file_size'] / 1024, 2); ?> KB</p>
                            <div class="item-actions">
                                <a href="download.php?file=<?php echo $file['id']; ?>" class="btn btn-small">تحميل</a>
                                <a href="share.php?file=<?php echo $file['id']; ?>" class="btn btn-small btn-share">مشاركة</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($folders) && empty($files)): ?>
                    <div class="empty-state">
                        <p>لا توجد ملفات أو مجلدات بعد</p>
                        <p>ابدأ برفع ملفاتك أو إنشاء مجلد جديد</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

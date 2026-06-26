<?php
/**
 * ملف الوظائف المساعدة
 */

// دالة تنسيق حجم الملف
function formatFileSize($bytes) {
    $sizes = ['B', 'KB', 'MB', 'GB'];
    if ($bytes == 0) return '0 B';
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), 2) . ' ' . $sizes[$i];
}

// دالة تنسيق التاريخ
function formatDate($date, $lang = 'ar') {
    $timestamp = strtotime($date);
    if ($lang === 'ar') {
        return date('d/m/Y', $timestamp);
    }
    return date('Y-m-d', $timestamp);
}

// دالة توليد رمز التحقق
function generateVerificationCode($length = 6) {
    return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

// دالة التحقق من صحة البريد الإلكتروني
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// دالة التحقق من قوة كلمة المرور
function validatePassword($password) {
    if (strlen($password) < 8) return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[a-z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    return true;
}

// دالة تشفير كلمة المرور
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// دالة التحقق من كلمة المرور
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// دالة الحصول على معلومات المستخدم الحالي
function getCurrentUser() {
    global $pdo;
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

// دالة التحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// دالة التحقق من أن المستخدم أدمن
function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['is_admin'] == 1;
}

// دالة إعادة التوجيه
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// دالة الحصول على المساحة المستخدمة للمستخدم
function getUserStorageUsed($userId) {
    global $pdo;
    $stmt = $pdo->prepare('SELECT SUM(file_size) as total FROM files WHERE user_id = ?');
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

// دالة حساب نسبة المساحة المستخدمة
function getStoragePercentage($userId) {
    $used = getUserStorageUsed($userId);
    $total = STORAGE_LIMIT;
    return round(($used / $total) * 100, 2);
}

// دالة التحقق من امتداد الملف
function isAllowedFileType($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ALLOWED_FILE_TYPES);
}

// دالة إنشاء مجلد آمن
function createUploadDirectory($path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

// دالة حفظ الملف
function saveUploadedFile($file, $userId) {
    global $pdo;
    
    // التحقق من الملف
    if (!isAllowedFileType($file['name'])) {
        return ['success' => false, 'message' => t('invalidFileType')];
    }
    
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'message' => t('fileTooLarge')];
    }
    
    // التحقق من المساحة المتاحة
    $used = getUserStorageUsed($userId);
    if ($used + $file['size'] > STORAGE_LIMIT) {
        return ['success' => false, 'message' => t('storageLimit')];
    }
    
    // إنشاء مسار الملف
    $uploadDir = UPLOAD_DIR . $userId . '/';
    createUploadDirectory($uploadDir);
    
    $filename = uniqid() . '_' . basename($file['name']);
    $filepath = $uploadDir . $filename;
    
    // نقل الملف
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['success' => false, 'message' => t('fileUploadFailed')];
    }
    
    // حفظ في قاعدة البيانات
    $stmt = $pdo->prepare('
        INSERT INTO files (user_id, name, original_name, file_path, file_size, file_type, uploaded_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ');
    
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $stmt->execute([
        $userId,
        $filename,
        $file['name'],
        $filepath,
        $file['size'],
        $ext
    ]);
    
    return ['success' => true, 'message' => t('fileUploadedSuccessfully')];
}

// دالة حذف الملف
function deleteFile($fileId, $userId) {
    global $pdo;
    
    // الحصول على معلومات الملف
    $stmt = $pdo->prepare('SELECT * FROM files WHERE id = ? AND user_id = ?');
    $stmt->execute([$fileId, $userId]);
    $file = $stmt->fetch();
    
    if (!$file) {
        return ['success' => false, 'message' => t('fileNotFound')];
    }
    
    // حذف الملف من النظام
    if (file_exists($file['file_path'])) {
        unlink($file['file_path']);
    }
    
    // حذف من قاعدة البيانات
    $stmt = $pdo->prepare('DELETE FROM files WHERE id = ?');
    $stmt->execute([$fileId]);
    
    return ['success' => true, 'message' => t('fileDeletedSuccessfully')];
}

// دالة إنشاء مجلد
function createFolder($folderName, $userId, $parentId = null) {
    global $pdo;
    
    // التحقق من اسم المجلد
    if (empty($folderName) || strlen($folderName) > 255) {
        return ['success' => false, 'message' => t('invalidFolderName')];
    }
    
    // حفظ في قاعدة البيانات
    $stmt = $pdo->prepare('
        INSERT INTO folders (user_id, parent_id, name, created_at)
        VALUES (?, ?, ?, NOW())
    ');
    
    $stmt->execute([$userId, $parentId, $folderName]);
    
    return ['success' => true, 'message' => t('folderCreatedSuccessfully')];
}

// دالة الحصول على ملفات المستخدم
function getUserFiles($userId, $folderId = null) {
    global $pdo;
    
    $query = 'SELECT * FROM files WHERE user_id = ?';
    $params = [$userId];
    
    if ($folderId) {
        $query .= ' AND folder_id = ?';
        $params[] = $folderId;
    } else {
        $query .= ' AND folder_id IS NULL';
    }
    
    $query .= ' ORDER BY uploaded_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// دالة الحصول على مجلدات المستخدم
function getUserFolders($userId, $parentId = null) {
    global $pdo;
    
    $query = 'SELECT * FROM folders WHERE user_id = ?';
    $params = [$userId];
    
    if ($parentId) {
        $query .= ' AND parent_id = ?';
        $params[] = $parentId;
    } else {
        $query .= ' AND parent_id IS NULL';
    }
    
    $query .= ' ORDER BY created_at DESC';
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

// دالة الحصول على جميع المستخدمين (للأدمن)
function getAllUsers() {
    global $pdo;
    $stmt = $pdo->query('SELECT id, name, email, is_admin, created_at FROM users ORDER BY created_at DESC');
    return $stmt->fetchAll();
}

// دالة الحصول على إحصائيات المستخدمين (للأدمن)
function getStatistics() {
    global $pdo;
    
    $stats = [];
    
    // إجمالي المستخدمين
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM users');
    $stats['total_users'] = $stmt->fetch()['count'];
    
    // إجمالي الملفات
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM files');
    $stats['total_files'] = $stmt->fetch()['count'];
    
    // إجمالي المساحة المستخدمة
    $stmt = $pdo->query('SELECT SUM(file_size) as total FROM files');
    $result = $stmt->fetch();
    $stats['total_storage'] = $result['total'] ?? 0;
    
    return $stats;
}
?>

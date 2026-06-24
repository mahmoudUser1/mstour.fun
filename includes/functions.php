<?php
require_once 'config.php';

// التحقق من تسجيل الدخول
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// الحصول على بيانات المستخدم الحالي
function getCurrentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// التحقق من صلاحيات المسؤول
function isAdmin() {
    $user = getCurrentUser();
    return $user && $user['is_admin'];
}

// تنسيق حجم الملف
function formatSize($bytes) {
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
    return $bytes . ' bytes';
}

// جلب الإعلانات النشطة لموضع معين
function getActiveAds($position = 'sidebar') {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM advertisements WHERE is_active = TRUE AND position = ? ORDER BY created_at DESC");
    $stmt->execute([$position]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// دالة توليد الهيدر والـ Meta Tags ديناميكياً
function renderMetaTags($title = '', $description = '', $keywords = '') {
    $site_name = SITE_NAME;
    $final_title = $title ? "$title | $site_name" : $site_name;
    $final_desc = $description ? $description : "أفضل منصة لنقل ومشاركة الملفات والمجلدات بسهولة وأمان.";
    $final_keys = $keywords ? $keywords : "نقل ملفات, مشاركة ملفات, رفع ملفات, تخزين سحابي, Manus Transfer";
    
    $html = "<!-- SEO Meta Tags -->\n";
    $html .= "    <title>$final_title</title>\n";
    $html .= "    <meta name='description' content='$final_desc'>\n";
    $html .= "    <meta name='keywords' content='$final_keys'>\n";
    $html .= "    <meta name='author' content='Manus Agent'>\n";
    $html .= "    <meta property='og:title' content='$final_title'>\n";
    $html .= "    <meta property='og:description' content='$final_desc'>\n";
    $html .= "    <meta property='og:type' content='website'>\n";
    
    return $html;
}
?>

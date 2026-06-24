<?php
// نظام اللغات
$languages = [
    'ar' => [
        'site_name' => 'MS Tool',
        'home' => 'الرئيسية',
        'notebook' => 'المفكرة',
        'admin_panel' => 'لوحة المسؤول',
        'logout' => 'خروج',
        'login' => 'دخول',
        'register' => 'تسجيل جديد',
        'upload' => 'رفع ملفات',
        'create_folder' => 'إنشاء مجلد',
        'my_files' => 'ملفاتي',
        'folders' => 'المجلدات',
        'files' => 'الملفات',
        'no_files' => 'لا توجد ملفات حالياً',
        'search' => 'بحث...',
        'verify_email' => 'تحقق من البريد',
        'verification_code' => 'كود التحقق',
        'verify_btn' => 'تحقق الآن',
        'resend_code' => 'إعادة إرسال الكود',
        'add_to_notebook' => 'إضافة للمفكرة',
        'title' => 'العنوان',
        'content' => 'المحتوى',
        'type' => 'النوع',
        'save' => 'حفظ',
        'note' => 'ملاحظة',
        'url' => 'رابط',
        'password' => 'كلمة مرور',
        'download' => 'تحميل',
        'view' => 'عرض',
        'delete' => 'حذف',
        'dir' => 'rtl',
        'lang_name' => 'العربية'
    ],
    'en' => [
        'site_name' => 'Manus Transfer',
        'home' => 'Home',
        'notebook' => 'Notebook',
        'admin_panel' => 'Admin Panel',
        'logout' => 'Logout',
        'login' => 'Login',
        'register' => 'Register',
        'upload' => 'Upload Files',
        'create_folder' => 'New Folder',
        'my_files' => 'My Files',
        'folders' => 'Folders',
        'files' => 'Files',
        'no_files' => 'No files found',
        'search' => 'Search...',
        'verify_email' => 'Verify Email',
        'verification_code' => 'Verification Code',
        'verify_btn' => 'Verify Now',
        'resend_code' => 'Resend Code',
        'add_to_notebook' => 'Add to Notebook',
        'title' => 'Title',
        'content' => 'Content',
        'type' => 'Type',
        'save' => 'Save',
        'note' => 'Note',
        'url' => 'URL',
        'password' => 'Password',
        'download' => 'Download',
        'view' => 'View',
        'delete' => 'Delete',
        'dir' => 'ltr',
        'lang_name' => 'English'
    ]
];

// تحديد اللغة الحالية
if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'ar'; // الافتراضي عربي
}

if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $languages)) {
    $_SESSION['lang'] = $_GET['lang'];
}

$lang = $languages[$_SESSION['lang']];

// دالة الترجمة
function __($key) {
    global $lang;
    return $lang[$key] ?? $key;
}
?>

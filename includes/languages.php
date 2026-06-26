<?php
/**
 * ملف اللغات والترجمات
 */

$translations = [
    'ar' => [
        // الرأس والتنقل
        'appName' => 'MS Tour Fun',
        'home' => 'الرئيسية',
        'dashboard' => 'لوحة التحكم',
        'admin' => 'الإدارة',
        'profile' => 'الملف الشخصي',
        'logout' => 'تسجيل الخروج',
        'language' => 'اللغة',
        'settings' => 'الإعدادات',

        // التسجيل والدخول
        'register' => 'إنشاء حساب',
        'login' => 'تسجيل الدخول',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'confirmPassword' => 'تأكيد كلمة المرور',
        'name' => 'الاسم',
        'fullName' => 'الاسم الكامل',
        'signUp' => 'إنشاء حساب جديد',
        'signIn' => 'دخول',
        'dontHaveAccount' => 'ليس لديك حساب؟',
        'alreadyHaveAccount' => 'هل لديك حساب بالفعل؟',
        'welcome' => 'مرحباً',

        // الملف الشخصي
        'personalInfo' => 'المعلومات الشخصية',
        'changePassword' => 'تغيير كلمة المرور',
        'currentPassword' => 'كلمة المرور الحالية',
        'newPassword' => 'كلمة المرور الجديدة',
        'profileUpdatedSuccessfully' => 'تم تحديث الملف الشخصي بنجاح',

        // التحقق
        'verificationCode' => 'رمز التحقق',
        'enterVerificationCode' => 'أدخل رمز التحقق المكون من 6 أرقام',
        'verificationCodeSent' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني',
        'resendCode' => 'إعادة إرسال الرمز',
        'verifyEmail' => 'التحقق من البريد الإلكتروني',
        'verify' => 'تحقق',
        'emailVerifiedSuccessfully' => 'تم التحقق من البريد الإلكتروني بنجاح',

        // الملفات والمجلدات
        'files' => 'الملفات',
        'folders' => 'المجلدات',
        'myFiles' => 'ملفاتي',
        'upload' => 'رفع ملف',
        'uploadFiles' => 'رفع ملفات',
        'createFolder' => 'إنشاء مجلد',
        'folderName' => 'اسم المجلد',
        'fileName' => 'اسم الملف',
        'fileSize' => 'حجم الملف',
        'uploadDate' => 'تاريخ الرفع',
        'actions' => 'الإجراءات',
        'download' => 'تحميل',
        'delete' => 'حذف',
        'rename' => 'إعادة تسمية',
        'view' => 'عرض',
        'noFiles' => 'لا توجد ملفات',
        'noFolders' => 'لا توجد مجلدات',

        // المساحة التخزينية
        'storage' => 'المساحة التخزينية',
        'usedStorage' => 'المساحة المستخدمة',
        'availableStorage' => 'المساحة المتاحة',
        'totalStorage' => 'إجمالي المساحة',
        'storageUsage' => 'استخدام المساحة',

        // لوحة التحكم
        'adminPanel' => 'لوحة التحكم',
        'users' => 'المستخدمون',
        'statistics' => 'الإحصائيات',
        'totalUsers' => 'إجمالي المستخدمين',
        'totalFiles' => 'إجمالي الملفات',
        'userList' => 'قائمة المستخدمين',
        'userName' => 'اسم المستخدم',
        'userEmail' => 'بريد المستخدم',
        'userStorage' => 'مساحة المستخدم',
        'joinDate' => 'تاريخ الانضمام',
        'lastLogin' => 'آخر دخول',

        // الفوتر والروابط
        'links' => 'روابط سريعة',
        'about' => 'عن الموقع',
        'contact' => 'اتصل بنا',
        'allRightsReserved' => 'جميع الحقوق محفوظة',

        // الرسائل
        'success' => 'نجح',
        'error' => 'خطأ',
        'warning' => 'تحذير',
        'info' => 'معلومة',
        'confirm' => 'تأكيد',
        'cancel' => 'إلغاء',
        'save' => 'حفظ',
        'submit' => 'إرسال',
        'ok' => 'حسناً',
        'yes' => 'نعم',
        'no' => 'لا',

        // رسائل النجاح
        'fileUploadedSuccessfully' => 'تم رفع الملف بنجاح',
        'folderCreatedSuccessfully' => 'تم إنشاء المجلد بنجاح',
        'fileDeletedSuccessfully' => 'تم حذف الملف بنجاح',
        'passwordChangedSuccessfully' => 'تم تغيير كلمة المرور بنجاح',

        // رسائل الأخطاء
        'fileUploadFailed' => 'فشل رفع الملف',
        'invalidEmail' => 'البريد الإلكتروني غير صحيح',
        'passwordTooShort' => 'كلمة المرور قصيرة جداً',
        'passwordRequirements' => 'يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل، حرف كبير، حرف صغير ورقم.',
        'passwordsDoNotMatch' => 'كلمات المرور غير متطابقة',
        'emailAlreadyExists' => 'البريد الإلكتروني موجود بالفعل',
        'invalidCredentials' => 'بيانات الدخول غير صحيحة',
        'emailNotVerified' => 'البريد الإلكتروني غير مفعل، يرجى تفعيله أولاً.',
        'invalidCode' => 'الرمز غير صحيح',
        'tooManyAttempts' => 'عدد محاولات كثير جداً',
        'required' => 'مطلوب',
        'invalidFileType' => 'نوع الملف غير مسموح به',
        'fileTooLarge' => 'حجم الملف كبير جداً',
        'storageLimit' => 'لقد تجاوزت المساحة التخزينية المتاحة لك',
        'fileNotFound' => 'الملف غير موجود',
        'invalidFolderName' => 'اسم المجلد غير صحيح',
    ],
    'en' => [
        // Header and Navigation
        'appName' => 'MS Tour Fun',
        'home' => 'Home',
        'dashboard' => 'Dashboard',
        'admin' => 'Admin',
        'profile' => 'Profile',
        'logout' => 'Logout',
        'language' => 'Language',
        'settings' => 'Settings',

        // Registration and Login
        'register' => 'Register',
        'login' => 'Login',
        'email' => 'Email',
        'password' => 'Password',
        'confirmPassword' => 'Confirm Password',
        'name' => 'Name',
        'fullName' => 'Full Name',
        'signUp' => 'Sign Up',
        'signIn' => 'Sign In',
        'dontHaveAccount' => "Don't have an account?",
        'alreadyHaveAccount' => 'Already have an account?',
        'welcome' => 'Welcome',

        // Profile
        'personalInfo' => 'Personal Information',
        'changePassword' => 'Change Password',
        'currentPassword' => 'Current Password',
        'newPassword' => 'New Password',
        'profileUpdatedSuccessfully' => 'Profile updated successfully',

        // Verification
        'verificationCode' => 'Verification Code',
        'enterVerificationCode' => 'Enter the 6-digit verification code',
        'verificationCodeSent' => 'Verification code has been sent to your email',
        'resendCode' => 'Resend Code',
        'verifyEmail' => 'Verify Email',
        'verify' => 'Verify',
        'emailVerifiedSuccessfully' => 'Email verified successfully',

        // Files and Folders
        'files' => 'Files',
        'folders' => 'Folders',
        'myFiles' => 'My Files',
        'upload' => 'Upload',
        'uploadFiles' => 'Upload Files',
        'createFolder' => 'Create Folder',
        'folderName' => 'Folder Name',
        'fileName' => 'File Name',
        'fileSize' => 'File Size',
        'uploadDate' => 'Upload Date',
        'actions' => 'Actions',
        'download' => 'Download',
        'delete' => 'Delete',
        'rename' => 'Rename',
        'view' => 'View',
        'noFiles' => 'No files',
        'noFolders' => 'No folders',

        // Storage
        'storage' => 'Storage',
        'usedStorage' => 'Used Storage',
        'availableStorage' => 'Available Storage',
        'totalStorage' => 'Total Storage',
        'storageUsage' => 'Storage Usage',

        // Admin Panel
        'adminPanel' => 'Admin Panel',
        'users' => 'Users',
        'statistics' => 'Statistics',
        'totalUsers' => 'Total Users',
        'totalFiles' => 'Total Files',
        'userList' => 'User List',
        'userName' => 'Username',
        'userEmail' => 'User Email',
        'userStorage' => 'User Storage',
        'joinDate' => 'Join Date',
        'lastLogin' => 'Last Login',

        // Footer and Links
        'links' => 'Quick Links',
        'about' => 'About Us',
        'contact' => 'Contact Us',
        'allRightsReserved' => 'All Rights Reserved',

        // Messages
        'success' => 'Success',
        'error' => 'Error',
        'warning' => 'Warning',
        'info' => 'Information',
        'confirm' => 'Confirm',
        'cancel' => 'Cancel',
        'save' => 'Save',
        'submit' => 'Submit',
        'ok' => 'OK',
        'yes' => 'Yes',
        'no' => 'No',

        // Success Messages
        'fileUploadedSuccessfully' => 'File uploaded successfully',
        'folderCreatedSuccessfully' => 'Folder created successfully',
        'fileDeletedSuccessfully' => 'File deleted successfully',
        'passwordChangedSuccessfully' => 'Password changed successfully',

        // Error Messages
        'fileUploadFailed' => 'File upload failed',
        'invalidEmail' => 'Invalid email address',
        'passwordTooShort' => 'Password is too short',
        'passwordRequirements' => 'Password must be at least 8 characters long and contain uppercase, lowercase and numbers.',
        'passwordsDoNotMatch' => 'Passwords do not match',
        'emailAlreadyExists' => 'Email already exists',
        'invalidCredentials' => 'Invalid credentials',
        'emailNotVerified' => 'Email not verified, please verify first.',
        'invalidCode' => 'Invalid code',
        'tooManyAttempts' => 'Too many attempts',
        'required' => 'Required',
        'invalidFileType' => 'Invalid file type',
        'fileTooLarge' => 'File is too large',
        'storageLimit' => 'You have exceeded your storage limit',
        'fileNotFound' => 'File not found',
        'invalidFolderName' => 'Invalid folder name',
    ]
];

// دالة الحصول على النص المترجم
function t($key, $lang = null) {
    global $translations;
    $lang = $lang ?? getCurrentLanguage();
    return $translations[$lang][$key] ?? $key;
}
?>

<?php
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags('تسجيل الدخول', 'قم بتسجيل الدخول للوصول إلى ملفاتك ومشاركتها.', 'دخول, تسجيل, حساب جديد'); ?>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #5c67f2 0%, #818cf8 100%); }
        .auth-card { width: 100%; max-width: 400px; background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
        .auth-tabs { display: flex; margin-bottom: 30px; border-bottom: 1px solid var(--border); }
        .auth-tab { flex: 1; text-align: center; padding: 10px; cursor: pointer; font-weight: 600; color: var(--text-muted); }
        .auth-tab.active { color: var(--primary); border-bottom: 2px solid var(--primary); }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h1 style="text-align: center; color: var(--primary); margin-bottom: 30px;">📁 <?php echo SITE_NAME; ?></h1>
            
            <?php if ($error): ?>
                <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:8px; margin-bottom:20px; font-size:0.9rem;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div style="background:#d1fae5; color:#065f46; padding:10px; border-radius:8px; margin-bottom:20px; font-size:0.9rem;">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <div id="loginForm">
                <form action="actions/auth.php" method="POST">
                    <input type="hidden" name="login" value="1">
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">دخول</button>
                </form>
                <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
                    ليس لديك حساب؟ <a href="javascript:void(0)" onclick="toggleAuth()" style="color: var(--primary);">سجل الآن</a>
                </p>
            </div>

            <div id="registerForm" style="display: none;">
                <form action="actions/auth.php" method="POST">
                    <input type="hidden" name="register" value="1">
                    <div class="form-group">
                        <label class="form-label">اسم المستخدم</label>
                        <input type="text" name="username" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">إنشاء حساب</button>
                </form>
                <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
                    لديك حساب بالفعل؟ <a href="javascript:void(0)" onclick="toggleAuth()" style="color: var(--primary);">سجل دخول</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function toggleAuth() {
            const login = document.getElementById('loginForm');
            const register = document.getElementById('registerForm');
            if (login.style.display === 'none') {
                login.style.display = 'block';
                register.style.display = 'none';
            } else {
                login.style.display = 'none';
                register.style.display = 'block';
            }
        }
    </script>
</body>
</html>

 <?php
require_once 'config.php';

$message = '';
$error = '';

// معالجة تسجيل الدخول
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'يرجى ملء جميع الحقول';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
        }
    }
}

// معالجة التسجيل
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'يرجى ملء جميع الحقول';
    } elseif ($password !== $confirm_password) {
        $error = 'كلمات المرور غير متطابقة';
    } elseif (strlen($password) < 6) {
        $error = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    } else {
        // التحقق من عدم وجود المستخدم مسبقاً
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        
        if ($stmt->fetch()) {
            $error = 'البريد الإلكتروني أو اسم المستخدم موجود مسبقاً';
        } else {
            // إنشاء المستخدم الجديد
            $hashed_password = hashPassword($password);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $message = 'تم إنشاء الحساب بنجاح. يمكنك الآن تسجيل الدخول.';
            } else {
                $error = 'حدث خطأ أثناء إنشاء الحساب';
            }
        }
    }
}

// تسجيل الخروج
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: auth.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h1><?php echo SITE_NAME; ?></h1>
            <p>منصة مشاركة الملفات والمجلدات</p>
        </div>
        
        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="auth-forms">
            <!-- نموذج تسجيل الدخول -->
            <div class="form-container" id="login-form">
                <h2>تسجيل الدخول</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">كلمة المرور:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" name="login" class="btn btn-primary">تسجيل الدخول</button>
                </form>
                
                <p class="switch-form">
                    ليس لديك حساب؟ <a href="#" onclick="showRegisterForm()">إنشاء حساب جديد</a>
                </p>
            </div>
            
            <!-- نموذج التسجيل -->
            <div class="form-container" id="register-form" style="display: none;">
                <h2>إنشاء حساب جديد</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="reg-username">اسم المستخدم:</label>
                        <input type="text" id="reg-username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg-email">البريد الإلكتروني:</label>
                        <input type="email" id="reg-email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg-password">كلمة المرور:</label>
                        <input type="password" id="reg-password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm-password">تأكيد كلمة المرور:</label>
                        <input type="password" id="confirm-password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" name="register" class="btn btn-primary">إنشاء الحساب</button>
                </form>
                
                <p class="switch-form">
                    لديك حساب بالفعل؟ <a href="#" onclick="showLoginForm()">تسجيل الدخول</a>
                </p>
            </div>
        </div>
    </div>
    
    <script>
        function showRegisterForm() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('register-form').style.display = 'block';
        }
        
        function showLoginForm() {
            document.getElementById('register-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        }
    </script>
</body>
</html>

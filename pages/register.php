<?php
require_once __DIR__ . '/../includes/header.php';

// إذا كان المستخدم مسجل دخول بالفعل
if (isLoggedIn()) {
    redirect(SITE_URL . '/pages/dashboard.php');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // التحقق من البيانات
    if (empty($name)) {
        $errors['name'] = t('required');
    }
    
    if (empty($email)) {
        $errors['email'] = t('required');
    } elseif (!validateEmail($email)) {
        $errors['email'] = t('invalidEmail');
    }
    
    if (empty($password)) {
        $errors['password'] = t('required');
    } elseif (!validatePassword($password)) {
        $errors['password'] = t('passwordTooShort');
    }
    
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = t('passwordsDoNotMatch');
    }
    
    // التحقق من عدم وجود البريد الإلكتروني
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = t('emailAlreadyExists');
        }
    }
    
    // إنشاء الحساب
    if (empty($errors)) {
        $verification_code = generateVerificationCode();
        $hashed_password = hashPassword($password);
        
        $stmt = $pdo->prepare('
            INSERT INTO users (name, email, password, verification_code, verification_code_expires_at, created_at)
            VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW())
        ');
        
        if ($stmt->execute([$name, $email, $hashed_password, $verification_code])) {
            // إرسال بريد التحقق
            $subject = t('verifyEmail') . " - " . SITE_NAME;
            $message = "
                <h2>" . t('welcome') . " " . htmlspecialchars($name) . "</h2>
                <p>" . t('verificationCodeSent') . "</p>
                <h1 style='background: #f4f4f4; padding: 10px; text-align: center;'>" . $verification_code . "</h1>
                <p>" . t('enterVerificationCode') . "</p>
            ";
            
            sendEmail($email, $subject, $message);
            
            // حفظ البريد الإلكتروني والرمز في الجلسة للتحقق
            $_SESSION['verification_email'] = $email;
            $_SESSION['verification_code'] = $verification_code;
            
            // إعادة التوجيه إلى صفحة التحقق
            redirect(SITE_URL . '/pages/verification.php');
        } else {
            $errors['general'] = "حدث خطأ أثناء إنشاء الحساب.";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4"><?php echo t('signUp'); ?></h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label"><?php echo t('fullName'); ?></label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label"><?php echo t('email'); ?></label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label"><?php echo t('password'); ?></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <small class="text-muted d-block mt-2">
                            <?php echo t('passwordRequirements'); ?>
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label"><?php echo t('confirmPassword'); ?></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3"><?php echo t('signUp'); ?></button>
                </form>
                
                <p class="text-center">
                    <?php echo t('alreadyHaveAccount'); ?> 
                    <a href="<?php echo SITE_URL; ?>/pages/login.php"><?php echo t('signIn'); ?></a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

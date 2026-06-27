<?php
require_once __DIR__ . '/../includes/header.php';

// التحقق من وجود البريد الإلكتروني في الجلسة
if (!isset($_SESSION['verification_email'])) {
    redirect(SITE_URL . '/pages/register.php');
}

$errors = [];
$success = false;
$email = $_SESSION['verification_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');
    
    if (empty($code)) {
        $errors['code'] = t('required');
    } elseif ($code !== $_SESSION['verification_code']) {
        $errors['code'] = t('invalidCode');
    }
    
    if (empty($errors)) {
        // تحديث حالة المستخدم
        $stmt = $pdo->prepare('UPDATE users SET is_verified = 1 WHERE email = ?');
        $stmt->execute([$email]);
        
        // مسح الجلسة
        unset($_SESSION['verification_email']);
        unset($_SESSION['verification_code']);
        
        // إعادة التوجيه إلى صفحة الدخول
        $_SESSION['message'] = t('emailVerifiedSuccessfully');
        redirect(SITE_URL . '/pages/login.php');
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4"><?php echo t('verifyEmail'); ?></h2>
                
                <p class="text-center text-muted mb-4">
                    <?php echo t('verificationCodeSent'); ?><br>
                    <strong><?php echo htmlspecialchars($email); ?></strong>
                </p>
                
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
                        <label for="code" class="form-label"><?php echo t('verificationCode'); ?></label>
                        <input type="text" class="form-control text-center" id="code" name="code" 
                               maxlength="6" placeholder="000000" value="<?php echo htmlspecialchars($_POST['code'] ?? ''); ?>" required>
                        <small class="text-muted d-block mt-2"><?php echo t('enterVerificationCode'); ?></small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3"><?php echo t('verify'); ?></button>
                </form>
                
                <p class="text-center">
                    <a href="<?php echo SITE_URL; ?>/pages/register.php"><?php echo t('resendCode'); ?></a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

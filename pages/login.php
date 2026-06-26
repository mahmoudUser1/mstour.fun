<?php
require_once __DIR__ . '/../includes/header.php';

// إذا كان المستخدم مسجل دخول بالفعل
if (isLoggedIn()) {
    redirect(SITE_URL . '/pages/dashboard.php');
}

$errors = [];
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // التحقق من البيانات
    if (empty($email)) {
        $errors['email'] = t('required');
    }
    
    if (empty($password)) {
        $errors['password'] = t('required');
    }
    
    // البحث عن المستخدم
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if (!$user || !verifyPassword($password, $user['password'])) {
            $errors['general'] = t('invalidCredentials');
        } elseif (!$user['is_verified']) {
            $errors['general'] = t('emailNotVerified');
        } else {
            // تسجيل الدخول
            $_SESSION['user_id'] = $user['id'];
            
            // تحديث آخر دخول
            $stmt = $pdo->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
            $stmt->execute([$user['id']]);
            
            redirect(SITE_URL . '/pages/dashboard.php');
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg">
            <div class="card-body p-5">
                <h2 class="card-title text-center mb-4"><?php echo t('signIn'); ?></h2>
                
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
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
                        <label for="email" class="form-label"><?php echo t('email'); ?></label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label"><?php echo t('password'); ?></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 mb-3"><?php echo t('signIn'); ?></button>
                </form>
                
                <p class="text-center">
                    <?php echo t('dontHaveAccount'); ?> 
                    <a href="<?php echo SITE_URL; ?>/pages/register.php"><?php echo t('signUp'); ?></a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
require_once 'includes/functions.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo __('dir'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags(__('login')); ?>
    <style>
        .auth-bg { background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%); min-height: 100vh; }
        .auth-card { border: none; border-radius: 20px; box-shadow: 0 1rem 3rem rgba(0,0,0,0.175); }
    </style>
</head>
<body class="auth-bg d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4 text-white">
                    <img src="assets/img/logo.png" alt="Logo" height="80" class="mb-3">
                    <h1 class="fw-bold"><?php echo __('site_name'); ?></h1>
                    <p class="opacity-75">إدارة ملفاتك لم تكن بهذه السهولة من قبل</p>
                </div>
                
                <div class="card auth-card overflow-hidden">
                    <div class="card-header bg-white p-0">
                        <ul class="nav nav-pills nav-fill" id="authTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active py-3 rounded-0 fw-bold" data-bs-toggle="tab" data-bs-target="#login"><?php echo __('login'); ?></button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link py-3 rounded-0 fw-bold" data-bs-toggle="tab" data-bs-target="#register"><?php echo __('register'); ?></button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4 p-lg-5">
                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div><?php echo $error; ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="tab-content">
                            <!-- Login Form -->
                            <div class="tab-pane fade show active" id="login">
                                <form action="actions/auth.php" method="POST">
                                    <input type="hidden" name="login" value="1">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase">البريد الإلكتروني</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                                            <input type="email" name="email" class="form-control bg-light border-0" placeholder="name@example.com" required>
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-uppercase">كلمة المرور</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                            <input type="password" name="password" class="form-control bg-light border-0" placeholder="••••••••" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                                        <i class="fas fa-sign-in-alt me-2"></i> دخول للنظام
                                    </button>
                                </form>
                            </div>

                            <!-- Register Form -->
                            <div class="tab-pane fade" id="register">
                                <form action="actions/auth.php" method="POST">
                                    <input type="hidden" name="register" value="1">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase">اسم المستخدم</label>
                                        <input type="text" name="username" class="form-control bg-light border-0" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-uppercase">البريد الإلكتروني</label>
                                        <input type="email" name="email" class="form-control bg-light border-0" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-uppercase">كلمة المرور</label>
                                        <input type="password" name="password" class="form-control bg-light border-0" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 py-3 fw-bold shadow-sm">
                                        <i class="fas fa-user-plus me-2"></i> إنشاء حساب جديد
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="?lang=ar" class="text-white-50 text-decoration-none me-3">العربية</a>
                    <a href="?lang=en" class="text-white-50 text-decoration-none">English</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once __DIR__ . '/../includes/header.php';

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    redirect(SITE_URL . '/pages/login.php');
}

$user = getCurrentUser();
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        
        if (empty($name) || !validateEmail($email)) {
            $error = "الرجاء إدخال بيانات صحيحة.";
        } else {
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
            $stmt->execute([$name, $email, $user['id']]);
            $success = "تم تحديث البيانات بنجاح.";
            $user = getCurrentUser(); // تحديث بيانات المستخدم في المتغير
        }
    } elseif (isset($_POST['change_password'])) {
        $old_pass = $_POST['old_password'] ?? '';
        $new_pass = $_POST['new_password'] ?? '';
        $confirm_pass = $_POST['confirm_password'] ?? '';
        
        if (!verifyPassword($old_pass, $user['password'])) {
            $error = "كلمة المرور القديمة غير صحيحة.";
        } elseif ($new_pass !== $confirm_pass) {
            $error = "كلمات المرور الجديدة غير متطابقة.";
        } elseif (!validatePassword($new_pass)) {
            $error = "كلمة المرور الجديدة ضعيفة (يجب أن تحتوي على 8 أحرف على الأقل، وأرقام وحروف كبيرة وصغيرة).";
        } else {
            $hashed = hashPassword($new_pass);
            $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
            $stmt->execute([$hashed, $user['id']]);
            $success = "تم تغيير كلمة المرور بنجاح.";
        }
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active">الإعدادات العامة</a>
                <a href="<?php echo SITE_URL; ?>/pages/dashboard.php" class="list-group-item list-group-item-action">لوحة التحكم</a>
            </div>
        </div>
        <div class="col-md-9">
            <h2>إعدادات الحساب</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header">تحديث الملف الشخصي</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">حفظ التغييرات</button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">تغيير كلمة المرور</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">كلمة المرور القديمة</label>
                            <input type="password" name="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" name="change_password" class="btn btn-warning">تغيير كلمة المرور</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

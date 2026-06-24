<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$user = getCurrentUser();
if ($user['is_verified']) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    $code = trim($_POST['code']);
    
    if ($code === $user['verification_code']) {
        $stmt = $pdo->prepare("UPDATE users SET is_verified = TRUE, verification_code = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);
        header('Location: index.php?verified=1');
        exit;
    } else {
        $error = 'كود التحقق غير صحيح، يرجى المحاولة مرة أخرى.';
    }
}

if (isset($_GET['resend'])) {
    $new_code = sprintf("%06d", mt_rand(1, 999999));
    $stmt = $pdo->prepare("UPDATE users SET verification_code = ? WHERE id = ?");
    $stmt->execute([$new_code, $user['id']]);
    
    // إرسال الإيميل باستخدام دالة mail()
    $to = $user['email'];
    $subject = "كود التحقق الخاص بك - " . SITE_NAME;
    $message = "مرحباً " . $user['username'] . ",\n\nكود التحقق الخاص بك هو: " . $new_code . "\n\nشكراً لاستخدامك منصتنا.";
    $headers = "From: no-reply@" . $_SERVER['HTTP_HOST'];
    
    mail($to, $subject, $message, $headers);
    $success = 'تم إعادة إرسال الكود إلى بريدك الإلكتروني.';
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags('تأكيد الحساب', 'قم بتأكيد بريدك الإلكتروني لتفعيل حسابك.', 'تأكيد, تحقق, بريد إلكتروني'); ?>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .verify-card { max-width: 400px; margin: 100px auto; padding: 40px; text-align: center; }
        .code-input { font-size: 2rem; letter-spacing: 10px; text-align: center; width: 100%; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card verify-card">
            <h2 class="card-title">تأكيد الحساب</h2>
            <p>لقد أرسلنا كود تحقق مكون من 6 أرقام إلى بريدك الإلكتروني:</p>
            <p><strong><?php echo htmlspecialchars($user['email']); ?></strong></p>
            
            <?php if ($error): ?>
                <div style="color: var(--danger); margin-bottom: 20px;"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div style="color: var(--success); margin-bottom: 20px;"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <input type="text" name="code" class="form-input code-input" maxlength="6" placeholder="000000" required autofocus>
                </div>
                <button type="submit" name="verify" class="btn btn-primary" style="width: 100%;">تحقق الآن</button>
            </form>
            
            <p style="margin-top: 20px;">
                لم يصلك الكود؟ <a href="?resend=1" style="color: var(--primary);">إعادة الإرسال</a>
            </p>
        </div>
    </div>
</body>
</html>

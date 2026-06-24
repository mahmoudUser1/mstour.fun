<?php
require_once '../includes/functions.php';

$action = $_GET['action'] ?? '';

if ($action == 'logout') {
    session_destroy();
    header('Location: ../auth.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            if (!$user['is_verified']) {
                header('Location: ../verify.php');
            } else {
                header('Location: ../index.php');
            }
            exit;
        } else {
            $_SESSION['error'] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
            header('Location: ../auth.php');
            exit;
        }
    }
    
    if (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        
        if ($stmt->fetch()) {
            $_SESSION['error'] = 'المستخدم موجود مسبقاً';
            header('Location: ../auth.php');
            exit;
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $code = sprintf("%06d", mt_rand(1, 999999));
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, verification_code) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed, $code])) {
                $user_id = $pdo->lastInsertId();
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                
                // إرسال الإيميل باستخدام دالة mail()
                $to = $email;
                $subject = "كود التحقق الخاص بك - " . SITE_NAME;
                $message = "مرحباً " . $username . ",\n\nكود التحقق الخاص بك هو: " . $code . "\n\nشكراً لاستخدامك منصتنا.";
                $headers = "From: no-reply@" . $_SERVER['HTTP_HOST'];
                mail($to, $subject, $message, $headers);
                
                header('Location: ../verify.php');
                exit;
            }
        }
    }
}
?>

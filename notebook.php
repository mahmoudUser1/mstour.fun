<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$user = getCurrentUser();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_note'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $type = $_POST['type'];
    
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO notes (title, content, type, user_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $content, $type, $user['id']]);
        $message = 'تم الحفظ بنجاح في المفكرة.';
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $user['id']]);
    header('Location: notebook.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user['id']]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo __('dir'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags(__('notebook')); ?>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-folder-open me-2"></i> <?php echo __('site_name'); ?></a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-light btn-sm"><i class="fas fa-arrow-right me-1"></i> عودة للملفات</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold mb-4"><i class="fas fa-plus-circle text-primary me-2"></i> <?php echo __('add_to_notebook'); ?></h5>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted"><?php echo __('title'); ?></label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted"><?php echo __('type'); ?></label>
                                <select name="type" class="form-select">
                                    <option value="note">📝 <?php echo __('note'); ?></option>
                                    <option value="url">🔗 <?php echo __('url'); ?></option>
                                    <option value="password">🔑 <?php echo __('password'); ?></option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted"><?php echo __('content'); ?></label>
                                <textarea name="content" class="form-control" rows="4"></textarea>
                            </div>
                            <button type="submit" name="add_note" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="fas fa-save me-2"></i> <?php echo __('save'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="row g-3">
                    <?php foreach ($notes as $n): ?>
                        <div class="col-md-6">
                            <div class="card h-100 shadow-sm border-0 rounded-4 transition hover-shadow">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <?php 
                                            $icon = 'fa-sticky-note'; $color = 'primary';
                                            if($n['type'] == 'url') { $icon = 'fa-link'; $color = 'success'; }
                                            if($n['type'] == 'password') { $icon = 'fa-key'; $color = 'danger'; }
                                        ?>
                                        <div class="bg-<?php echo $color; ?> bg-opacity-10 p-2 rounded text-<?php echo $color; ?>">
                                            <i class="fas <?php echo $icon; ?> fa-lg"></i>
                                        </div>
                                        <a href="?delete=<?php echo $n['id']; ?>" class="text-danger opacity-50 hover-opacity-100" onclick="return confirm('حذف؟')">
                                            <i class="fas fa-times-circle"></i>
                                        </a>
                                    </div>
                                    <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($n['title']); ?></h6>
                                    <div class="bg-light p-3 rounded-3 small text-break font-monospace">
                                        <?php if ($n['type'] == 'url'): ?>
                                            <a href="<?php echo htmlspecialchars($n['content']); ?>" target="_blank" class="text-primary text-decoration-none">
                                                <?php echo htmlspecialchars($n['content']); ?> <i class="fas fa-external-link-alt ms-1 small"></i>
                                            </a>
                                        <?php else: ?>
                                            <?php echo nl2br(htmlspecialchars($n['content'])); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

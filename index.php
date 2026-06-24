<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    header('Location: auth.php');
    exit;
}

$user = getCurrentUser();
if (!$user['is_verified']) {
    header('Location: verify.php');
    exit;
}

$current_folder = isset($_GET['folder']) ? (int)$_GET['folder'] : null;

// جلب المجلدات
$stmt = $pdo->prepare("SELECT * FROM folders WHERE user_id = ? AND parent_id " . ($current_folder ? "= ?" : "IS NULL"));
$current_folder ? $stmt->execute([$user['id'], $current_folder]) : $stmt->execute([$user['id']]);
$folders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// جلب الملفات
$stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ? AND folder_id " . ($current_folder ? "= ?" : "IS NULL"));
$current_folder ? $stmt->execute([$user['id'], $current_folder]) : $stmt->execute([$user['id']]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="<?php echo $_SESSION['lang']; ?>" dir="<?php echo __('dir'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags(__('home')); ?>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="index.php">
                <img src="assets/img/logo.png" alt="Logo" height="40" class="me-2">
                <?php echo __('site_name'); ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="fas fa-home me-1"></i> <?php echo __('home'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="notebook.php"><i class="fas fa-book me-1"></i> <?php echo __('notebook'); ?></a>
                    </li>
                    <?php if ($user['is_admin']): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="admin/index.php"><i class="fas fa-user-shield me-1"></i> <?php echo __('admin_panel'); ?></a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle btn btn-outline-light btn-sm px-3" href="#" id="langDrop" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i> <?php echo __('lang_name'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="?lang=ar">العربية</a></li>
                            <li><a class="dropdown-item" href="?lang=en">English</a></li>
                        </ul>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-danger btn-sm px-3" href="actions/auth.php?action=logout">
                            <i class="fas fa-sign-out-alt me-1"></i> <?php echo __('logout'); ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                    <h4 class="mb-0"><i class="fas fa-hdd text-primary me-2"></i> <?php echo __('my_files'); ?></h4>
                    <div class="btn-group">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                            <i class="fas fa-upload me-1"></i> <?php echo __('upload'); ?>
                        </button>
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#folderModal">
                            <i class="fas fa-folder-plus me-1"></i> <?php echo __('create_folder'); ?>
                        </button>
                    </div>
                </div>

                <!-- المجلدات -->
                <div class="row g-3 mb-4">
                    <?php foreach ($folders as $f): ?>
                        <div class="col-md-3 col-6">
                            <div class="card h-100 shadow-sm hover-shadow transition">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-folder fa-3x text-warning mb-3"></i>
                                    <h6 class="card-title text-truncate mb-0">
                                        <a href="?folder=<?php echo $f['id']; ?>" class="text-dark text-decoration-none stretched-link">
                                            <?php echo htmlspecialchars($f['name']); ?>
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- الملفات -->
                <div class="bg-white rounded shadow-sm overflow-hidden">
                    <div class="p-3 border-bottom bg-light">
                        <h6 class="mb-0"><i class="fas fa-file-alt me-2 text-secondary"></i> <?php echo __('files'); ?></h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><?php echo __('title'); ?></th>
                                    <th>الحجم</th>
                                    <th class="text-end">إجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($files as $f): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-file-invoice fa-lg text-primary me-3"></i>
                                                <span class="fw-medium"><?php echo htmlspecialchars($f['original_name']); ?></span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark"><?php echo formatSize($f['file_size']); ?></span></td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="viewer.php?id=<?php echo $f['id']; ?>" class="btn btn-outline-primary" title="<?php echo __('view'); ?>">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="actions/download.php?id=<?php echo $f['id']; ?>" class="btn btn-outline-success" title="<?php echo __('download'); ?>">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button class="btn btn-outline-danger" title="<?php echo __('delete'); ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($files) && empty($folders)): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                            <?php echo __('no_files'); ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Ads -->
            <div class="col-lg-3">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="p-3 bg-light border-bottom">
                            <h6 class="mb-0 text-uppercase small fw-bold text-muted">📢 إعلان ممول</h6>
                        </div>
                        <div class="p-3">
                            <div class="bg-secondary bg-opacity-10 rounded p-4 text-center border border-dashed">
                                <p class="text-muted mb-0 small">مساحة إعلانية مخصصة</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="actions/upload.php" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo __('upload'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="folder_id" value="<?php echo $current_folder; ?>">
                    <div class="mb-3">
                        <label class="form-label">اختر الملفات</label>
                        <input type="file" name="files[]" class="form-control" multiple required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100"><?php echo __('upload'); ?></button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

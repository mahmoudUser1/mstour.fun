<?php
require_once '../includes/functions.php';

if (!isAdmin()) {
    header('Location: ../index.php');
    exit;
}

$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$files_count = $pdo->query("SELECT COUNT(*) FROM files")->fetchColumn();
$ads_count = $pdo->query("SELECT COUNT(*) FROM advertisements WHERE is_active = TRUE")->fetchColumn();
$notes_count = $pdo->query("SELECT COUNT(*) FROM notes")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo renderMetaTags('لوحة المسؤول'); ?>
</head>
<body class="bg-light">
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-white shadow-sm border-start" style="width: 280px; min-height: 100vh;">
            <div class="p-4 border-bottom text-center">
                <h5 class="fw-bold text-primary mb-0"><i class="fas fa-user-shield me-2"></i> لوحة التحكم</h5>
            </div>
            <div class="p-3">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item mb-2">
                        <a href="index.php" class="nav-link active py-3"><i class="fas fa-chart-line me-2"></i> الرئيسية</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="users.php" class="nav-link text-dark py-3"><i class="fas fa-users me-2"></i> المستخدمين</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link text-dark py-3"><i class="fas fa-ad me-2"></i> الإعلانات</a>
                    </li>
                    <li class="nav-item mt-4">
                        <a href="../index.php" class="nav-link text-danger py-3"><i class="fas fa-arrow-right me-2"></i> العودة للموقع</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4 p-lg-5">
            <h2 class="fw-bold mb-4">نظرة عامة على النظام</h2>
            
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-primary text-white">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $users_count; ?></h3>
                                <p class="mb-0 opacity-75">مستخدم</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-success text-white">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $files_count; ?></h3>
                                <p class="mb-0 opacity-75">ملف مرفوع</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-info text-white">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                                <i class="fas fa-sticky-note fa-2x"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $notes_count; ?></h3>
                                <p class="mb-0 opacity-75">ملاحظة/رابط</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-warning text-white">
                        <div class="d-flex align-items-center">
                            <div class="bg-white bg-opacity-25 p-3 rounded-4 me-3">
                                <i class="fas fa-ad fa-2x"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $ads_count; ?></h3>
                                <p class="mb-0 opacity-75">إعلان نشط</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-bottom">
                    <h5 class="fw-bold mb-0"><i class="fas fa-plus-circle text-primary me-2"></i> إضافة إعلان جديد</h5>
                </div>
                <div class="card-body p-4">
                    <form action="../actions/admin_ads.php" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">عنوان الإعلان</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small">رابط الإعلان</label>
                                <input type="url" name="ad_link" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small">صورة الإعلان</label>
                                <input type="file" name="ad_image" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">حفظ الإعلان</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
require_once __DIR__ . '/includes/header.php';
?>

<div class="row align-items-center">
    <div class="col-md-6">
        <h1 class="display-4 mb-4"><?php echo SITE_NAME; ?></h1>
        <p class="lead mb-4"><?php echo SITE_DESCRIPTION; ?></p>
        <p class="mb-4">
            منصة آمنة وسهلة الاستخدام لمشاركة ونقل الملفات. احصل على 2 جيجابايت من المساحة التخزينية المجانية.
        </p>
        
        <?php if (!isLoggedIn()): ?>
            <div class="d-flex gap-2">
                <a href="<?php echo SITE_URL; ?>/pages/register.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-plus"></i> <?php echo t('signUp'); ?>
                </a>
                <a href="<?php echo SITE_URL; ?>/pages/login.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-sign-in-alt"></i> <?php echo t('signIn'); ?>
                </a>
            </div>
        <?php else: ?>
            <a href="<?php echo SITE_URL; ?>/pages/dashboard.php" class="btn btn-primary btn-lg">
                <i class="fas fa-cloud-upload-alt"></i> <?php echo t('dashboard'); ?>
            </a>
        <?php endif; ?>
    </div>
    
    <div class="col-md-6 text-center">
        <i class="fas fa-cloud-upload-alt fa-10x text-primary opacity-25"></i>
    </div>
</div>

<!-- Features Section -->
<div class="row mt-5 pt-5">
    <div class="col-md-4 mb-4">
        <div class="text-center">
            <i class="fas fa-lock fa-3x text-primary mb-3"></i>
            <h5>آمن وموثوق</h5>
            <p>تشفير عالي المستوى لحماية ملفاتك</p>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="text-center">
            <i class="fas fa-bolt fa-3x text-primary mb-3"></i>
            <h5>سريع وسهل</h5>
            <p>رفع وتحميل الملفات بسهولة وسرعة</p>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="text-center">
            <i class="fas fa-share-alt fa-3x text-primary mb-3"></i>
            <h5>شارك بسهولة</h5>
            <p>شارك ملفاتك مع الآخرين بسهولة</p>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

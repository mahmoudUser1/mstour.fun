<?php
require_once __DIR__ . '/../includes/header.php';

// التحقق من تسجيل الدخول والصلاحيات
if (!isLoggedIn() || !isAdmin()) {
    redirect(SITE_URL . '/pages/login.php');
}

$users = getAllUsers();
$stats = getStatistics();
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1><?php echo t('adminPanel'); ?></h1>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title"><?php echo t('totalUsers'); ?></h5>
                <h2><?php echo $stats['total_users']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title"><?php echo t('totalFiles'); ?></h5>
                <h2><?php echo $stats['total_files']; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title"><?php echo t('storage'); ?></h5>
                <h2><?php echo formatFileSize($stats['total_storage']); ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Users List -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo t('userList'); ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th><?php echo t('userName'); ?></th>
                            <th><?php echo t('userEmail'); ?></th>
                            <th><?php echo t('userStorage'); ?></th>
                            <th><?php echo t('joinDate'); ?></th>
                            <th><?php echo t('lastLogin'); ?></th>
                            <th><?php echo t('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo formatFileSize(getUserStorageUsed($user['id'])); ?></td>
                                <td><?php echo formatDate($user['created_at']); ?></td>
                                <td><?php echo formatDate($user['last_login'] ?? $user['created_at']); ?></td>
                                <td>
                                    <a href="<?php echo SITE_URL; ?>/admin/user.php?id=<?php echo $user['id']; ?>" 
                                       class="btn btn-sm btn-info" title="<?php echo t('view'); ?>">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="deleteUser">
                                        <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('<?php echo t('confirm'); ?>')" 
                                                title="<?php echo t('delete'); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

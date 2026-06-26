<?php
require_once __DIR__ . '/../includes/header.php';

// التحقق من تسجيل الدخول
if (!isLoggedIn()) {
    redirect(SITE_URL . '/pages/login.php');
}

$user = getCurrentUser();
$files = getUserFiles($user['id']);
$folders = getUserFolders($user['id']);
$storage_used = getUserStorageUsed($user['id']);
$storage_percentage = getStoragePercentage($user['id']);
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1><?php echo t('myFiles'); ?></h1>
    </div>
</div>

<!-- Storage Information -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo t('storageUsage'); ?></h5>
                <div class="storage-bar mb-3">
                    <div class="storage-bar-fill" style="width: <?php echo $storage_percentage; ?>%"></div>
                </div>
                <p class="mb-0">
                    <strong><?php echo formatFileSize($storage_used); ?></strong> / 
                    <strong><?php echo formatFileSize(STORAGE_LIMIT); ?></strong>
                    (<?php echo $storage_percentage; ?>%)
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Upload and Create Folder Buttons -->
<div class="row mb-4">
    <div class="col-md-6">
        <form method="POST" enctype="multipart/form-data" id="uploadForm">
            <input type="hidden" name="action" value="upload">
            <div class="input-group">
                <input type="file" class="form-control" id="fileInput" name="file" required>
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-upload"></i> <?php echo t('upload'); ?>
                </button>
            </div>
        </form>
    </div>
    <div class="col-md-6">
        <form method="POST" id="createFolderForm">
            <input type="hidden" name="action" value="createFolder">
            <div class="input-group">
                <input type="text" class="form-control" name="folderName" placeholder="<?php echo t('folderName'); ?>" required>
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-folder-plus"></i> <?php echo t('createFolder'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Folders Section -->
<?php if (!empty($folders)): ?>
    <div class="row mb-4">
        <div class="col-md-12">
            <h4><?php echo t('folders'); ?></h4>
            <div class="row">
                <?php foreach ($folders as $folder): ?>
                    <div class="col-md-3 mb-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-folder fa-3x text-warning mb-3"></i>
                                <h6 class="card-title"><?php echo htmlspecialchars($folder['name']); ?></h6>
                                <small class="text-muted"><?php echo formatDate($folder['created_at']); ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Files Section -->
<?php if (!empty($files)): ?>
    <div class="row">
        <div class="col-md-12">
            <h4><?php echo t('files'); ?></h4>
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th><?php echo t('fileName'); ?></th>
                        <th><?php echo t('fileSize'); ?></th>
                        <th><?php echo t('uploadDate'); ?></th>
                        <th><?php echo t('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file): ?>
                        <tr>
                            <td>
                                <i class="fas fa-file"></i> 
                                <?php echo htmlspecialchars($file['original_name']); ?>
                            </td>
                            <td><?php echo formatFileSize($file['file_size']); ?></td>
                            <td><?php echo formatDate($file['uploaded_at']); ?></td>
                            <td>
                                <a href="<?php echo SITE_URL; ?>/pages/download.php?id=<?php echo $file['id']; ?>" 
                                   class="btn btn-sm btn-info" title="<?php echo t('download'); ?>">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="deleteFile">
                                    <input type="hidden" name="fileId" value="<?php echo $file['id']; ?>">
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
<?php else: ?>
    <div class="alert alert-info text-center">
        <i class="fas fa-inbox fa-3x mb-3"></i>
        <p><?php echo t('noFiles'); ?></p>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

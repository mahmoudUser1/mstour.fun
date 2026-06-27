<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/languages.php';
require_once __DIR__ . '/functions.php';

$lang = getCurrentLanguage();
$direction = getDirection($lang);
$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>" dir="<?php echo $direction; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - <?php echo SITE_DESCRIPTION; ?></title>
    <meta name="description" content="<?php echo SITE_DESCRIPTION; ?>">

    <!-- Google Fonts - Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">

    <style>
    * {
        font-family: 'Cairo', sans-serif;
    }

    body {
        background-color: #f5f5f5;
    }

    .navbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand {
        font-weight: 700;
        font-size: 1.5rem;
    }

    .btn-primary {
        background-color: #667eea;
        border-color: #667eea;
    }

    .btn-primary:hover {
        background-color: #764ba2;
        border-color: #764ba2;
    }

    .storage-bar {
        height: 10px;
        background-color: #e9ecef;
        border-radius: 5px;
        overflow: hidden;
    }

    .storage-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        transition: width 0.3s ease;
    }

    .footer {
        background-color: #2c3e50;
        color: #ecf0f1;
        margin-top: 50px;
        padding: 30px 0;
    }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
                <i class="fas fa-cloud-upload-alt"></i> <?php echo SITE_NAME; ?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>"><?php echo t('home'); ?></a>
                    </li>

                    <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?php echo SITE_URL; ?>/pages/dashboard.php"><?php echo t('dashboard'); ?></a>
                    </li>

                    <?php if (isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/admin/index.php"><?php echo t('admin'); ?></a>
                    </li>
                    <?php endif;?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($user['name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item"
                                    href="<?php echo SITE_URL; ?>/pages/profile.php"><?php echo t('profile'); ?></a>
                            </li>
                            <li><a class="dropdown-item"
                                    href="<?php echo SITE_URL; ?>/pages/settings.php"><?php echo t('settings'); ?></a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item"
                                    href="<?php echo SITE_URL; ?>/pages/logout.php"><?php echo t('logout'); ?></a></li>
                        </ul>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/login.php"><?php echo t('login'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?php echo SITE_URL; ?>/pages/register.php"><?php echo t('register'); ?></a>
                    </li>
                    <?php endif; ?>

                    <!-- Language Switcher -->
                    <li class="nav-item">
                        <a class="nav-link" href="?lang=<?php echo $lang === 'ar' ? 'en' : 'ar'; ?>">
                            <?php echo $lang === 'ar' ? 'EN' : 'AR'; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
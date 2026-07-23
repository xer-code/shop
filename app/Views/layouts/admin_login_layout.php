<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#D4A017">
    <title><?= e($pageTitle ?? 'Admin Login') ?></title>
    
    <?php
    $sysSettings = \App\Core\Session::get('ent_settings', []);
    if (!empty($sysSettings['favicon_path'])):
    ?>
        <link rel="icon" href="<?= url('/' . ltrim($sysSettings['favicon_path'], '/')) ?>">
    <?php endif; ?>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
</head>
<body style="background-color: var(--bg-primary); color: var(--text-primary); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem;">
    <div style="width: 100%; max-width: 440px;">
        <!-- Flash Messages -->
        <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
            <div class="flash-message flash-success" style="margin-bottom: 1rem;">
                <?= e($msg) ?>
            </div>
        <?php endif; ?>
        <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
            <div class="flash-message flash-error" style="margin-bottom: 1rem;">
                <?= e($msg) ?>
            </div>
        <?php endif; ?>
        
        <?= $content ?>
    </div>
</body>
</html>

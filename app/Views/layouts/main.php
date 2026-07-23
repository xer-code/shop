<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShopX Global — Premium global marketplace. Everything you need, delivered anywhere on Earth.">
    <meta name="theme-color" content="#D4A017">
    <!-- PWA Web App Wrapper Meta Tags -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= e($sysSettings['app_name'] ?? 'DexterX Global') ?>">
    <meta name="application-name" content="<?= e($sysSettings['app_name'] ?? 'DexterX Global') ?>">
    
    <title><?= e($pageTitle ?? 'ShopX Global') ?></title>
    
    <?php
    if (!empty($sysSettings['favicon_path'])):
    ?>
        <link rel="icon" href="<?= url('/' . ltrim($sysSettings['favicon_path'], '/')) ?>">
    <?php endif; ?>
    
    <!-- PWA Manifest & Icons -->
    <link rel="manifest" href="<?= url('/manifest.json') ?>">
    <link rel="apple-touch-icon" href="<?= asset('images/icon-192.png') ?>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN (Play CDN for development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gold': '#D4A017',
                        'gold-light': '#E8C158',
                        'gold-dark': '#B8860B',
                        'surface': '#1a1a1a',
                        'surface-dark': '#111111',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>?v=1.0.2">
</head>
<body>
    <!-- Header -->
    <?php \App\Core\View::partial('header'); ?>
    
    <!-- Flash Messages -->
    <div class="container" style="margin-top: 0.5rem;">
        <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
            <div class="flash-message flash-success">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?= e($msg) ?>
            </div>
        <?php endif; ?>
        <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
            <div class="flash-message flash-error">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?= e($msg) ?>
            </div>
        <?php endif; ?>
        <?php if ($msg = \App\Core\Session::getFlash('info')): ?>
            <div class="flash-message flash-info">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <?= e($msg) ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <?php \App\Core\View::partial('footer'); ?>
    
    <!-- Floating Widgets -->
    <?php \App\Core\View::partial('floating-widgets'); ?>

    <!-- Main JS -->
    <script src="<?= asset('js/app.js') ?>?v=1.0.2"></script>
    
    <!-- Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?= url('/service-worker.js') ?>')
                .catch(err => console.log('SW registration failed:', err));
        }
    </script>
</body>
</html>

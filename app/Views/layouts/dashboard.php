<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShopX Global — My Dashboard">
    <!-- PWA Web App Wrapper Meta Tags -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="<?= e($sysSettings['app_name'] ?? 'DexterX Global') ?>">
    <meta name="application-name" content="<?= e($sysSettings['app_name'] ?? 'DexterX Global') ?>">
    
    <title><?= e($pageTitle ?? 'My Dashboard — ShopX Global') ?></title>
    
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
    
    <!-- Tailwind CDN -->
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
    <link rel="stylesheet" href="<?= asset('css/app.css') ?>">
    <style>
        .dash-sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: 260px; background: #0d0d0d; border-right: 1px solid #1a1a1a;
            padding: 1.5rem 1rem; z-index: 100; overflow-y: auto;
        }
        .dash-main {
            margin-left: 260px; min-height: 100vh; padding: 2rem;
        }
        .dash-nav a {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 0.85rem; border-radius: 8px;
            color: #999; font-size: 0.82rem; font-weight: 600;
            transition: all 0.15s; text-decoration: none;
        }
        .dash-nav a:hover { color: #fff; background: #1a1a1a; }
        .dash-nav a.active { color: #D4A017; background: rgba(212,160,23,0.08); border: 1px solid rgba(212,160,23,0.15); }
        .dash-nav a .nav-icon { font-size: 1rem; width: 22px; text-align: center; }
        .dash-group-label {
            font-size: 0.65rem; font-weight: 700; color: #555;
            text-transform: uppercase; letter-spacing: 0.12em;
            padding: 0 0.85rem; margin-bottom: 0.35rem; margin-top: 1.25rem;
        }
        @media (max-width: 900px) {
            .dash-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: 5px 0 25px rgba(0,0,0,0.5);
            }
            .dash-sidebar.active { transform: translateX(0); }
            .dash-main { margin-left: 0; }
            #dash-mobile-toggle-btn { display: flex !important; }
            #dash-mobile-close { display: flex !important; }
        }
        @media (max-width: 576px) {
            .dash-wallet-badge { display: none !important; }
        }
    </style>
</head>
<body style="background-color: var(--bg-primary); color: var(--text-primary);">
    <?php
    use App\Core\Auth;
    use App\Core\Session;
    $userModel = \App\Models\User::find(Auth::id());
    $dashSection = $dashSection ?? 'overview';
    ?>

    <!-- Customer Dashboard Sidebar -->
    <aside class="dash-sidebar" id="dashSidebar">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <a href="<?= url('/') ?>" class="logo" style="text-decoration: none;">
                <div class="logo-main" style="font-size: 1.4rem;">SHOP<span>X</span></div>
                <div class="logo-sub" style="font-size: 0.5rem; letter-spacing: 0.25em;">D A S H B O A R D</div>
            </a>
            <button id="dash-mobile-close" style="display: none; background: transparent; border: 1px solid #2a2a2a; border-radius: 6px; color: #888; width: 30px; height: 30px; align-items: center; justify-content: center; font-weight: bold; cursor: pointer;">✕</button>
        </div>

        <!-- User Info Card -->
        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #151515; border: 1px solid #1a1a1a; border-radius: 10px; margin-bottom: 1.25rem;">
            <div style="width: 38px; height: 38px; border-radius: 50%; border: 1.5px solid rgba(212,160,23,0.3); overflow: hidden; display: flex; align-items: center; justify-content: center; background: #1a1a1a; flex-shrink: 0;">
                <?php if ($userModel && !empty($userModel['avatar'])): ?>
                    <img src="<?= url('/' . $userModel['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--gold-primary);"><?= strtoupper(substr(Auth::name() ?? 'U', 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div style="overflow: hidden;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e(Auth::name()) ?></div>
                <div style="font-size: 0.65rem; color: #666; font-family: monospace;"><?= e(Auth::email()) ?></div>
            </div>
        </div>

        <!-- Navigation -->
        <nav>
            <ul class="dash-nav" style="list-style: none; padding: 0; margin: 0;">
                <div class="dash-group-label">Overview</div>
                <li><a href="<?= url('/dashboard') ?>" class="<?= $dashSection === 'overview' ? 'active' : '' ?>"><span class="nav-icon">📊</span> Dashboard</a></li>

                <div class="dash-group-label">Shopping</div>
                <li><a href="<?= url('/dashboard?section=orders') ?>" class="<?= $dashSection === 'orders' ? 'active' : '' ?>"><span class="nav-icon">📦</span> Orders</a></li>
                <li><a href="<?= url('/dashboard?section=tracking') ?>" class="<?= $dashSection === 'tracking' ? 'active' : '' ?>"><span class="nav-icon">🚚</span> Tracking</a></li>
                <li><a href="<?= url('/dashboard?section=saved') ?>" class="<?= $dashSection === 'saved' ? 'active' : '' ?>"><span class="nav-icon">❤️</span> Saved Products</a></li>

                <div class="dash-group-label">Financials</div>
                <li><a href="<?= url('/dashboard?section=wallet') ?>" class="<?= $dashSection === 'wallet' ? 'active' : '' ?>"><span class="nav-icon">💰</span> Wallet</a></li>
                <li><a href="<?= url('/dashboard?section=payments') ?>" class="<?= $dashSection === 'payments' ? 'active' : '' ?>"><span class="nav-icon">💳</span> Payments</a></li>
                <li><a href="<?= url('/dashboard?section=quotes') ?>" class="<?= $dashSection === 'quotes' ? 'active' : '' ?>"><span class="nav-icon">📝</span> Quotes</a></li>
                <li><a href="<?= url('/dashboard?section=giftcards') ?>" class="<?= $dashSection === 'giftcards' ? 'active' : '' ?>"><span class="nav-icon">🎁</span> Gift Cards</a></li>

                <div class="dash-group-label">Communication</div>
                <li><a href="<?= url('/dashboard?section=notifications') ?>" class="<?= $dashSection === 'notifications' ? 'active' : '' ?>"><span class="nav-icon">🔔</span> Notifications</a></li>
                <li><a href="<?= url('/dashboard?section=support') ?>" class="<?= $dashSection === 'support' ? 'active' : '' ?>"><span class="nav-icon">🎫</span> Support Tickets</a></li>

                <div class="dash-group-label">Account</div>
                <li><a href="<?= url('/profile') ?>"><span class="nav-icon">⚙️</span> Profile Settings</a></li>
            </ul>
        </nav>

        <!-- Footer Actions -->
        <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #1a1a1a;">
            <ul class="dash-nav" style="list-style: none; padding: 0; margin: 0;">
                <li><a href="<?= url('/shop') ?>" style="color: #D4A017;">← Return to Shop</a></li>
                <li><a href="<?= url('/logout') ?>" style="color: #ef4444;">🚪 Logout</a></li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="dash-main">
        <!-- Top bar -->
        <header style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.5rem; margin-bottom: 2rem; border-bottom: 1px solid #1a1a1a;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 800;"><?= e($pageTitle) ?></h1>
                    <p style="font-size: 0.85rem; color: var(--text-muted);">Welcome back, <strong style="color: var(--text-secondary);"><?= e(Auth::name()) ?></strong></p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span class="dash-wallet-badge" style="padding: 4px 14px; background: rgba(212,160,23,0.06); border: 1px solid rgba(212,160,23,0.2); color: #D4A017; font-size: 0.8rem; font-weight: 700; border-radius: 20px; font-family: monospace;">
                    <?= formatPrice(Auth::wallet()) ?>
                </span>
                <!-- User Profile Avatar with Dropdown -->
                <style>
                    .avatar-dropdown-item {
                        display: block;
                        padding: 0.6rem 1.25rem;
                        color: var(--text-secondary);
                        font-size: 0.85rem;
                        font-weight: 500;
                        transition: all 0.2s ease;
                        text-align: left;
                        text-decoration: none;
                    }
                    .avatar-dropdown-item:hover {
                        background: rgba(212, 160, 23, 0.1);
                        color: var(--gold-primary);
                    }
                    .avatar-dropdown-item.text-danger:hover {
                        background: rgba(239, 68, 68, 0.1);
                        color: #ef4444;
                    }
                </style>

                <div style="position: relative; display: inline-block;" id="avatarDropdownContainer">
                    <button id="avatarDropdownToggle" title="My Account" style="width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid rgba(212,160,23,0.3); overflow: hidden; display: flex; align-items: center; justify-content: center; background: #1a1a1a; cursor: pointer; transition: transform 0.2s; padding: 0; outline: none;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <?php if ($userModel && !empty($userModel['avatar'])): ?>
                            <img src="<?= url('/' . $userModel['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <span style="font-size: 0.85rem; font-weight: 800; color: #D4A017;"><?= strtoupper(substr(Auth::name() ?? 'U', 0, 1)) ?></span>
                        <?php endif; ?>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="avatarDropdownMenu" style="display: none; position: absolute; right: 0; top: 120%; width: 160px; background: #111; border: 1px solid #2a2a2a; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); z-index: 1000; overflow: hidden; padding: 0.5rem 0;">
                        <a href="<?= url('/dashboard') ?>" class="avatar-dropdown-item">📊 Dashboard</a>
                        <a href="<?= url('/profile') ?>" class="avatar-dropdown-item">👤 Profile</a>
                        <a href="<?= url('/') ?>" class="avatar-dropdown-item">🏪 Store</a>
                        <a href="<?= url('/logout') ?>" class="avatar-dropdown-item text-danger">🚪 Logout</a>
                    </div>
                </div>
                
                <!-- Hamburger (Mobile Toggle) -->
                <button class="hamburger" id="dash-mobile-toggle-btn" aria-label="Open menu" style="display: none; flex-direction: column; gap: 5px; background: none; border: none; cursor: pointer; padding: 4px; margin-left: 0.5rem; outline: none; align-items: center; justify-content: center;">
                    <span style="width: 24px; height: 2px; background: #fff; border-radius: 2px;"></span>
                    <span style="width: 24px; height: 2px; background: #fff; border-radius: 2px;"></span>
                    <span style="width: 24px; height: 2px; background: #fff; border-radius: 2px;"></span>
                </button>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toggle = document.getElementById('avatarDropdownToggle');
                    const menu = document.getElementById('avatarDropdownMenu');
                    
                    if (toggle && menu) {
                        toggle.addEventListener('click', function(e) {
                            e.stopPropagation();
                            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
                        });
                        
                        document.addEventListener('click', function() {
                            menu.style.display = 'none';
                        });
                        
                        menu.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                    }
                });
                </script>
            </div>
        </header>

        <!-- Flash Messages -->
        <?php if ($msg = Session::getFlash('success')): ?>
            <div class="flash-message flash-success" style="margin-bottom: 1.5rem;"><?= e($msg) ?></div>
        <?php endif; ?>
        <?php if ($msg = Session::getFlash('error')): ?>
            <div class="flash-message flash-error" style="margin-bottom: 1.5rem;"><?= e($msg) ?></div>
        <?php endif; ?>

        <!-- Dynamic Content -->
        <?= $content ?>
        <!-- Floating Widgets & PWA Banner -->
        <?php \App\Core\View::partial('floating-widgets'); ?>
    </main>

    <!-- Main JS -->
    <script src="<?= asset('js/app.js') ?>?v=1.0.3"></script>
    <script>
    (function(){
        const sidebar = document.getElementById('dashSidebar');
        const toggle = document.getElementById('dash-mobile-toggle-btn');
        const close = document.getElementById('dash-mobile-close');
        if (toggle) toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.add('active');
        });
        if (close) close.addEventListener('click', () => sidebar.classList.remove('active'));
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 900 && sidebar.classList.contains('active') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    })();
    </script>
</body>
</html>

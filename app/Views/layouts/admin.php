<?php
use App\Core\Auth;
use App\Core\Session;

if (!function_exists('hasPermission')) {
    function hasPermission(string $feature): bool {
        if (Auth::role() === 'admin') {
            return true;
        }
        $roles = Session::get('ent_roles', []);
        $userRole = Auth::role();
        if (isset($roles[$userRole]) && isset($roles[$userRole][$feature])) {
            return (int) $roles[$userRole][$feature] === 1;
        }
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ShopX Global Administrator Panel.">
    <meta name="theme-color" content="#D4A017">
    <title><?= e($pageTitle ?? 'Admin Panel — ShopX Global') ?></title>
    
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
        @media (max-width: 900px) {
            #mobile-nav-toggle-btn {
                display: flex !important;
            }
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                display: block !important;
                box-shadow: 5px 0 25px rgba(0,0,0,0.5);
            }
            .admin-sidebar.active {
                transform: translateX(0);
            }
            .sidebar-close-btn {
                display: flex !important;
            }
        }
    </style>
</head>
<body style="background-color: var(--bg-primary); color: var(--text-primary);">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <a href="<?= url('/') ?>" class="logo">
                <div class="logo-main" style="font-size: 1.6rem;">SHOP<span>X</span></div>
                <div class="logo-sub" style="font-size: 0.55rem; letter-spacing: 0.25em;">C O N T R O L</div>
            </a>
            <!-- Close Button for Mobile Sidebar -->
            <button id="mobile-nav-close" class="sidebar-close-btn" style="display: none; background: transparent; border: 1px solid var(--border-dark); border-radius: 6px; color: var(--text-muted); width: 32px; height: 32px; align-items: center; justify-content: center; font-weight: bold; cursor: pointer; outline: none;">
                ✕
            </button>
        </div>
        
        <div class="admin-nav-container" style="max-height: calc(100vh - 120px); overflow-y: auto; padding-right: 4px;">
            <!-- Group 1: Core Admin -->
            <div class="admin-nav-group" style="margin-bottom: 1.5rem;">
                <div class="admin-nav-group-header" style="font-size: 0.75rem; font-weight: 700; color: var(--tan-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; padding-left: 0.75rem;">📊 Core Admin</div>
                <ul class="admin-nav" style="margin-top: 0;">
                    <li>
                        <a href="<?= url('/admin/dashboard') ?>" class="<?= isActive('admin/dashboard') || (isActive('admin') && !isActive('admin/products') && !isActive('admin/users') && !isActive('admin/orders') && !isActive('admin/gift-cards') && !isActive('admin/analytics') && !isActive('admin/customers') && !isActive('admin/suppliers') && !isActive('admin/categories') && !isActive('admin/payments') && !isActive('admin/invoices') && !isActive('admin/quotes') && !isActive('admin/warehouses') && !isActive('admin/shipments') && !isActive('admin/tracking') && !isActive('admin/support') && !isActive('admin/promotions') && !isActive('admin/coupons') && !isActive('admin/notifications') && !isActive('admin/audit-logs') && !isActive('admin/reports') && !isActive('admin/settings') && !isActive('admin/roles') && !isActive('admin/permissions') && !isActive('admin/api-keys')) ? 'active' : '' ?>">
                            📊 Dashboard
                        </a>
                    </li>
                    <?php if (hasPermission('analytics')): ?>
                    <li>
                        <a href="<?= url('/admin/analytics') ?>" class="<?= isActive('admin/analytics') ? 'active' : '' ?>">
                            📈 Analytics
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('reports')): ?>
                    <li>
                        <a href="<?= url('/admin/reports') ?>" class="<?= isActive('admin/reports') ? 'active' : '' ?>">
                            📋 Reports
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('notifications')): ?>
                    <li>
                        <a href="<?= url('/admin/notifications') ?>" class="<?= isActive('admin/notifications') ? 'active' : '' ?>">
                            🔔 Notifications
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Group 2: Sales & Catalog -->
            <div class="admin-nav-group" style="margin-bottom: 1.5rem;">
                <div class="admin-nav-group-header" style="font-size: 0.75rem; font-weight: 700; color: var(--tan-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; padding-left: 0.75rem;">🛍️ Sales & Catalog</div>
                <ul class="admin-nav" style="margin-top: 0;">
                    <?php if (hasPermission('products')): ?>
                    <li>
                        <a href="<?= url('/admin/products') ?>" class="<?= isActive('admin/products') ? 'active' : '' ?>">
                            📦 Products
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('categories')): ?>
                    <li>
                        <a href="<?= url('/admin/categories') ?>" class="<?= isActive('admin/categories') ? 'active' : '' ?>">
                            🏷️ Categories
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('orders')): ?>
                    <li>
                        <a href="<?= url('/admin/orders') ?>" class="<?= isActive('admin/orders') ? 'active' : '' ?>">
                            🛒 Orders
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('customers')): ?>
                    <li>
                        <a href="<?= url('/admin/customers') ?>" class="<?= isActive('admin/customers') ? 'active' : '' ?>">
                            👥 Customers
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('payments')): ?>
                    <li>
                        <a href="<?= url('/admin/payments') ?>" class="<?= isActive('admin/payments') ? 'active' : '' ?>">
                            💳 Payments
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('deposits')): ?>
                    <li>
                        <a href="<?= url('/admin/deposits') ?>" class="<?= isActive('admin/deposits') ? 'active' : '' ?>">
                            📥 Deposit Requests
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('invoices')): ?>
                    <li>
                        <a href="<?= url('/admin/invoices') ?>" class="<?= isActive('admin/invoices') ? 'active' : '' ?>">
                            🧾 Invoices
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('quotes')): ?>
                    <li>
                        <a href="<?= url('/admin/quotes') ?>" class="<?= isActive('admin/quotes') ? 'active' : '' ?>">
                            💬 Quotes
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Group 3: Logistics -->
            <div class="admin-nav-group" style="margin-bottom: 1.5rem;">
                <div class="admin-nav-group-header" style="font-size: 0.75rem; font-weight: 700; color: var(--tan-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; padding-left: 0.75rem;">🚚 Logistics</div>
                <ul class="admin-nav" style="margin-top: 0;">
                    <?php if (hasPermission('suppliers')): ?>
                    <li>
                        <a href="<?= url('/admin/suppliers') ?>" class="<?= isActive('admin/suppliers') ? 'active' : '' ?>">
                            🤝 Suppliers
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('warehouses')): ?>
                    <li>
                        <a href="<?= url('/admin/warehouses') ?>" class="<?= isActive('admin/warehouses') ? 'active' : '' ?>">
                            🏢 Warehouses
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('shipments')): ?>
                    <li>
                        <a href="<?= url('/admin/shipments') ?>" class="<?= isActive('admin/shipments') ? 'active' : '' ?>">
                            🚢 Shipments
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('tracking')): ?>
                    <li>
                        <a href="<?= url('/admin/tracking') ?>" class="<?= isActive('admin/tracking') ? 'active' : '' ?>">
                            📍 Tracking
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Group 4: Marketing & CRM -->
            <div class="admin-nav-group" style="margin-bottom: 1.5rem;">
                <div class="admin-nav-group-header" style="font-size: 0.75rem; font-weight: 700; color: var(--tan-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; padding-left: 0.75rem;">📣 Marketing & CRM</div>
                <ul class="admin-nav" style="margin-top: 0;">
                    <?php if (hasPermission('promotions')): ?>
                    <li>
                        <a href="<?= url('/admin/promotions') ?>" class="<?= isActive('admin/promotions') ? 'active' : '' ?>">
                            🔥 Promotions
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('coupons')): ?>
                    <li>
                        <a href="<?= url('/admin/coupons') ?>" class="<?= isActive('admin/coupons') ? 'active' : '' ?>">
                            🎟️ Coupons
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('gift_cards')): ?>
                    <li>
                        <a href="<?= url('/admin/gift-cards') ?>" class="<?= isActive('admin/gift-cards') ? 'active' : '' ?>">
                            🎁 Gift Cards
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('support')): ?>
                    <li>
                        <a href="<?= url('/admin/support') ?>" class="<?= isActive('admin/support') ? 'active' : '' ?>">
                            📞 Support
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('chat')): ?>
                    <li>
                        <a href="<?= url('/admin/live-chat') ?>" class="<?= isActive('admin/live-chat') ? 'active' : '' ?>">
                            💬 Live Chat
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Group 5: System & Security -->
            <div class="admin-nav-group" style="margin-bottom: 1.5rem;">
                <div class="admin-nav-group-header" style="font-size: 0.75rem; font-weight: 700; color: var(--tan-muted); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem; padding-left: 0.75rem;">⚙️ System & Security</div>
                <ul class="admin-nav" style="margin-top: 0;">
                    <?php if (hasPermission('settings')): ?>
                    <li>
                        <a href="<?= url('/admin/settings') ?>" class="<?= isActive('admin/settings') ? 'active' : '' ?>">
                            🔧 Settings
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('users')): ?>
                    <li>
                        <a href="<?= url('/admin/users') ?>" class="<?= isActive('admin/users') ? 'active' : '' ?>">
                            👥 Admins & Users
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('roles')): ?>
                    <li>
                        <a href="<?= url('/admin/roles') ?>" class="<?= isActive('admin/roles') ? 'active' : '' ?>">
                            🛡️ Roles
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('permissions')): ?>
                    <li>
                        <a href="<?= url('/admin/permissions') ?>" class="<?= isActive('admin/permissions') ? 'active' : '' ?>">
                            🔑 Permissions
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('api_keys')): ?>
                    <li>
                        <a href="<?= url('/admin/api-keys') ?>" class="<?= isActive('admin/api-keys') ? 'active' : '' ?>">
                            🔌 API Keys
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('gateways')): ?>
                    <li>
                        <a href="<?= url('/admin/payment-gateways') ?>" class="<?= isActive('admin/payment-gateways') ? 'active' : '' ?>">
                            💳 Payment Gateways
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if (hasPermission('audit_logs')): ?>
                    <li>
                        <a href="<?= url('/admin/audit-logs') ?>" class="<?= isActive('admin/audit-logs') ? 'active' : '' ?>">
                            📝 Audit Logs
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Store & Logout Actions -->
            <div class="admin-nav-group" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border-dark);">
                <ul class="admin-nav" style="margin-top: 0;">
                    <li>
                        <a href="<?= url('/') ?>" style="color: var(--gold-primary);">
                            ← Return to Store
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('/logout') ?>" style="color: #ef4444;">
                            🚪 Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="admin-main">
        <!-- Top bar -->
        <header style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1.5rem; margin-bottom: 2rem; border-bottom: 1px solid var(--border-dark);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div>
                    <h1 style="font-size: 1.5rem; font-weight: 800;"><?= e($pageTitle) ?></h1>
                    <p style="font-size: 0.85rem; color: var(--text-muted);">Signed in as <strong style="color: var(--text-secondary);"><?= e(\App\Core\Auth::name()) ?></strong></p>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="padding: 4px 12px; background: var(--gold-glow); border: 1px solid var(--border-gold); color: var(--gold-primary); font-size: 0.75rem; font-weight: 700; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.05em;">
                    🛡️ System Administrator
                </span>
                
                <!-- Admin Profile Avatar with Dropdown -->
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
                    <button id="avatarDropdownToggle" title="My Account" style="width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid var(--border-gold); overflow: hidden; display: flex; align-items: center; justify-content: center; background: #1a1a1a; cursor: pointer; transition: transform 0.2s; padding: 0; outline: none;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <?php 
                        $userModel = \App\Models\User::find(\App\Core\Auth::id());
                        if ($userModel && !empty($userModel['avatar'])): 
                        ?>
                            <img src="<?= url('/' . $userModel['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <span style="font-size: 0.85rem; font-weight: 800; color: var(--gold-primary);"><?= strtoupper(substr(\App\Core\Auth::name() ?? 'A', 0, 1)) ?></span>
                        <?php endif; ?>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="avatarDropdownMenu" style="display: none; position: absolute; right: 0; top: 120%; width: 160px; background: #111; border: 1px solid var(--border-dark); border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); z-index: 1000; overflow: hidden; padding: 0.5rem 0;">
                        <a href="<?= url('/admin/profile') ?>" class="avatar-dropdown-item">👤 Profile</a>
                        <a href="<?= url('/logout') ?>" class="avatar-dropdown-item text-danger">🚪 Logout</a>
                    </div>
                </div>
                
                <!-- Hamburger (Mobile Toggle) -->
                <button class="hamburger" id="mobile-nav-toggle-btn" aria-label="Open menu" style="display: none; flex-direction: column; gap: 5px; background: none; border: none; cursor: pointer; padding: 4px; margin-left: 0.5rem; outline: none; align-items: center; justify-content: center;">
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
        <?php if ($msg = \App\Core\Session::getFlash('success')): ?>
            <div class="flash-message flash-success" style="margin-bottom: 1.5rem;">
                <?= e($msg) ?>
            </div>
        <?php endif; ?>
        <?php if ($msg = \App\Core\Session::getFlash('error')): ?>
            <div class="flash-message flash-error" style="margin-bottom: 1.5rem;">
                <?= e($msg) ?>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('mobile-nav-toggle-btn');
            const closeBtn = document.getElementById('mobile-nav-close');
            const sidebar = document.querySelector('.admin-sidebar');

            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.add('active');
                });
            }

            if (closeBtn && sidebar) {
                closeBtn.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                });
            }

            // Close sidebar when clicking outside of it on mobile views
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 900) {
                    if (sidebar && !sidebar.contains(e.target) && sidebar.classList.contains('active') && !toggleBtn.contains(e.target)) {
                        sidebar.classList.remove('active');
                    }
                }
            });
        });
    </script>
</body>
</html>

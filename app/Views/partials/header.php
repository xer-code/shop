<?php
use App\Core\Auth;
use App\Core\Session;
$cartCount = cartCount();
?>
<!-- Site Header -->
<header class="site-header">
    <div class="header-inner">
        <!-- Logo -->
        <a href="<?= url('/') ?>" class="logo">
            <div class="logo-main">SHOP<span>X</span></div>
            <div class="logo-sub">G L O B A L</div>
        </a>
        
        <!-- Desktop Nav -->
        <nav>
            <ul class="nav-links">
                <li><a href="<?= url('/') ?>" class="<?= activeClass('') ?>">Home</a></li>
                <li><a href="<?= url('/shop') ?>" class="<?= activeClass('shop') ?>">Shop</a></li>
                <li><a href="<?= url('/track-order') ?>" class="<?= activeClass('track-order') ?>">Track Order</a></li>
            </ul>
        </nav>
        
        <!-- Actions -->
        <div class="header-actions">
            <!-- Theme toggle (decorative) -->
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>
            
            <?php if (Auth::check()): ?>
                <!-- Cart -->
                <a href="<?= url('/cart') ?>" class="cart-icon" id="cartIcon">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge" id="cartBadge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
                
                <!-- Wallet -->
                <a href="<?= url('/wallet') ?>" class="wallet-badge">
                    <span class="balance"><?= formatPrice(Auth::wallet()) ?></span>
                    <span style="color: var(--gold-primary); font-size: 0.9rem;">+</span>
                </a>
                
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
                    <button id="avatarDropdownToggle" title="My Account" style="width: 32px; height: 32px; border-radius: 50%; border: 1.5px solid var(--border-gold); overflow: hidden; display: flex; align-items: center; justify-content: center; background: #1a1a1a; cursor: pointer; transition: transform 0.2s; padding: 0; outline: none;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <?php 
                        $userModel = \App\Models\User::find(Auth::id());
                        if ($userModel && !empty($userModel['avatar'])): 
                        ?>
                            <img src="<?= url('/' . $userModel['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <span style="font-size: 0.75rem; font-weight: 700; color: var(--gold-primary);"><?= strtoupper(substr(Auth::name() ?? 'U', 0, 1)) ?></span>
                        <?php endif; ?>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="avatarDropdownMenu" style="display: none; position: absolute; right: 0; top: 120%; width: 160px; background: #111; border: 1px solid var(--border-dark); border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); z-index: 1000; overflow: hidden; padding: 0.5rem 0;">
                        <?php if (Auth::isAdmin()): ?>
                            <!-- Admin Dropdown Items -->
                            <a href="<?= url('/admin/dashboard') ?>" class="avatar-dropdown-item">📊 Admin Console</a>
                            <a href="<?= url('/profile') ?>" class="avatar-dropdown-item">👤 Profile</a>
                            <a href="<?= url('/logout') ?>" class="avatar-dropdown-item text-danger">🚪 Logout</a>
                        <?php else: ?>
                            <!-- Regular User Dropdown Items -->
                            <a href="<?= url('/dashboard') ?>" class="avatar-dropdown-item">📊 Dashboard</a>
                            <a href="<?= url('/profile') ?>" class="avatar-dropdown-item">👤 Profile</a>
                            <a href="<?= url('/') ?>" class="avatar-dropdown-item">🏪 Store</a>
                            <a href="<?= url('/logout') ?>" class="avatar-dropdown-item text-danger">🚪 Logout</a>
                        <?php endif; ?>
                    </div>
                </div>

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
                
                <!-- Hamburger -->
                <button class="hamburger" id="menuToggle" aria-label="Open menu">
                    <span></span><span></span><span></span>
                </button>
            <?php else: ?>
                <!-- Sign In -->
                <a href="<?= url('/login') ?>" class="btn-signin">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Login
                </a>
                
                <!-- Hamburger -->
                <button class="hamburger" id="menuToggle" aria-label="Open menu">
                    <span></span><span></span><span></span>
                </button>
            <?php endif; ?>
        </div>
    </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <a href="<?= url('/') ?>" class="logo">
            <div class="logo-main">SHOP<span>X</span></div>
            <div class="logo-sub">G L O B A L</div>
        </a>
        <button class="close-btn" id="menuClose">&times;</button>
    </div>
    
    <ul class="mobile-nav">
        <li><a href="<?= url('/') ?>"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg> Home</a></li>
        <li><a href="<?= url('/shop') ?>"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg> Shop</a></li>
        <li><a href="<?= url('/track-order') ?>"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg> Track Order</a></li>
    </ul>
    
    <?php if (Auth::check()): ?>
    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-dark);">
        <a href="<?= url('/wallet') ?>" class="wallet-badge" style="margin-bottom: 1rem;">
            <svg width="18" height="18" fill="none" stroke="var(--gold-primary)" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M16 12h.01"/></svg>
            Balance <span class="balance"><?= formatPrice(Auth::wallet()) ?></span>
            <span style="color: var(--gold-primary);">+</span>
        </a>
    </div>
    <?php endif; ?>
    
    <div style="margin-top: auto; padding-top: 2rem;">
        <?php if (!Auth::check()): ?>
            <a href="<?= url('/login') ?>" class="btn-gold" style="width: 100%; justify-content: center; margin-bottom: 0.75rem;">Sign In</a>
            <a href="<?= url('/register') ?>" class="btn-outline" style="width: 100%; justify-content: center;">Create Account</a>
        <?php endif; ?>
    </div>
</div>

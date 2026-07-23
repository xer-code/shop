<!-- Site Footer -->
<footer class="site-footer">
    <!-- Trust Badges -->
    <div class="trust-badges-row">
        <div class="trust-badge">
            <div class="trust-badge-icon">🌍</div>
            <span>190+ Countries Delivered</span>
        </div>
        <div class="trust-badge">
            <div class="trust-badge-icon">🔒</div>
            <span>Secure Checkout</span>
        </div>
        <div class="trust-badge">
            <div class="trust-badge-icon">🚚</div>
            <span>Tracked Worldwide Shipping</span>
        </div>
        <div class="trust-badge">
            <div class="trust-badge-icon">💬</div>
            <span>24/7 Customer Support</span>
        </div>
    </div>
    
    <!-- Footer Content -->
    <div class="footer-content">
        <div class="footer-brand">
            <a href="<?= url('/') ?>" class="logo" style="margin-bottom: 0.5rem;">
                <div class="logo-main">SHOP<span>X</span></div>
                <div class="logo-sub">G L O B A L</div>
            </a>
            <p class="brand-desc">
                Your premium global marketplace. From electronics to fashion — everything delivered anywhere on Earth. Shop smarter, live better.
            </p>
            <div class="footer-buttons">
                <a href="mailto:support@shopx.com" class="footer-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Contact Us
                </a>
                <button class="footer-btn" onclick="toggleChat()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Live Chat
                </button>
            </div>
        </div>
        
        <div class="footer-col">
            <h4>Shop</h4>
            <ul>
                <li><a href="<?= url('/shop') ?>">All Products</a></li>
                <li><a href="<?= url('/shop?category=electronics') ?>">Electronics</a></li>
                <li><a href="<?= url('/shop?category=fashion') ?>">Fashion</a></li>
                <li><a href="<?= url('/shop?category=home-living') ?>">Home & Living</a></li>
                <li><a href="<?= url('/shop?category=gaming') ?>">Gaming</a></li>
            </ul>
        </div>
        
        <div class="footer-col">
            <h4>Account</h4>
            <ul>
                <li><a href="<?= url('/my-orders') ?>">My Orders</a></li>
                <li><a href="<?= url('/track-order') ?>">Track Order</a></li>
                <li><a href="<?= url('/gift-cards') ?>">Gift Cards</a></li>
                <li><a href="<?= url('/virtual-stores') ?>">Virtual Store</a></li>
            </ul>
        </div>
    </div>
    
    <!-- Copyright -->
    <div class="footer-bottom">
        <span>&copy; <?= date('Y') ?> ShopX Global. All rights reserved.</span>
        <div class="footer-legal">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Cookie Policy</a>
        </div>
    </div>
</footer>

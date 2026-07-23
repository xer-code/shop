<?php $pageTitle = 'ShopX Global — Premium Global Marketplace'; ?>

<style>
/* Custom CTA Button Styles */
.btn-cta-white {
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, background-color 0.25s ease;
}
.btn-cta-white:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 12px 30px rgba(255,255,255,0.25);
    background: #f8fafc;
}
.btn-cta-outline {
    transition: background-color 0.25s ease, transform 0.25s ease, border-color 0.25s ease;
}
.btn-cta-outline:hover {
    background: rgba(255,255,255,0.1);
    transform: translateY(-3px);
    border-color: rgba(255,255,255,0.9);
}

/* Category Card Zoom & Rotate effects */
.category-card {
    position: relative;
    overflow: hidden;
}
.category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(212,160,23,0.2);
    border-color: var(--gold-primary) !important;
}
.category-card:hover .card-image-backdrop {
    transform: scale(1.15) rotate(1deg);
}
.category-card:hover .category-icon {
    transform: scale(1.22) translateY(-4px);
}

/* Plane flying animation */
@keyframes planeFly {
    0% { left: -5%; transform: rotate(45deg); opacity: 0; }
    10% { opacity: 1; }
    90% { opacity: 1; }
    100% { left: 90%; transform: rotate(45deg); opacity: 0; }
}

/* Gold Link hover effect */
.link-gold {
    color: var(--gold-primary);
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s ease;
}
.link-gold:hover {
    color: #fff;
}
.link-gold svg {
    transition: transform 0.2s ease;
}
.link-gold:hover svg {
    transform: translateX(5px);
}

/* Floating product item states */
.floating-item {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), z-index 0.3s;
}
.floating-item:hover {
    transform: scale(1.2) rotate(0deg) translateY(-10px) !important;
    z-index: 10 !important;
}

/* Responsive banner override classes */
.home-promo-banner {
    display: grid;
    grid-template-columns: 1.3fr 1fr auto;
    gap: 2rem;
    align-items: center;
}
.home-cta-banner {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 3rem;
    align-items: center;
}
.home-gift-cards-grid {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 2rem;
    align-items: center;
}

@media (max-width: 992px) {
    .home-promo-banner {
        grid-template-columns: 1fr auto !important;
        text-align: left !important;
        padding: 2rem 1.5rem !important;
        gap: 1.5rem !important;
    }
    .promo-center-badge {
        display: none !important;
    }
    .home-promo-banner .floating-products {
        display: none !important;
    }
    .home-promo-banner a {
        width: auto !important;
        justify-content: center;
    }
    
    .home-cta-banner {
        grid-template-columns: 1fr !important;
        gap: 2.5rem;
        padding: 2.5rem 1.5rem !important;
        text-align: center;
    }
    .home-cta-banner div {
        justify-content: center !important;
        align-items: center !important;
    }
    .home-cta-banner .btn-cta-white,
    .home-cta-banner .btn-cta-outline {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .home-gift-cards-grid {
        grid-template-columns: 1fr !important;
        text-align: center;
    }
    .home-gift-cards-grid > div {
        width: 100%;
    }
    .home-gift-cards-grid a {
        width: 100%;
        justify-content: center;
    }
}

.category-grid-home {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 1.5rem;
}

@media (max-width: 576px) {
    .category-grid-home {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 0.75rem !important;
    }
    .category-grid-home a {
        height: 140px !important;
    }
}
</style>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-grid-bg"></div>
    <div class="hero-content fade-in">
        <!-- Eyebrow -->
        <div class="hero-eyebrow">
            <div class="hero-eyebrow-line"></div>
            <span class="hero-eyebrow-text">Premium Global Marketplace</span>
            <div class="hero-eyebrow-line"></div>
        </div>
        
        <!-- Title -->
        <h1 class="hero-title">SHOP<span>X</span></h1>
        <div class="hero-subtitle">G L O B A L</div>
        
        <!-- Tagline -->
        <p class="hero-tagline">Everything you need — delivered anywhere on Earth.</p>
        
        <!-- CTAs -->
        <div class="hero-ctas">
            <a href="<?= url('/shop') ?>" class="btn-gold">
                Shop Now
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <?php if (!\App\Core\Auth::check()): ?>
            <a href="<?= url('/register') ?>" class="btn-outline">
                Create Account
            </a>
            <?php else: ?>
            <a href="<?= url('/my-orders') ?>" class="btn-outline">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                My Orders
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-pill">
                <span class="stat-icon">🛍️</span>
                <span class="stat-value">1M+</span>
                <span class="stat-label">Products</span>
            </div>
            <div class="stat-pill">
                <span class="stat-icon">👥</span>
                <span class="stat-value">500K+</span>
                <span class="stat-label">Shoppers</span>
            </div>
            <div class="stat-pill">
                <span class="stat-icon">🌍</span>
                <span class="stat-value">190+</span>
                <span class="stat-label">Countries</span>
            </div>
            <div class="stat-pill">
                <span class="stat-icon">🔒</span>
                <span class="stat-value">100%</span>
                <span class="stat-label">Secure</span>
            </div>
        </div>
    </div>
</section>

<!-- Section: Worldwide Delivery -->
<section style="padding: 5rem 0; background: radial-gradient(circle at center, rgba(212,160,23,0.03) 0%, transparent 70%); border-bottom: 1px solid #1a1a1a;">
    <div class="container text-center">
        <!-- Badge label -->
        <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.25rem; border: 1px solid rgba(212, 160, 23, 0.3); border-radius: 25px; color: var(--gold-primary); font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1.5rem; background: rgba(212, 160, 23, 0.05);">
            🌐 Global Shipping Network
        </span>
        <!-- Title -->
        <h2 style="font-size: clamp(2rem, 5vw, 3.2rem); font-weight: 900; color: white;">
            We Deliver <span class="text-gold">Worldwide</span>
        </h2>
        <!-- Desc -->
        <p style="color: var(--text-muted); font-size: 1.05rem; max-width: 600px; margin: 1rem auto 3rem; line-height: 1.6;">
            From our warehouse to your doorstep — no matter where you are on the planet.
        </p>
        
        <!-- Transit Visual -->
        <div style="display: flex; align-items: center; justify-content: center; gap: 1.5rem; margin-bottom: 3.5rem; position: relative; max-width: 400px; margin-left: auto; margin-right: auto; padding: 1rem 0;">
             <!-- Globe node -->
             <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(212,160,23,0.1); border: 2px solid var(--gold-primary); display: flex; align-items: center; justify-content: center; font-size: 1.8rem; box-shadow: 0 0 20px rgba(212,160,23,0.2); z-index: 2;">
                 🌐
             </div>
             
             <!-- Dotted connecting line -->
             <div style="flex: 1; height: 2px; border-top: 2px dashed rgba(212,160,23,0.3); position: relative; display: flex; align-items: center; justify-content: center;">
                 <!-- Moving Plane -->
                 <span style="font-size: 1.4rem; transform: rotate(45deg); position: absolute; animation: planeFly 4s linear infinite; color: var(--gold-primary);">✈️</span>
             </div>
             
             <!-- Target Destination node -->
             <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(59,130,246,0.1); border: 2px solid #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; box-shadow: 0 0 20px rgba(59,130,246,0.2); z-index: 2;">
                 📍
             </div>
        </div>
        
        <!-- Outlined Pills row -->
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 1rem;">
             <span style="padding: 0.65rem 1.5rem; border: 1px solid #2a2a2a; background: #111; color: var(--text-secondary); border-radius: 30px; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                 🌎 190+ Countries
             </span>
             <span style="padding: 0.65rem 1.5rem; border: 1px solid #2a2a2a; background: #111; color: var(--text-secondary); border-radius: 30px; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                 📦 Tracked Shipping
             </span>
             <span style="padding: 0.65rem 1.5rem; border: 1px solid #2a2a2a; background: #111; color: var(--text-secondary); border-radius: 30px; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                 🛡️ Insured Delivery
             </span>
             <span style="padding: 0.65rem 1.5rem; border: 1px solid #2a2a2a; background: #111; color: var(--text-secondary); border-radius: 30px; font-size: 0.9rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                 🚪 Door-to-Door
             </span>
        </div>
    </div>
</section>

<!-- Country Ticker -->
<section class="ticker-section" style="border-bottom: 1px solid #1a1a1a; padding: 1rem 0; background: #0c0c0c;">
    <div style="overflow: hidden;">
        <div class="ticker-track">
            <?php 
            $countries = ['🇫🇷 France', '🇯🇵 Japan', '🇦🇺 Australia', '🇨🇦 Canada', '🇮🇳 India', '🇧🇷 Brazil', '🇿🇦 South Africa', '🇸🇬 Singapore', '🇦🇪 UAE', '🇳🇬 Nigeria', '🇬🇧 UK', '🇩🇪 Germany', '🇰🇷 South Korea', '🇲🇽 Mexico', '🇮🇹 Italy'];
            foreach ($countries as $c): ?>
                <span class="ticker-item"><?= $c ?> •</span>
            <?php endforeach; ?>
            <?php foreach ($countries as $c): ?>
                <span class="ticker-item"><?= $c ?> •</span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Section: Shop by Category -->
<section style="padding: 5rem 0; border-bottom: 1px solid #1a1a1a;">
    <div class="container">
        <!-- Section Header -->
        <div class="text-center" style="margin-bottom: 3.5rem;">
             <h2 style="font-size: 2.4rem; font-weight: 900; color: white;">Shop by <span class="text-gold">Category</span></h2>
             <p style="color: var(--text-muted); font-size: 1.05rem; margin-top: 0.5rem;">Explore our curated collections</p>
        </div>
        
        <?php
        $catStyles = [
            'electronics' => [
                'bg' => 'linear-gradient(135deg, rgba(14, 165, 233, 0.45), rgba(37, 99, 235, 0.45))',
                'border' => 'rgba(14, 165, 233, 0.3)',
                'img' => asset('images/products/macbook-pro.jpg')
            ],
            'fashion' => [
                'bg' => 'linear-gradient(135deg, rgba(244, 63, 94, 0.45), rgba(217, 70, 239, 0.45))',
                'border' => 'rgba(244, 63, 94, 0.3)',
                'img' => asset('images/products/leather-jacket.jpg')
            ],
            'home-living' => [
                'bg' => 'linear-gradient(135deg, rgba(234, 179, 8, 0.45), rgba(249, 115, 22, 0.45))',
                'border' => 'rgba(234, 179, 8, 0.3)',
                'img' => asset('images/products/espresso-machine.jpg')
            ],
            'gaming' => [
                'bg' => 'linear-gradient(135deg, rgba(236, 72, 153, 0.45), rgba(219, 39, 119, 0.45))',
                'border' => 'rgba(236, 72, 153, 0.3)',
                'img' => asset('images/products/ps5-pro.jpg')
            ],
            'automotive' => [
                'bg' => 'linear-gradient(135deg, rgba(16, 185, 129, 0.45), rgba(5, 150, 105, 0.45))',
                'border' => 'rgba(16, 185, 129, 0.3)',
                'img' => asset('images/products/dash-cam-4k.jpg')
            ]
        ];
        ?>
        
        <!-- Category Grid -->
        <div class="category-grid-home">
             <?php foreach ($categories as $cat): ?>
                 <?php
                 $slug = $cat['slug'];
                 $style = $catStyles[$slug] ?? [
                     'bg' => 'linear-gradient(135deg, rgba(163, 138, 92, 0.45), rgba(136, 110, 69, 0.45))',
                     'border' => 'rgba(163, 138, 92, 0.3)',
                     'img' => asset('images/placeholder.jpg')
                 ];
                 ?>
                 <a href="<?= url('/shop?category=' . $cat['id']) ?>" class="category-card" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 180px; border-radius: 20px; border: 1px solid <?= $style['border'] ?>; background: <?= $style['bg'] ?>; text-decoration: none; position: relative; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.25); transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease, border-color 0.3s;">
                      <!-- Card Image Backdrop -->
                      <div class="card-image-backdrop" style="position: absolute; inset: 0; background-image: url('<?= $style['img'] ?>'); background-size: cover; background-position: center; filter: brightness(0.25) blur(1.5px) contrast(1.1); transition: transform 0.5s ease; z-index: 1;"></div>
                      
                      <!-- Overlay color layer -->
                      <div style="position: absolute; inset: 0; background: <?= $style['bg'] ?>; opacity: 0.6; mix-blend-mode: multiply; z-index: 2;"></div>
                      
                      <!-- Icon -->
                      <span class="category-icon" style="font-size: 2.5rem; position: relative; z-index: 3; filter: drop-shadow(0 4px 10px rgba(0,0,0,0.6)); transition: transform 0.3s ease; transform-origin: bottom center;"><?= e($cat['icon']) ?></span>
                      
                      <!-- Name -->
                      <span style="font-size: 1.1rem; font-weight: 700; color: white; margin-top: 1rem; position: relative; z-index: 3; text-shadow: 0 2px 10px rgba(0,0,0,0.8); text-align: center; padding: 0 0.5rem;"><?= e($cat['name']) ?></span>
                      
                      <!-- Product Count -->
                      <span style="font-size: 0.7rem; color: rgba(255,255,255,0.7); margin-top: 0.25rem; position: relative; z-index: 3; font-family: monospace; background: rgba(0,0,0,0.4); padding: 0.15rem 0.5rem; border-radius: 10px;"><?= $cat['product_count'] ?> Products</span>
                 </a>
             <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Section: Promo Banner -->
<section style="padding: 3rem 0; border-bottom: 1px solid #1a1a1a;">
    <div class="container">
        <div class="promo-banner home-promo-banner" style="background: linear-gradient(135deg, #0e0e0e, #181818); border: 1px solid #222; border-radius: 24px; padding: 2.5rem 3.5rem; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.5);">
             <!-- World map background graphic -->
             <div class="map-bg" style="position: absolute; inset: 0; opacity: 0.04; background-image: radial-gradient(#D4A017 1px, transparent 1px); background-size: 20px 20px;"></div>
             
             <!-- Left Content -->
             <div style="position: relative; z-index: 2;">
                 <span style="font-size: 0.75rem; font-weight: 700; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.1em; display: inline-flex; align-items: center; gap: 0.5rem;">
                     ⚡ LIMITED TIME
                 </span>
                 <h2 style="font-size: 2.5rem; font-weight: 900; color: white; line-height: 1.1; margin-top: 0.75rem; letter-spacing: -0.01em; text-transform: uppercase;">
                     MEGA SALE<br><span style="color: white;">EVENT</span>
                 </h2>
                 <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.75rem; max-width: 380px; line-height: 1.5;">
                     Up to 70% off on premium products
                 </p>
             </div>
             
             <!-- Center 70% OFF -->
             <div class="promo-center-badge" style="position: relative; z-index: 2; text-align: center; border-left: 1px solid #222; border-right: 1px solid #222; padding: 0 2.5rem;">
                 <div style="font-size: 4.8rem; font-weight: 950; line-height: 1; background: linear-gradient(135deg, #fff 25%, #D4A017 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 2px 12px rgba(212,160,23,0.25));">
                     70% OFF
                 </div>
                 <div style="font-size: 0.75rem; font-weight: 700; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.25em; margin-top: 0.5rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                     🎁 EXCLUSIVE SALE
                 </div>
             </div>
             
             <!-- Right Action & Images -->
             <div style="position: relative; z-index: 2; display: flex; align-items: center; gap: 2rem;">
                 <div class="floating-products" style="display: flex; align-items: center; position: relative; width: 160px; height: 90px; margin-right: 1rem;">
                     <img class="floating-item" src="<?= asset('images/products/swiss-watch.jpg') ?>" alt="" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid #000; position: absolute; left: 0; z-index: 1; transform: rotate(-10deg); box-shadow: 0 10px 20px rgba(0,0,0,0.5); cursor: pointer;">
                     <img class="floating-item" src="<?= asset('images/products/iphone-15.jpg') ?>" alt="" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid #000; position: absolute; left: 40px; z-index: 3; transform: scale(1.1); box-shadow: 0 10px 25px rgba(0,0,0,0.6); cursor: pointer;">
                     <img class="floating-item" src="<?= asset('images/products/sony-headphones.jpg') ?>" alt="" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 2px solid #000; position: absolute; left: 80px; z-index: 2; transform: rotate(10deg); box-shadow: 0 10px 20px rgba(0,0,0,0.5); cursor: pointer;">
                 </div>
                 <a href="<?= url('/shop') ?>" class="btn-gold" style="padding: 0.85rem 1.75rem; font-size: 0.9rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; border-radius: 30px; white-space: nowrap;">
                     Browse all
                     <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                 </a>
             </div>
        </div>
    </div>
</section>

<!-- Section: Trending Now -->
<section style="padding: 5rem 0; border-bottom: 1px solid #1a1a1a;">
    <div class="container">
        <!-- Section Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3.5rem;">
             <div>
                 <!-- Badge -->
                 <span style="display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.35rem 0.85rem; background: rgba(124,58,237,0.15); border: 1px solid rgba(124,58,237,0.3); border-radius: 20px; color: #a78bfa; font-size: 0.75rem; font-weight: 700; margin-bottom: 0.75rem; text-transform: uppercase;">
                     ✨ Featured
                 </span>
                 <h2 style="font-size: 2.4rem; font-weight: 900; color: white;">Trending <span class="text-gold">Now</span></h2>
                 <p style="color: var(--text-muted); font-size: 1.05rem; margin-top: 0.25rem;">Handpicked favorites from around the globe</p>
             </div>
             
             <a href="<?= url('/shop') ?>" class="link-gold">
                 View All Products
                 <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
             </a>
        </div>
        
        <!-- Product Grid -->
        <div class="product-grid">
             <?php foreach ($hotProducts as $product): ?>
                 <?php \App\Core\View::partial('product-card', ['product' => $product]); ?>
             <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (!\App\Core\Auth::check()): ?>
<!-- Section: CTA Registration Banner -->
<section style="padding: 3rem 0; border-bottom: 1px solid #1a1a1a;">
    <div class="container">
        <div class="cta-banner home-cta-banner" style="background: linear-gradient(135deg, #4f46e5, #7c3aed, #db2777); border-radius: 24px; padding: 3.5rem; position: relative; overflow: hidden; box-shadow: 0 20px 45px rgba(79, 70, 229, 0.25);">
             <!-- Background glowing bubble -->
             <div style="position: absolute; top: -50px; right: -50px; width: 250px; height: 250px; border-radius: 50%; background: rgba(255,255,255,0.1); filter: blur(30px); pointer-events: none;"></div>
             
             <!-- Left content -->
             <div style="position: relative; z-index: 2;">
                 <span style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; background: rgba(255,255,255,0.15); border-radius: 20px; color: white; font-size: 0.8rem; font-weight: 600; margin-bottom: 1.5rem;">
                     ✨ Join 500,000+ Shoppers
                 </span>
                 <h2 style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: white; line-height: 1.15;">
                     Sign Up & Get <br><span style="color: #ffe066;">$25 Free Credit</span>
                 </h2>
                 <p style="color: rgba(255,255,255,0.85); font-size: 1rem; margin-top: 1rem; max-width: 480px; line-height: 1.6;">
                     Create your free account and unlock exclusive deals, wallet system, gift cards, and premium shopping experience.
                 </p>
                 <div style="display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">
                     <a href="<?= url('/register') ?>" class="btn-cta-white" style="padding: 0.85rem 1.75rem; background: white; color: #4f46e5; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">
                         Create Free Account
                         <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                     </a>
                     <a href="<?= url('/login') ?>" class="btn-cta-outline" style="padding: 0.85rem 1.75rem; border: 1.5px solid white; background: transparent; color: white; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                         Sign In
                     </a>
                 </div>
             </div>
             
             <!-- Right checklist -->
             <div style="position: relative; z-index: 2; display: flex; flex-direction: column; gap: 1rem;">
                  <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; color: white; backdrop-filter: blur(5px);">
                      <span style="font-size: 1.5rem;">🎁</span>
                      <div>
                          <h4 style="font-weight: 700; font-size: 0.95rem;">$25 Welcome Bonus</h4>
                          <p style="font-size: 0.75rem; color: rgba(255,255,255,0.75); margin-top: 0.15rem;">Credited instantly to your wallet upon registration</p>
                      </div>
                  </div>
                  <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; color: white; backdrop-filter: blur(5px);">
                      <span style="font-size: 1.5rem;">🛡️</span>
                      <div>
                          <h4 style="font-weight: 700; font-size: 0.95rem;">Secure Wallet System</h4>
                          <p style="font-size: 0.75rem; color: rgba(255,255,255,0.75); margin-top: 0.15rem;">Complete fraud prevention and encrypted transit</p>
                      </div>
                  </div>
                  <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); border-radius: 16px; color: white; backdrop-filter: blur(5px);">
                      <span style="font-size: 1.5rem;">⚡</span>
                      <div>
                          <h4 style="font-weight: 700; font-size: 0.95rem;">Instant Checkout</h4>
                          <p style="font-size: 0.75rem; color: rgba(255,255,255,0.75); margin-top: 0.15rem;">Buy seamlessly with single-tap wallet funds</p>
                      </div>
                  </div>
             </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Gift Cards Section -->
<section style="padding: 5rem 0; border-bottom: 1px solid #1a1a1a;">
    <div class="container">
        <div class="card" style="padding: 3rem; position: relative; overflow: hidden; background: #111;">
            <!-- Background text -->
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: clamp(3rem, 8vw, 6rem); font-weight: 900; color: rgba(255,255,255,0.02); white-space: nowrap; pointer-events: none;">
                GIFT CARDS AVAILABLE
            </div>
            
            <div class="home-gift-cards-grid" style="position: relative; z-index: 2;">
                <div>
                    <span class="badge-category" style="background: var(--gold-primary); color: #000; margin-bottom: 1rem; display: inline-flex;">🎁 GIFT CARDS</span>
                    <h2 style="font-size: clamp(1.5rem, 4vw, 2.5rem); font-weight: 900; margin-top: 1rem; line-height: 1.2; color: white;">
                        Give the Gift<br><span class="text-gold">of Choice</span>
                    </h2>
                    <p style="color: var(--text-muted); margin-top: 0.75rem; max-width: 400px;">
                        Purchase gift cards for loved ones or redeem yours instantly — values from $25 to $1,000.
                    </p>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <a href="<?= url('/gift-cards') ?>" class="btn-gold">
                        🎁 Get Your Gift Card
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="<?= url('/gift-cards') ?>" class="btn-outline">
                        💳 Redeem Your Gift Card
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PWA Install Banner -->
<section class="container" id="pwaSection" style="display: none; padding: 3rem 0;">
    <div class="pwa-banner" id="pwaBanner">
        <div class="pwa-banner-icon">📱</div>
        <div>
            <h3>Install ShopX-Global</h3>
            <p>Add to your home screen for quick access</p>
        </div>
        <button class="pwa-install-btn" id="pwaInstallBtn" onclick="installPWA()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Install Now
        </button>
    </div>
</section>

<?php
/** @var array $product */
$savings = ($product['original_price'] ?? 0) - $product['price'];
$hasDiscount = $product['discount_percent'] > 0;
$isHot = $product['is_hot'] ?? false;
$isLowStock = ($product['stock'] ?? 100) <= 10 && ($product['stock'] ?? 100) > 0;
$categoryName = $product['category_name'] ?? 'Shop';
?>
<div class="product-card" id="product-<?= $product['id'] ?>">
    <a href="<?= url('/shop/' . $product['id']) ?>" style="text-decoration: none; color: inherit;">
        <div class="image-wrapper">
            <!-- Product Image -->
            <img src="<?= e($product['image_url'] ?: asset('images/placeholder.jpg')) ?>" 
                 alt="<?= e($product['title']) ?>"
                 loading="lazy"
                 onerror="this.src='https://placehold.co/400x300/1a1a1a/666?text=<?= urlencode($product['title']) ?>'">
            
            <!-- Badges -->
            <div class="badges">
                <?php if ($isHot): ?>
                    <span class="badge-hot">✨ HOT</span>
                <?php endif; ?>
                <?php if ($hasDiscount): ?>
                    <span class="badge-discount">-<?= $product['discount_percent'] ?>% OFF</span>
                <?php endif; ?>
                <?php if ($isLowStock): ?>
                    <span class="badge-urgency">Only <?= $product['stock'] ?> left!</span>
                <?php endif; ?>
            </div>
        </div>
    </a>
    
    <!-- Wishlist Button -->
    <button class="wishlist-btn" onclick="toggleWishlist(<?= $product['id'] ?>, this)" aria-label="Toggle wishlist">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
    </button>
    
    <div class="card-body">
        <!-- Category Tag -->
        <span class="badge-category"><?= e($categoryName) ?></span>
        
        <!-- Title -->
        <h3 style="font-size: 1rem; font-weight: 700; margin-top: 0.5rem; line-height: 1.3;">
            <a href="<?= url('/shop/' . $product['id']) ?>" style="color: inherit; text-decoration: none;">
                <?= e(truncate($product['title'], 50)) ?>
            </a>
        </h3>
        
        <!-- Description -->
        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem; line-height: 1.4;">
            <?= e(truncate($product['description'] ?? '', 60)) ?>
        </p>
        
        <!-- Rating -->
        <div style="margin-top: 0.5rem;">
            <?= starRating($product['rating'] ?? 0, $product['review_count'] ?? 0) ?>
        </div>
        
        <!-- Price -->
        <div class="price-row">
            <span class="price-current"><?= formatPrice($product['price']) ?></span>
            <?php if ($product['original_price'] && $product['original_price'] > $product['price']): ?>
                <span class="price-original"><?= formatPrice($product['original_price']) ?></span>
                <span class="price-save">Save <?= formatPrice($savings) ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

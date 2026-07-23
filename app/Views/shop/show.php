<?php
$savings = ($product['original_price'] ?? 0) - $product['price'];
?>
<section class="container fade-in" style="padding: 2rem 0;">
    <a href="<?= url('/shop') ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.9rem;">
        ← Back to Shop
    </a>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
        <!-- Product Image -->
        <div style="border-radius: 16px; overflow: hidden; background: var(--bg-card); border: 1px solid var(--border-dark);">
            <img src="<?= e($product['image_url'] ?: '') ?>" alt="<?= e($product['title']) ?>" 
                 style="width: 100%; aspect-ratio: 4/3; object-fit: cover;"
                 onerror="this.src='https://placehold.co/600x450/1a1a1a/666?text=<?= urlencode($product['title']) ?>'">
        </div>
        
        <!-- Product Details -->
        <div>
            <span class="badge-category"><?= e($product['category_name']) ?></span>
            
            <?php if ($product['is_hot']): ?>
                <span class="badge-hot" style="margin-left: 0.5rem;">✨ HOT</span>
            <?php endif; ?>
            
            <h1 style="font-size: 2rem; font-weight: 800; margin-top: 1rem; line-height: 1.2;"><?= e($product['title']) ?></h1>
            
            <div style="margin-top: 1rem;">
                <?= starRating($product['rating'], $product['review_count']) ?>
            </div>
            
            <p style="color: var(--text-secondary); margin-top: 1rem; line-height: 1.7;"><?= e($product['description']) ?></p>
            
            <div class="price-row" style="margin-top: 1.5rem; font-size: 1.2rem;">
                <span class="price-current" style="font-size: 2rem;"><?= formatPrice($product['price']) ?></span>
                <?php if ($product['original_price'] > $product['price']): ?>
                    <span class="price-original" style="font-size: 1rem;"><?= formatPrice($product['original_price']) ?></span>
                    <span class="badge-discount">-<?= $product['discount_percent'] ?>%</span>
                    <span class="price-save" style="font-size: 0.9rem;">Save <?= formatPrice($savings) ?></span>
                <?php endif; ?>
            </div>
            
            <p style="color: <?= $product['stock'] > 10 ? 'var(--green-save)' : 'var(--orange-badge)' ?>; font-size: 0.85rem; margin-top: 0.5rem;">
                <?= $product['stock'] > 10 ? '✓ In Stock' : 'Only ' . $product['stock'] . ' left!' ?>
            </p>
            
            <!-- Add to Cart -->
            <form method="POST" action="<?= url('/cart/add') ?>" style="margin-top: 2rem; display: flex; gap: 1rem; align-items: center;">
                <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                <div style="display: flex; align-items: center; gap: 0; border: 1px solid var(--border-dark); border-radius: 8px; overflow: hidden;">
                    <button type="button" onclick="this.nextElementSibling.stepDown(); this.nextElementSibling.dispatchEvent(new Event('change'))" 
                            style="width: 40px; height: 40px; background: var(--bg-card); border: none; color: white; font-size: 1.2rem; cursor: pointer;">−</button>
                    <input type="number" name="qty" value="1" min="1" max="<?= $product['stock'] ?>" 
                           style="width: 50px; text-align: center; background: var(--bg-secondary); border: none; color: white; font-size: 1rem; height: 40px;">
                    <button type="button" onclick="this.previousElementSibling.stepUp(); this.previousElementSibling.dispatchEvent(new Event('change'))" 
                            style="width: 40px; height: 40px; background: var(--bg-card); border: none; color: white; font-size: 1.2rem; cursor: pointer;">+</button>
                </div>
                <button type="submit" class="btn-gold" style="flex: 1;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Add to Cart
                </button>
            </form>
        </div>
    </div>
    
    <!-- Related Products -->
    <?php if (!empty($related)): ?>
    <div style="margin-top: 4rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem;">Related Products</h2>
        <div class="product-grid">
            <?php foreach (array_slice($related, 0, 4) as $rp): ?>
                <?php $rp['category_name'] = $product['category_name']; ?>
                <?php \App\Core\View::partial('product-card', ['product' => $rp]); ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>

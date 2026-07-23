<?php $pageTitle = 'Your Cart — ShopX Global'; ?>
<section class="container fade-in" style="padding: 2rem 0; max-width: 800px;">
    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(124, 58, 237, 0.15); display: flex; align-items: center; justify-content: center;">
            🛒
        </div>
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 700;">Your Cart</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;"><?= count($items) ?> items</p>
        </div>
    </div>
    
    <?php if (empty($items)): ?>
        <div class="card" style="text-align: center; padding: 3rem;">
            <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 1rem;">Your cart is empty</p>
            <a href="<?= url('/shop') ?>" class="btn-gold">Start Shopping →</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($items as $item): ?>
                <div class="card" style="display: flex; align-items: center; gap: 1rem; padding: 1rem;">
                    <img src="<?= e($item['image_url'] ?: '') ?>" alt="<?= e($item['title']) ?>" 
                         style="width: 100px; height: 80px; object-fit: cover; border-radius: 8px; background: var(--bg-secondary);"
                         onerror="this.src='https://placehold.co/100x80/1a1a1a/666?text=Product'">
                    
                    <div style="flex: 1;">
                        <h3 style="font-weight: 600; font-size: 0.95rem;"><?= e($item['title']) ?></h3>
                        <p style="color: var(--gold-primary); font-weight: 700; margin-top: 0.25rem;"><?= formatPrice($item['price']) ?></p>
                    </div>
                    
                    <!-- Qty Controls -->
                    <form method="POST" action="<?= url('/cart/update') ?>" style="display: flex; align-items: center; gap: 0;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <button type="submit" name="qty" value="<?= max(1, $item['qty'] - 1) ?>" 
                                style="width: 36px; height: 36px; background: var(--bg-secondary); border: none; color: white; border-radius: 8px; cursor: pointer; font-size: 1.1rem;">−</button>
                        <span style="width: 40px; text-align: center; font-weight: 600;"><?= $item['qty'] ?></span>
                        <button type="submit" name="qty" value="<?= $item['qty'] + 1 ?>" 
                                style="width: 36px; height: 36px; background: var(--bg-secondary); border: none; color: white; border-radius: 8px; cursor: pointer; font-size: 1.1rem;">+</button>
                    </form>
                    
                    <!-- Remove -->
                    <form method="POST" action="<?= url('/cart/remove') ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.5rem;" title="Remove">
                            🗑️
                        </button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Subtotal & Checkout -->
        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--border-dark); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="color: var(--text-muted);">Subtotal</span>
                <span style="font-size: 1.5rem; font-weight: 800; margin-left: 1rem;"><?= formatPrice($total) ?></span>
            </div>
            <a href="<?= url('/checkout') ?>" class="btn-gold" style="padding: 0.9rem 2.5rem;">
                Checkout
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    <?php endif; ?>
</section>

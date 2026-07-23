<div class="fade-in space-y-6">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">❤️ Saved Products</h2>
        <a href="<?= url('/shop') ?>" style="font-size: 0.7rem; color: #D4A017; text-decoration: none; font-weight: 600;">Browse Shop →</a>
    </div>

    <?php if (empty($products)): ?>
        <div style="text-align: center; padding: 3rem; background: #151515; border: 1px solid #1a1a1a; border-radius: 12px;">
            <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">💔</p>
            <p style="color: #555; font-size: 0.85rem;">Your wishlist is empty. Start saving products you love!</p>
            <a href="<?= url('/shop') ?>" class="btn-gold" style="margin-top: 1rem; display: inline-flex; font-size: 0.8rem; padding: 0.5rem 1.5rem; text-decoration: none; border-radius: 8px;">Explore Products →</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
            <?php foreach ($products as $p): ?>
                <a href="<?= url('/shop/' . $p['slug']) ?>" style="text-decoration: none; background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; overflow: hidden; transition: border-color 0.2s;" onmouseover="this.style.borderColor='rgba(212,160,23,0.3)'" onmouseout="this.style.borderColor='#1a1a1a'">
                    <div style="height: 160px; background: #111; overflow: hidden;">
                        <?php if (!empty($p['image_url'])): ?>
                            <img src="<?= url('/' . $p['image_url']) ?>" alt="<?= e($p['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #333; font-size: 2rem;">📦</div>
                        <?php endif; ?>
                    </div>
                    <div style="padding: 0.85rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= e($p['title']) ?></div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                            <span style="font-size: 0.9rem; font-weight: 800; color: #D4A017; font-family: monospace;"><?= formatPrice($p['price']) ?></span>
                            <span style="font-size: 0.65rem; color: #f43f5e;">❤️</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

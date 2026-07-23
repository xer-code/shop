<?php $pageTitle = 'Virtual Stores — ShopX Global'; ?>
<section class="container fade-in" style="padding: 2rem 0;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">🏪 Virtual Stores</h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">Browse curated stores from our trusted sellers</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
        <?php foreach ($stores as $store): ?>
            <a href="<?= url('/virtual-stores/' . $store['id']) ?>" class="card" style="text-decoration: none;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark)); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #000; font-weight: 900;">
                        <?= strtoupper(substr($store['name'], 0, 1)) ?>
                    </div>
                    <div>
                        <h3 style="font-weight: 700;"><?= e($store['name']) ?></h3>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">by <?= e($store['owner_name'] ?? 'ShopX') ?></p>
                    </div>
                </div>
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1rem;"><?= e(truncate($store['description'] ?? '', 100)) ?></p>
                <span style="font-size: 0.85rem; color: var(--gold-primary); font-weight: 600;"><?= $store['product_count'] ?> products →</span>
            </a>
        <?php endforeach; ?>
    </div>
</section>

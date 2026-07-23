<?php $pageTitle = $store['name'] . ' — ShopX Global'; ?>
<section class="container fade-in" style="padding: 2rem 0;">
    <a href="<?= url('/virtual-stores') ?>" style="color: var(--text-muted); font-size: 0.9rem;">← Back to Stores</a>
    
    <div style="display: flex; align-items: center; gap: 1.5rem; margin: 1.5rem 0 2rem;">
        <div style="width: 70px; height: 70px; border-radius: 16px; background: linear-gradient(135deg, var(--gold-primary), var(--gold-dark)); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: #000; font-weight: 900;">
            <?= strtoupper(substr($store['name'], 0, 1)) ?>
        </div>
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800;"><?= e($store['name']) ?></h1>
            <p style="color: var(--text-muted);"><?= e($store['description'] ?? '') ?></p>
        </div>
    </div>
    
    <p style="color: var(--text-muted); margin-bottom: 1.5rem;"><?= count($products) ?> products</p>
    
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <?php \App\Core\View::partial('product-card', ['product' => $product]); ?>
        <?php endforeach; ?>
    </div>
</section>

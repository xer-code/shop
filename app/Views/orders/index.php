<?php $pageTitle = 'My Orders — ShopX Global'; ?>
<section class="container fade-in" style="padding: 2rem 0;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem;">📦 My Orders</h1>
    
    <?php if (empty($orders)): ?>
        <div class="card" style="text-align: center; padding: 3rem;">
            <p style="color: var(--text-muted); margin-bottom: 1rem;">No orders yet</p>
            <a href="<?= url('/shop') ?>" class="btn-gold">Start Shopping →</a>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($orders as $order): ?>
                <a href="<?= url('/orders/' . $order['id']) ?>" class="card" style="display: grid; grid-template-columns: 1fr auto auto; gap: 1.5rem; align-items: center; text-decoration: none;">
                    <div>
                        <p style="font-weight: 700;">Order #<?= $order['id'] ?></p>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">
                            <?= date('M j, Y', strtotime($order['created_at'])) ?> • <?= $order['item_count'] ?> items
                        </p>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                            Tracking: <span class="text-gold"><?= e($order['tracking_code'] ?? 'N/A') ?></span>
                        </p>
                    </div>
                    <span style="font-weight: 700; font-size: 1.1rem;"><?= formatPrice($order['total']) ?></span>
                    <span class="badge-status badge-<?= $order['status'] ?>"><?= $order['status'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

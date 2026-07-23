<?php
$pageTitle = 'Order #' . $order['id'] . ' — ShopX Global';
$statuses = ['pending', 'processing', 'shipped', 'delivered'];
$currentIndex = array_search($order['status'], $statuses);
if ($currentIndex === false) $currentIndex = -1;
$icons = ['📋', '⚙️', '🚚', '✅'];
?>
<section class="container fade-in" style="padding: 2rem 0; max-width: 800px;">
    <a href="<?= url('/my-orders') ?>" style="color: var(--text-muted); font-size: 0.9rem;">← Back to Orders</a>
    
    <h1 style="font-size: 1.5rem; font-weight: 700; margin: 1rem 0;">Order #<?= $order['id'] ?></h1>
    
    <!-- Status Timeline -->
    <div class="card" style="margin-bottom: 2rem;">
        <div class="timeline">
            <?php foreach ($statuses as $i => $s): ?>
                <div class="timeline-step <?= $i < $currentIndex ? 'completed' : ($i === $currentIndex ? 'active' : '') ?>">
                    <div class="timeline-dot"><?= $icons[$i] ?></div>
                    <div class="timeline-label"><?= ucfirst($s) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Order Info -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div><span style="color: var(--text-muted); font-size: 0.85rem;">Tracking Code</span><br><strong class="text-gold"><?= e($order['tracking_code'] ?? 'N/A') ?></strong></div>
            <div><span style="color: var(--text-muted); font-size: 0.85rem;">Date</span><br><strong><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></strong></div>
            <div><span style="color: var(--text-muted); font-size: 0.85rem;">Payment</span><br><strong><?= ucfirst(e($order['payment_method'])) ?></strong></div>
            <div><span style="color: var(--text-muted); font-size: 0.85rem;">Shipping</span><br><strong><?= e($order['shipping_address']) ?></strong></div>
        </div>
    </div>
    
    <!-- Items -->
    <div class="card">
        <h3 style="font-weight: 700; margin-bottom: 1rem;">Items</h3>
        <?php foreach ($order['items'] as $item): ?>
            <div style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border-dark);">
                <img src="<?= e($item['image_url'] ?: '') ?>" alt="" style="width: 60px; height: 50px; object-fit: cover; border-radius: 6px; background: var(--bg-secondary);"
                     onerror="this.src='https://placehold.co/60x50/1a1a1a/666'">
                <div style="flex: 1;">
                    <p style="font-weight: 500; font-size: 0.9rem;"><?= e($item['title']) ?></p>
                    <p style="font-size: 0.8rem; color: var(--text-muted);">Qty: <?= $item['qty'] ?></p>
                </div>
                <span style="font-weight: 600;"><?= formatPrice($item['price_at_purchase'] * $item['qty']) ?></span>
            </div>
        <?php endforeach; ?>
        <div style="display: flex; justify-content: space-between; padding-top: 1rem; margin-top: 0.5rem;">
            <span style="font-weight: 700; font-size: 1.1rem;">Total</span>
            <span style="font-weight: 800; font-size: 1.3rem; color: var(--gold-primary);"><?= formatPrice($order['total']) ?></span>
        </div>
    </div>
</section>

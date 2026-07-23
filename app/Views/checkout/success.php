<?php $pageTitle = 'Order Confirmed — ShopX Global'; ?>
<section class="container fade-in" style="padding: 3rem 0; text-align: center; max-width: 600px;">
    <div style="font-size: 4rem; margin-bottom: 1rem;">🎉</div>
    <h1 style="font-size: 2rem; font-weight: 800;">Order Confirmed!</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Thank you for your purchase. Your order has been placed successfully.</p>
    
    <div class="card" style="margin-top: 2rem; text-align: left;">
        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
            <span style="color: var(--text-muted);">Order ID</span>
            <span style="font-weight: 700;">#<?= $order['id'] ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
            <span style="color: var(--text-muted);">Tracking Code</span>
            <span style="font-weight: 700; color: var(--gold-primary);"><?= e($order['tracking_code']) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 1rem;">
            <span style="color: var(--text-muted);">Total</span>
            <span style="font-weight: 700;"><?= formatPrice($order['total']) ?></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span style="color: var(--text-muted);">Status</span>
            <span class="badge-status badge-pending"><?= $order['status'] ?></span>
        </div>
    </div>
    
    <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
        <a href="<?= url('/my-orders') ?>" class="btn-gold">View My Orders</a>
        <a href="<?= url('/shop') ?>" class="btn-outline">Continue Shopping</a>
    </div>
</section>

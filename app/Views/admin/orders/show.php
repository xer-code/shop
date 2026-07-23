<div style="margin-bottom: 2rem;">
    <a href="<?= url('/admin/orders') ?>" style="color: var(--text-muted); font-size: 0.9rem;">
        ← Back to Order Console
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; align-items: start;">
    <!-- Left Column: Order Items & Customer -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Customer details -->
        <div class="card">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">👥 Customer Details</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Name</span><br>
                    <strong style="color: white;"><?= e($order['customer_name']) ?></strong>
                </div>
                <div>
                    <span style="font-size: 0.8rem; color: var(--text-muted);">Email Address</span><br>
                    <strong style="color: white;"><?= e($order['customer_email']) ?></strong>
                </div>
            </div>
        </div>

        <!-- Items list -->
        <div class="card">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">🛍️ Order Items</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <?php foreach ($order['items'] as $item): ?>
                    <div style="display: flex; align-items: center; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-dark);">
                        <img src="<?= e($item['image_url']) ?>" alt="" style="width: 70px; height: 55px; object-fit: cover; border-radius: 6px; background: var(--bg-secondary);"
                             onerror="this.src='https://placehold.co/70x55/1a1a1a/666'">
                        <div style="flex: 1;">
                            <h4 style="font-weight: 600; font-size: 0.9rem; color: white;"><?= e($item['title']) ?></h4>
                            <p style="font-size: 0.8rem; color: var(--text-muted);">Quantity: <?= $item['qty'] ?> • Unit Price: <?= formatPrice($item['price_at_purchase']) ?></p>
                        </div>
                        <span style="font-weight: 700; color: white;"><?= formatPrice($item['price_at_purchase'] * $item['qty']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
                <span style="font-size: 1.1rem; font-weight: 700; color: white;">Order Total:</span>
                <span style="font-size: 1.4rem; font-weight: 800; color: var(--gold-primary);"><?= formatPrice($order['total']) ?></span>
            </div>
        </div>
    </div>

    <!-- Right Column: Status & Tracking Configuration -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card" style="border-color: var(--border-gold);">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1.25rem;">⚙️ Shipping Information</h3>
            
            <div style="margin-bottom: 1.5rem;">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Current Status</span><br>
                <span class="badge-status badge-<?= $order['status'] ?>" style="margin-top: 0.25rem; font-size: 0.85rem; padding: 6px 16px;">
                    <?= ucfirst($order['status']) ?>
                </span>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Tracking Code</span><br>
                <strong class="text-gold" style="font-family: monospace; font-size: 1rem;"><?= e($order['tracking_code']) ?></strong>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Payment Method</span><br>
                <strong style="color: white; text-transform: uppercase; font-size: 0.85rem;"><?= e($order['payment_method']) ?></strong>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Shipping Destination</span><br>
                <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.25rem; line-height: 1.5;"><?= e($order['shipping_address']) ?></p>
            </div>

            <div style="padding-top: 1rem; border-top: 1px solid var(--border-dark); text-align: center;">
                <a href="<?= url('/admin/tracking') ?>" class="btn-gold" style="display: flex; justify-content: center; font-size: 0.85rem; padding: 0.65rem; text-decoration: none; border-radius: 8px;">
                    📍 Manage Track Status
                </a>
            </div>
        </div>
    </div>
</div>

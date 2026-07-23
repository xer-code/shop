<div style="margin-bottom: 2rem;">
    <h2 style="font-size: 1.25rem; font-weight: 700;">📦 Order Management Console (<?= count($orders) ?> orders total)</h2>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Tracking Code</th>
                    <th>Payment Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="text-gold" style="font-weight: 700;">
                                #<?= $order['id'] ?>
                            </a>
                        </td>
                        <td style="font-weight: 600; color: white;"><?= e($order['customer_name']) ?></td>
                        <td><?= date('M j, Y g:i A', strtotime($order['created_at'])) ?></td>
                        <td style="font-weight: 700; color: white;"><?= formatPrice($order['total']) ?></td>
                        <td>
                            <span class="text-gold" style="font-family: monospace; font-size: 0.85rem;"><?= e($order['tracking_code']) ?></span>
                        </td>
                        <td style="text-transform: capitalize; font-size: 0.85rem;"><?= str_replace('_', ' ', e($order['payment_method'])) ?></td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; align-items: center;">
                                <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="btn-outline btn-sm" style="padding: 0.4rem 0.8rem; text-decoration: none; display: inline-flex; align-items: center; border-radius: 4px;">
                                    👁️ View
                                </a>
                                <form method="POST" action="<?= url('/admin/orders/delete/' . $order['id']) ?>" onsubmit="return confirm('Are you sure you want to delete this order?');" style="margin: 0;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-danger btn-sm" style="padding: 0.4rem 0.8rem; font-weight: 700; border: none; border-radius: 4px; cursor: pointer;">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

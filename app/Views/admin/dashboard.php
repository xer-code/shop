<!-- Admin Dashboard stats -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Revenue -->
    <div class="admin-stat-card">
        <div class="stat-label">💰 Total Revenue</div>
        <div class="stat-number"><?= formatPrice($stats['total_revenue']) ?></div>
        <div style="font-size: 0.75rem; color: var(--green-save); margin-top: 0.5rem;">★ All-time sales earnings</div>
    </div>
    
    <!-- Orders -->
    <div class="admin-stat-card">
        <div class="stat-label">📦 Total Orders</div>
        <div class="stat-number"><?= $stats['total_orders'] ?></div>
        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">
            <?= $stats['pending'] ?> pending • <?= $stats['processing'] ?> processing
        </div>
    </div>
    
    <!-- Products -->
    <div class="admin-stat-card">
        <div class="stat-label">🛍️ Total Products</div>
        <div class="stat-number"><?= $totalProducts ?></div>
        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Active in catalog</div>
    </div>
    
    <!-- Users -->
    <div class="admin-stat-card">
        <div class="stat-label">👥 Total Customers</div>
        <div class="stat-number"><?= $totalUsers ?></div>
        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Registered shoppers</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Recent Orders -->
    <div class="card">
        <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1.25rem;">📦 Recent Orders</h2>
        
        <?php if (empty($recentOrders)): ?>
            <p style="color: var(--text-muted); text-align: center; padding: 2rem 0;">No orders placed yet.</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>
                                    <a href="<?= url('/admin/orders/' . $order['id']) ?>" class="text-gold" style="font-weight: 600;">
                                        #<?= $order['id'] ?>
                                    </a>
                                </td>
                                <td><?= e($order['customer_name']) ?></td>
                                <td><?= formatPrice($order['total']) ?></td>
                                <td>
                                    <span class="badge-status badge-<?= $order['status'] ?>"><?= $order['status'] ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align: right; margin-top: 1rem;">
                <a href="<?= url('/admin/orders') ?>" class="text-gold" style="font-size: 0.85rem; font-weight: 600;">
                    Manage All Orders →
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Low Stock Alert -->
    <div class="card">
        <h2 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1.25rem;">⚠️ Low Stock Products</h2>
        
        <?php if (empty($lowStockProducts)): ?>
            <p style="color: var(--text-muted); text-align: center; padding: 2rem 0;">All items fully stocked.</p>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Stock</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lowStockProducts as $prod): ?>
                            <tr>
                                <td>
                                    <a href="<?= url('/admin/products/edit/' . $prod['id']) ?>" class="text-gold" style="font-weight: 600;">
                                        <?= e(truncate($prod['title'], 35)) ?>
                                    </a>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: <?= $prod['stock'] <= 5 ? 'var(--red-badge)' : 'var(--orange-badge)' ?>;">
                                        <?= $prod['stock'] ?> left
                                    </span>
                                </td>
                                <td><?= formatPrice($prod['price']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="text-align: right; margin-top: 1rem;">
                <a href="<?= url('/admin/products') ?>" class="text-gold" style="font-size: 0.85rem; font-weight: 600;">
                    Manage Inventory →
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

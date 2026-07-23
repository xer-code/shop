<div class="fade-in space-y-6">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">📦 Order History</h2>
        <span style="font-size: 0.75rem; color: #666; font-weight: 600;"><?= count($orders) ?> total orders</span>
    </div>

    <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 3rem; background: #151515; border: 1px solid #1a1a1a; border-radius: 12px;">
            <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">📭</p>
            <p style="color: #555; font-size: 0.85rem;">You haven't placed any orders yet.</p>
            <a href="<?= url('/shop') ?>" class="btn-gold" style="margin-top: 1rem; display: inline-flex; font-size: 0.8rem; padding: 0.5rem 1.5rem; text-decoration: none; border-radius: 8px;">Start Shopping →</a>
        </div>
    <?php else: ?>
        <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #1a1a1a;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Order ID</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Items</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Total</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Status</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Date</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <?php
                            $statusColors = ['pending'=>'#eab308','processing'=>'#3b82f6','shipped'=>'#8b5cf6','delivered'=>'#10b981','cancelled'=>'#ef4444'];
                            $sColor = $statusColors[$o['status']] ?? '#888';
                        ?>
                        <tr style="border-bottom: 1px solid #111;">
                            <td style="padding: 0.75rem 1rem; font-size: 0.8rem; font-weight: 700; color: #fff; font-family: monospace;">#<?= $o['tracking_code'] ?? $o['id'] ?></td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.8rem; color: #999;"><?= $o['item_count'] ?? '-' ?> items</td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.8rem; font-weight: 700; color: #D4A017; font-family: monospace;"><?= formatPrice($o['total']) ?></td>
                            <td style="padding: 0.75rem 1rem;">
                                <span style="display: inline-block; padding: 2px 8px; font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: <?= $sColor ?>; background: <?= $sColor ?>15; border: 1px solid <?= $sColor ?>30; border-radius: 4px;"><?= $o['status'] ?></span>
                            </td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.75rem; color: #555;"><?= date('M j, Y', strtotime($o['created_at'])) ?></td>
                            <td style="padding: 0.75rem 1rem;">
                                <a href="<?= url('/orders/' . $o['id']) ?>" style="font-size: 0.75rem; color: #D4A017; text-decoration: none; font-weight: 600;">View →</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

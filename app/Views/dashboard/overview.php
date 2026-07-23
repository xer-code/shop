<div class="fade-in space-y-6">
    <!-- Stats Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
        <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.65rem; color: #666; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Total Orders</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #fff; margin-top: 0.25rem;"><?= $totalOrders ?></div>
            <div style="font-size: 0.7rem; color: #555; margin-top: 0.25rem;">📦 Lifetime purchases</div>
        </div>
        <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.65rem; color: #666; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">In Transit</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #3b82f6; margin-top: 0.25rem;"><?= $shippedOrders ?></div>
            <div style="font-size: 0.7rem; color: #555; margin-top: 0.25rem;">🚚 Processing & shipped</div>
        </div>
        <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.65rem; color: #666; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Wallet Balance</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #D4A017; margin-top: 0.25rem; font-family: monospace;"><?= formatPrice($balance) ?></div>
            <div style="font-size: 0.7rem; color: #555; margin-top: 0.25rem;">💰 Available funds</div>
        </div>
        <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.65rem; color: #666; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Total Spent</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #10b981; margin-top: 0.25rem; font-family: monospace;"><?= formatPrice($totalSpent) ?></div>
            <div style="font-size: 0.7rem; color: #555; margin-top: 0.25rem;">💸 All-time spending</div>
        </div>
        <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
            <div style="font-size: 0.65rem; color: #666; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;">Saved Products</div>
            <div style="font-size: 1.8rem; font-weight: 800; color: #f43f5e; margin-top: 0.25rem;"><?= $savedCount ?></div>
            <div style="font-size: 0.7rem; color: #555; margin-top: 0.25rem;">❤️ Wishlisted items</div>
        </div>
    </div>

    <!-- Two-column grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Recent Orders -->
        <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 0.85rem; font-weight: 700; color: #fff;">Recent Orders</h3>
                <a href="<?= url('/dashboard?section=orders') ?>" style="font-size: 0.7rem; color: #D4A017; text-decoration: none; font-weight: 600;">View All →</a>
            </div>
            <?php if (empty($recentOrders)): ?>
                <p style="font-size: 0.8rem; color: #555; text-align: center; padding: 1.5rem 0;">No orders yet. <a href="<?= url('/shop') ?>" style="color: #D4A017;">Start shopping →</a></p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <?php foreach ($recentOrders as $o): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; background: #111; border-radius: 8px; border: 1px solid #1a1a1a;">
                            <div>
                                <span style="font-size: 0.75rem; font-weight: 700; color: #fff;">#<?= $o['tracking_code'] ?? $o['id'] ?></span>
                                <span style="font-size: 0.65rem; color: #555; margin-left: 0.5rem;"><?= timeAgo($o['created_at']) ?></span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 0.75rem; font-weight: 700; color: #D4A017; font-family: monospace;"><?= formatPrice($o['total']) ?></span>
                                <?php
                                    $sc = ['pending'=>'#eab308','processing'=>'#3b82f6','shipped'=>'#8b5cf6','delivered'=>'#10b981','cancelled'=>'#ef4444'];
                                    $color = $sc[$o['status']] ?? '#888';
                                ?>
                                <span style="font-size: 0.6rem; font-weight: 700; color: <?= $color ?>; text-transform: uppercase; letter-spacing: 0.05em;"><?= $o['status'] ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Transactions -->
        <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h3 style="font-size: 0.85rem; font-weight: 700; color: #fff;">Recent Transactions</h3>
                <a href="<?= url('/dashboard?section=payments') ?>" style="font-size: 0.7rem; color: #D4A017; text-decoration: none; font-weight: 600;">View All →</a>
            </div>
            <?php if (empty($recentTransactions)): ?>
                <p style="font-size: 0.8rem; color: #555; text-align: center; padding: 1.5rem 0;">No transaction history yet.</p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <?php foreach ($recentTransactions as $t): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; background: #111; border-radius: 8px; border: 1px solid #1a1a1a;">
                            <div style="overflow: hidden;">
                                <div style="font-size: 0.75rem; font-weight: 600; color: #ccc; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;"><?= e($t['description']) ?></div>
                                <div style="font-size: 0.6rem; color: #555;"><?= timeAgo($t['created_at']) ?></div>
                            </div>
                            <span style="font-size: 0.8rem; font-weight: 700; font-family: monospace; color: <?= $t['amount'] >= 0 ? '#10b981' : '#ef4444' ?>;">
                                <?= $t['amount'] >= 0 ? '+' : '' ?><?= formatPrice($t['amount']) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">⚡ Quick Actions</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <a href="<?= url('/shop') ?>" class="btn-gold" style="font-size: 0.8rem; padding: 0.5rem 1.25rem; border-radius: 8px; text-decoration: none;">🛍️ Browse Shop</a>
            <a href="<?= url('/wallet') ?>" style="font-size: 0.8rem; padding: 0.5rem 1.25rem; border-radius: 8px; background: #1a1a1a; border: 1px solid #2a2a2a; color: #ccc; font-weight: 600; text-decoration: none; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#D4A017'" onmouseout="this.style.borderColor='#2a2a2a'">💰 Add Funds</a>
            <a href="<?= url('/dashboard?section=support') ?>" style="font-size: 0.8rem; padding: 0.5rem 1.25rem; border-radius: 8px; background: #1a1a1a; border: 1px solid #2a2a2a; color: #ccc; font-weight: 600; text-decoration: none; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#D4A017'" onmouseout="this.style.borderColor='#2a2a2a'">🎫 Open Ticket</a>
            <a href="<?= url('/gift-cards') ?>" style="font-size: 0.8rem; padding: 0.5rem 1.25rem; border-radius: 8px; background: #1a1a1a; border: 1px solid #2a2a2a; color: #ccc; font-weight: 600; text-decoration: none; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#D4A017'" onmouseout="this.style.borderColor='#2a2a2a'">🎁 Gift Cards</a>
            <a href="<?= url('/track-order') ?>" style="font-size: 0.8rem; padding: 0.5rem 1.25rem; border-radius: 8px; background: #1a1a1a; border: 1px solid #2a2a2a; color: #ccc; font-weight: 600; text-decoration: none; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#D4A017'" onmouseout="this.style.borderColor='#2a2a2a'">🚚 Track Order</a>
        </div>
    </div>
</div>

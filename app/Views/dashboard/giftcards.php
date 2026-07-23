<div class="fade-in space-y-6">
    <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">🎁 Gift Cards</h2>

    <!-- Redeem Gift Card -->
    <div style="background: linear-gradient(135deg, #1a1500 0%, #151515 100%); border: 1px solid rgba(212,160,23,0.2); border-radius: 16px; padding: 1.5rem;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #fff; margin-bottom: 1rem;">Redeem a Gift Card</h3>
        <form method="POST" action="<?= url('/dashboard/gift-cards/redeem') ?>" style="display: flex; gap: 0.75rem; align-items: flex-end;">
            <?= csrf_field() ?>
            <div style="flex: 1;">
                <label style="display: block; font-size: 0.65rem; font-weight: 700; color: #666; text-transform: uppercase; margin-bottom: 0.4rem;">Gift Card Code</label>
                <input type="text" name="code" required placeholder="SHOPX-XXXX-XXXX" class="input-dark" style="width: 100%; font-family: monospace; text-transform: uppercase;">
            </div>
            <button type="submit" class="btn-gold" style="padding: 0.55rem 1.25rem; font-size: 0.8rem; border-radius: 8px; white-space: nowrap;">Redeem →</button>
        </form>
    </div>

    <!-- Purchase Gift Card -->
    <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="font-size: 0.85rem; font-weight: 700; color: #fff;">Buy a Gift Card</h3>
            <a href="<?= url('/gift-cards') ?>" style="font-size: 0.7rem; color: #D4A017; text-decoration: none; font-weight: 600;">Go to Gift Cards →</a>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            <?php foreach ([25, 50, 100, 250, 500] as $amount): ?>
                <a href="<?= url('/gift-cards') ?>" style="display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.5rem 1rem; background: #111; border: 1px solid #1a1a1a; border-radius: 8px; color: #D4A017; font-family: monospace; font-weight: 700; font-size: 0.8rem; text-decoration: none; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#D4A017'" onmouseout="this.style.borderColor='#1a1a1a'">$<?= $amount ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Purchased Gift Cards -->
    <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #fff; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #1a1a1a;">Purchased Cards</h3>
        <?php if (empty($purchased)): ?>
            <p style="font-size: 0.8rem; color: #555; text-align: center; padding: 1rem 0;">You haven't purchased any gift cards yet.</p>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <?php foreach ($purchased as $gc): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; background: #111; border-radius: 8px; border: 1px solid #1a1a1a;">
                        <div>
                            <span style="font-size: 0.8rem; font-weight: 700; color: #fff; font-family: monospace;"><?= e($gc['code']) ?></span>
                            <span style="font-size: 0.65rem; color: #555; margin-left: 0.5rem;"><?= date('M j, Y', strtotime($gc['created_at'])) ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 0.8rem; font-weight: 700; color: #D4A017; font-family: monospace;"><?= formatPrice($gc['initial_value']) ?></span>
                            <?php $gcColor = $gc['status'] === 'active' ? '#10b981' : '#ef4444'; ?>
                            <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; color: <?= $gcColor ?>;"><?= $gc['status'] ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Redeemed Gift Cards -->
    <?php if (!empty($redeemed)): ?>
    <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #fff; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #1a1a1a;">Redeemed Cards</h3>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <?php foreach ($redeemed as $gc): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; background: #111; border-radius: 8px; border: 1px solid #1a1a1a;">
                    <span style="font-size: 0.8rem; font-weight: 700; color: #888; font-family: monospace;"><?= e($gc['code']) ?></span>
                    <span style="font-size: 0.8rem; font-weight: 700; color: #10b981; font-family: monospace;">+<?= formatPrice($gc['initial_value']) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

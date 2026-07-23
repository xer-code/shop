<div class="fade-in space-y-6">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">💰 My Wallet</h2>
        <a href="<?= url('/wallet') ?>" class="btn-gold" style="font-size: 0.75rem; padding: 0.4rem 1rem; border-radius: 8px; text-decoration: none;">+ Add Funds</a>
    </div>

    <!-- Balance Card -->
    <div style="background: linear-gradient(135deg, #1a1500 0%, #151515 100%); border: 1px solid rgba(212,160,23,0.2); border-radius: 16px; padding: 2rem; text-align: center;">
        <div style="font-size: 0.7rem; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">Available Balance</div>
        <div style="font-size: 2.5rem; font-weight: 900; color: #D4A017; font-family: monospace; margin: 0.5rem 0;"><?= formatPrice($balance) ?></div>
        <?php if (!empty($pendingDeposits)): ?>
            <div style="font-size: 0.7rem; color: #eab308; margin-top: 0.5rem;">⏳ <?= count($pendingDeposits) ?> pending deposit(s) awaiting approval</div>
        <?php endif; ?>
    </div>

    <!-- Transaction History -->
    <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
        <h3 style="font-size: 0.85rem; font-weight: 700; color: #fff; margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid #1a1a1a;">Transaction History</h3>
        <?php if (empty($transactions)): ?>
            <p style="font-size: 0.8rem; color: #555; text-align: center; padding: 1.5rem 0;">No transactions yet.</p>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <?php foreach ($transactions as $t): ?>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; background: #111; border-radius: 8px; border: 1px solid #1a1a1a;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; background: <?= $t['amount'] >= 0 ? '#10b98115' : '#ef444415' ?>;">
                                <?= $t['amount'] >= 0 ? '↓' : '↑' ?>
                            </div>
                            <div>
                                <div style="font-size: 0.8rem; font-weight: 600; color: #ccc;"><?= e($t['description']) ?></div>
                                <div style="font-size: 0.65rem; color: #555;"><?= timeAgo($t['created_at']) ?></div>
                            </div>
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 700; font-family: monospace; color: <?= $t['amount'] >= 0 ? '#10b981' : '#ef4444' ?>;">
                            <?= $t['amount'] >= 0 ? '+' : '' ?><?= formatPrice($t['amount']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

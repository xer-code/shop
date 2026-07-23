<div class="fade-in space-y-6">
    <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">💳 Payment History</h2>

    <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <?php if (empty($transactions)): ?>
            <div style="text-align: center; padding: 3rem;">
                <p style="color: #555; font-size: 0.85rem;">No payment records found.</p>
            </div>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #1a1a1a;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">ID</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Type</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Description</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Amount</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 700; color: #555; text-transform: uppercase;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                        <tr style="border-bottom: 1px solid #111;">
                            <td style="padding: 0.75rem 1rem; font-size: 0.75rem; color: #555; font-family: monospace;">#<?= $t['id'] ?></td>
                            <td style="padding: 0.75rem 1rem;">
                                <?php
                                    $typeColors = ['deposit'=>'#10b981','purchase'=>'#ef4444','gift_card'=>'#8b5cf6','refund'=>'#3b82f6'];
                                    $tc = $typeColors[$t['type']] ?? '#888';
                                ?>
                                <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; color: <?= $tc ?>; background: <?= $tc ?>12; border: 1px solid <?= $tc ?>25; padding: 2px 6px; border-radius: 4px;"><?= $t['type'] ?></span>
                            </td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.8rem; color: #ccc;"><?= e($t['description']) ?></td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.8rem; font-weight: 700; font-family: monospace; color: <?= $t['amount'] >= 0 ? '#10b981' : '#ef4444' ?>;">
                                <?= $t['amount'] >= 0 ? '+' : '' ?><?= formatPrice($t['amount']) ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; font-size: 0.75rem; color: #555;"><?= date('M j, Y g:ia', strtotime($t['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

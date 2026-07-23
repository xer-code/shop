<style>
    .giftcard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: start;
    }
    @media (max-width: 991.98px) {
        .giftcard-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }
    @media (max-width: 767.98px) {
        .responsive-table thead {
            display: none;
        }
        .responsive-table, .responsive-table tbody, .responsive-table tr {
            display: block;
            width: 100%;
        }
        .responsive-table tr {
            border: 1px solid var(--border-dark);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            background: rgba(255, 255, 255, 0.01);
        }
        .responsive-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.85rem;
            text-align: right;
        }
        .responsive-table td:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .responsive-table td::before {
            content: attr(data-label);
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            font-size: 0.75rem;
            text-align: left;
            margin-right: 1rem;
            flex-shrink: 0;
        }
    }
</style>

<div class="giftcard-grid">
    <!-- Left Column: Issued Gift Cards Logs -->
    <div>
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700;">🎁 Issued Gift Cards (<?= count($giftCards) ?> total)</h2>
        </div>

        <div class="card">
            <div style="overflow-x: auto;">
                <table class="data-table responsive-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Card Name</th>
                            <th>Initial</th>
                            <th>Remaining</th>
                            <th>Logs</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($giftCards as $gc): ?>
                            <tr>
                                <td data-label="Code" style="font-weight: 700; color: white; font-family: monospace; font-size: 0.95rem;">
                                    <?= e($gc['code']) ?>
                                </td>
                                <td data-label="Card Name" style="font-weight: 600; color: #eee;">
                                    <?= e($gc['name'] ?? 'N/A') ?>
                                </td>
                                <td data-label="Initial"><?= formatPrice($gc['initial_value']) ?></td>
                                <td data-label="Remaining" style="font-weight: 700; color: var(--gold-primary);"><?= formatPrice($gc['remaining_value']) ?></td>
                                <td data-label="Logs" style="font-size: 0.8rem; line-height: 1.4;">
                                    <?php if ($gc['buyer_name']): ?>
                                        <span style="color: var(--text-muted);">Bought by:</span> <strong style="color: white;"><?= e($gc['buyer_name']) ?></strong><br>
                                    <?php endif; ?>
                                    <?php if ($gc['redeemer_name']): ?>
                                        <span style="color: var(--text-muted);">Redeemed by:</span> <strong style="color: white;"><?= e($gc['redeemer_name']) ?></strong>
                                    <?php endif; ?>
                                    <?php if (!$gc['buyer_name'] && !$gc['redeemer_name']): ?>
                                        <span style="color: var(--text-muted); font-style: italic;">Direct System Issue</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Status">
                                    <span class="badge-status <?= $gc['status'] === 'active' ? 'badge-delivered' : ($gc['status'] === 'used' ? 'badge-pending' : 'badge-cancelled') ?>">
                                        <?= $gc['status'] ?>
                                    </span>
                                </td>
                                <td data-label="Actions">
                                    <div style="display: flex; gap: 0.35rem; align-items: center; justify-content: flex-end;">
                                        <?php if ($gc['status'] === 'active'): ?>
                                            <form method="POST" action="<?= url('/admin/gift-cards/void/' . $gc['id']) ?>" onsubmit="return confirm('Are you sure you want to void this gift card?');" style="margin: 0;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn-warning btn-sm" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 700;">
                                                    Void
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="POST" action="<?= url('/admin/gift-cards/delete/' . $gc['id']) ?>" onsubmit="return confirm('Are you sure you want to delete this gift card?');" style="margin: 0;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn-danger btn-sm" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 700;">
                                                Delete
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
    </div>

    <!-- Right Column: Issue Gift Card Form -->
    <div class="card" style="border-color: var(--border-gold);">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1.25rem;">💰 Issue New Gift Card</h3>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.5rem; line-height: 1.5;">
            Create custom gift cards with specific codes, names, and flexible values.
        </p>

        <form method="POST" action="<?= url('/admin/gift-cards/issue') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Card Name (Optional)</label>
                <input type="text" name="name" class="input-dark" placeholder="e.g. Summer Promo Card">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Custom Code (Optional)</label>
                <input type="text" name="code" class="input-dark" placeholder="e.g. SUMMER-100 (blank for auto-generated)">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Gift Card Value ($)</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-weight: 600;">$</span>
                    <input type="number" name="amount" class="input-dark" step="any" min="0.01" placeholder="e.g. 50.00" style="padding-left: 2rem;" required>
                </div>
            </div>
            
            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; margin-top: 1rem; padding: 0.85rem; font-size: 0.9rem; border-radius: 8px;">
                🚀 Issue Gift Card
            </button>
        </form>
    </div>
</div>

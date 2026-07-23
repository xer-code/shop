<div class="fade-in space-y-6">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">📝 My Quotes</h2>
        <button onclick="document.getElementById('quoteModal').classList.add('active')" class="btn-gold" style="font-size: 0.75rem; padding: 0.4rem 1rem; border-radius: 8px;">+ Request Quote</button>
    </div>

    <?php if (empty($quotes)): ?>
        <div style="text-align: center; padding: 3rem; background: #151515; border: 1px solid #1a1a1a; border-radius: 12px;">
            <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">📋</p>
            <p style="color: #555; font-size: 0.85rem;">No quote requests yet. Request a bulk or custom order quote.</p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <?php foreach (array_reverse($quotes) as $q): ?>
                <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.25rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <div style="font-size: 0.85rem; font-weight: 700; color: #fff;"><?= e($q['product']) ?></div>
                            <div style="font-size: 0.7rem; color: #555; margin-top: 0.25rem;">Qty: <?= $q['quantity'] ?> &middot; <?= timeAgo($q['created_at']) ?></div>
                            <?php if (!empty($q['notes'])): ?>
                                <div style="font-size: 0.75rem; color: #888; margin-top: 0.5rem; padding: 0.5rem 0.75rem; background: #111; border-radius: 6px; border-left: 2px solid #D4A017;"><?= e($q['notes']) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php
                            $qColors = ['Pending'=>'#eab308','Approved'=>'#10b981','Rejected'=>'#ef4444'];
                            $qc = $qColors[$q['status']] ?? '#888';
                        ?>
                        <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: <?= $qc ?>; background: <?= $qc ?>12; border: 1px solid <?= $qc ?>25; padding: 2px 8px; border-radius: 4px;"><?= $q['status'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Quote Request Modal -->
<div class="modal-overlay" id="quoteModal">
    <div class="modal-content" style="max-width: 460px; padding: 2rem; background: #151515; border: 1px solid #2a2a2a; border-radius: 16px;">
        <button class="modal-close" onclick="document.getElementById('quoteModal').classList.remove('active')" style="color: #888; font-size: 1.5rem;">&times;</button>
        <h2 style="font-weight: 800; font-size: 1.1rem; color: #fff; margin-bottom: 1.25rem;">📝 Request a Quote</h2>
        <form method="POST" action="<?= url('/dashboard/quote/request') ?>" style="display: flex; flex-direction: column; gap: 1rem;">
            <?= csrf_field() ?>
            <div>
                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #666; text-transform: uppercase; margin-bottom: 0.4rem;">Product / Service Name</label>
                <input type="text" name="product" required placeholder="e.g. Custom Branded T-Shirts" class="input-dark" style="width: 100%;">
            </div>
            <div>
                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #666; text-transform: uppercase; margin-bottom: 0.4rem;">Quantity Needed</label>
                <input type="number" name="quantity" min="1" value="1" required class="input-dark" style="width: 100%;">
            </div>
            <div>
                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #666; text-transform: uppercase; margin-bottom: 0.4rem;">Additional Notes (optional)</label>
                <textarea name="notes" rows="3" placeholder="Describe specifications, colours, sizes..." class="input-dark" style="width: 100%; resize: vertical;"></textarea>
            </div>
            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.6rem; border-radius: 8px; font-size: 0.85rem;">Submit Quote Request</button>
        </form>
    </div>
</div>

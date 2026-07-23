<?php $pageTitle = 'Gift Cards — ShopX Global'; ?>
<section class="container fade-in" style="padding: 2rem 0; max-width: 900px;">
    <a href="<?= url('/') ?>" style="color: var(--text-muted); font-size: 0.9rem;">← Back to Home</a>
    
    <div style="text-align: center; margin: 2rem 0;">
        <span class="badge-category" style="background: var(--gold-primary); color: #000;">🎁 GIFT CARDS</span>
        <h1 style="font-size: clamp(1.8rem, 4vw, 2.5rem); font-weight: 900; margin-top: 1rem;">
            Give the Gift of <span class="text-gold">Choice</span>
        </h1>
        <p style="color: var(--text-muted); margin-top: 0.5rem;">Purchase or redeem gift cards instantly. Perfect for any occasion.</p>
        
        <?php if (\App\Core\Auth::check()): ?>
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1.5rem; border: 1px solid var(--border-dark); border-radius: 25px; margin-top: 1rem;">
            Wallet Balance: <strong class="text-gold"><?= formatPrice($wallet) ?></strong>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Tabs -->
    <div style="display: flex; border: 1px solid var(--border-dark); border-radius: 12px; overflow: hidden; margin-bottom: 2rem;">
        <button class="gc-tab active" onclick="switchGCTab('buy')" id="tabBuy" 
                style="flex: 1; padding: 0.9rem; background: var(--gold-primary); color: #000; font-weight: 700; border: none; font-size: 0.95rem; cursor: pointer; transition: all 0.3s;">
            🎁 Buy Gift Card
        </button>
        <button class="gc-tab" onclick="switchGCTab('redeem')" id="tabRedeem"
                style="flex: 1; padding: 0.9rem; background: transparent; color: var(--text-secondary); font-weight: 700; border: none; font-size: 0.95rem; cursor: pointer; transition: all 0.3s;">
            💳 Redeem Code
        </button>
    </div>
    
    <!-- Buy Tab -->
    <div id="buyPanel">
        <?php if (empty($publicCards)): ?>
            <div class="card" style="padding: 3rem; text-align: center; border-color: var(--border-dark);">
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 0;">🎁 No gift cards are currently available for purchase. Please check back later!</p>
            </div>
        <?php else: ?>
            <form method="POST" action="<?= url('/gift-cards/purchase') ?>" id="gcForm">
                <?= csrf_field() ?>
                <input type="hidden" name="gift_card_id" id="gcId" value="">
                
                <div class="gift-card-grid">
                    <?php 
                    $gradients = [
                        'linear-gradient(135deg, #059669, #10b981)',
                        'linear-gradient(135deg, #2563eb, #3b82f6)',
                        'linear-gradient(135deg, #7c3aed, #8b5cf6)',
                        'linear-gradient(135deg, #db2777, #ec4899)',
                        'linear-gradient(135deg, #ea580c, #f97316)',
                        'linear-gradient(135deg, #475569, #64748b)',
                    ];
                    $index = 0;
                    foreach ($publicCards as $pc): 
                        $gradient = $gradients[$index % count($gradients)];
                        $index++;
                    ?>
                        <div class="gift-card-item" style="background: <?= $gradient ?>; border-radius: 16px; padding: 1.5rem; position: relative; display: flex; flex-direction: column; justify-content: space-between; min-height: 140px; color: white;" 
                             onclick="selectGiftCard(<?= $pc['id'] ?>, <?= $pc['initial_value'] ?>, this)">
                            <div>
                                <div class="gc-icon" style="font-size: 1.5rem; margin-bottom: 0.25rem;">🎁</div>
                                <div class="gc-label" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.8; font-weight: 700;"><?= e($pc['name'] ?? 'Gift Card') ?></div>
                            </div>
                            <div class="gc-value" style="font-size: 1.8rem; font-weight: 900; line-height: 1;"><?= formatPrice($pc['initial_value']) ?></div>
                            <div style="position: absolute; top: 10px; right: 10px; opacity: 0.5;">✨</div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <button type="submit" class="btn-gold" id="purchaseBtn" style="width: 100%; justify-content: center; margin-top: 2rem; padding: 1rem; font-size: 1rem;" disabled>
                    Select a card to purchase
                </button>
            </form>
        <?php endif; ?>
    </div>
    
    <!-- Redeem Tab -->
    <div id="redeemPanel" style="display: none;">
        <form method="POST" action="<?= url('/gift-cards/redeem') ?>">
            <?= csrf_field() ?>
            <div class="card" style="padding: 2rem; text-align: center;">
                <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Enter your gift card code below</p>
                <input type="text" name="code" class="input-dark" placeholder="SHOPX-XXXX-XXXX" 
                       style="text-align: center; font-size: 1.1rem; letter-spacing: 0.1em; max-width: 400px; margin: 0 auto;" required>
                <br><br>
                <button type="submit" class="btn-gold" style="padding: 0.9rem 2.5rem;">
                    Redeem Gift Card
                </button>
            </div>
        </form>
    </div>

    <!-- User's Purchased Cards Section -->
    <?php if (\App\Core\Auth::check() && !empty($myPurchased)): ?>
        <div style="margin-top: 3rem;">
            <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.25rem; color: white;">
                My Purchased Gift Cards
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
                <?php foreach ($myPurchased as $mp): ?>
                    <div class="card" style="border: 1px solid #222; background: #151515; display: flex; flex-direction: column; justify-content: space-between; gap: 1rem; padding: 1.25rem; border-radius: 12px;">
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div>
                                    <h4 style="font-size: 0.95rem; font-weight: 700; color: white; margin: 0; font-family: monospace;"><?= e($mp['code']) ?></h4>
                                    <?php if (!empty($mp['name'])): ?>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.15rem;"><?= e($mp['name']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <span style="font-weight: 700; color: var(--gold-primary);"><?= formatPrice($mp['initial_value']) ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                <span style="font-size: 0.7rem; color: var(--text-muted);"><?= date('M j, Y', strtotime($mp['created_at'])) ?></span>
                                <span class="badge-status <?= $mp['status'] === 'active' ? 'badge-delivered' : ($mp['status'] === 'used' ? 'badge-pending' : 'badge-cancelled') ?>" style="font-size: 0.65rem; padding: 0.2rem 0.6rem;">
                                    <?= e($mp['status']) ?>
                                </span>
                            </div>
                        </div>
                        
                        <?php if ($mp['status'] === 'active'): ?>
                            <form method="POST" action="<?= url('/gift-cards/redeem') ?>" style="margin: 0;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="code" value="<?= e($mp['code']) ?>">
                                <button type="submit" class="btn-outline btn-sm" style="width: 100%; justify-content: center; font-size: 0.8rem; border-radius: 6px; padding: 0.5rem; cursor: pointer; border: 1.5px solid var(--border-dark);">
                                    Redeem to Wallet
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<script>
function selectGiftCard(id, value, el) {
    document.getElementById('gcId').value = id;
    const btn = document.getElementById('purchaseBtn');
    btn.disabled = false;
    btn.innerHTML = `Purchase $${value} Gift Card <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-left: 0.5rem; display: inline-block; vertical-align: middle;"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>`;
    document.querySelectorAll('.gift-card-item').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
}
function switchGCTab(tab) {
    document.getElementById('buyPanel').style.display = tab === 'buy' ? 'block' : 'none';
    document.getElementById('redeemPanel').style.display = tab === 'redeem' ? 'block' : 'none';
    document.getElementById('tabBuy').style.background = tab === 'buy' ? 'var(--gold-primary)' : 'transparent';
    document.getElementById('tabBuy').style.color = tab === 'buy' ? '#000' : 'var(--text-secondary)';
    document.getElementById('tabRedeem').style.background = tab === 'redeem' ? 'var(--gold-primary)' : 'transparent';
    document.getElementById('tabRedeem').style.color = tab === 'redeem' ? '#000' : 'var(--text-secondary)';
}
</script>

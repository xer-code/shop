<?php $pageTitle = 'Checkout — ShopX Global'; ?>
<section class="container fade-in" style="padding: 2rem 0;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem;">Checkout</h1>
    
    <form method="POST" action="<?= url('/checkout') ?>">
        <?= csrf_field() ?>
        
        <div class="checkout-grid">
            <!-- Left: Address & Payment -->
            <div>
                <div class="card" style="margin-bottom: 1.5rem;">
                    <h2 style="font-weight: 700; margin-bottom: 1.25rem;">📍 Shipping Address</h2>
                    <div class="form-group">
                        <label>Street Address</label>
                        <input type="text" name="address" class="input-dark" placeholder="123 Main St, Apt 4B" required>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="input-dark" placeholder="New York" required>
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <select name="country" class="select-dark" required>
                                <option value="">Select</option>
                                <option value="US">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="CA">Canada</option>
                                <option value="AU">Australia</option>
                                <option value="DE">Germany</option>
                                <option value="FR">France</option>
                                <option value="JP">Japan</option>
                                <option value="NG">Nigeria</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card">
                    <h2 style="font-weight: 700; margin-bottom: 1.25rem;">💳 Payment Method</h2>
                    
                    <label class="payment-method-card" style="display: flex;">
                        <input type="radio" name="payment_method" value="wallet" checked style="display:none;">
                        <div class="pm-icon" style="background: rgba(212,160,23,0.15); color: var(--gold-primary);">💰</div>
                        <div class="pm-info">
                            <div class="pm-name">Wallet Balance</div>
                            <div class="pm-min">Available: <?= formatPrice($wallet) ?></div>
                        </div>
                        <span class="pm-arrow">›</span>
                    </label>
                    
                    <label class="payment-method-card" style="display: flex;">
                        <input type="radio" name="payment_method" value="gift_card" style="display:none;">
                        <div class="pm-icon" style="background: rgba(124,58,237,0.15); color: #8b5cf6;">🎁</div>
                        <div class="pm-info">
                            <div class="pm-name">Gift Card</div>
                            <div class="pm-min">Enter code below</div>
                        </div>
                        <span class="pm-arrow">›</span>
                    </label>
                    
                    <div class="form-group" style="margin-top: 1rem;">
                        <label>Gift Card Code (optional)</label>
                        <input type="text" name="gift_card_code" class="input-dark" placeholder="SHOPX-XXXX-XXXX">
                    </div>
                </div>
            </div>
            
            <!-- Right: Order Summary -->
            <div class="order-summary-card">
                <h2 style="font-weight: 700; margin-bottom: 1.25rem;">Order Summary</h2>
                
                <?php foreach ($items as $item): ?>
                <div style="display: flex; gap: 0.75rem; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-dark);">
                    <img src="<?= e($item['image_url'] ?: '') ?>" alt="" style="width: 60px; height: 50px; object-fit: cover; border-radius: 6px; background: var(--bg-secondary);"
                         onerror="this.src='https://placehold.co/60x50/1a1a1a/666'">
                    <div style="flex: 1;">
                        <p style="font-size: 0.85rem; font-weight: 500;"><?= e(truncate($item['title'], 30)) ?></p>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Qty: <?= $item['qty'] ?></p>
                    </div>
                    <span style="font-weight: 600; color: var(--gold-primary);"><?= formatPrice($item['price'] * $item['qty']) ?></span>
                </div>
                <?php endforeach; ?>
                
                <div style="display: flex; justify-content: space-between; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-dark);">
                    <span style="font-weight: 700; font-size: 1.1rem;">Total</span>
                    <span style="font-weight: 800; font-size: 1.3rem; color: var(--gold-primary);"><?= formatPrice($total) ?></span>
                </div>
                
                <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; margin-top: 1.5rem; padding: 1rem;">
                    Place Order
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
        </div>
    </form>
</section>

<?php $pageTitle = 'Wallet — ShopX Global'; ?>
<style>
    .funds-step-panel { display: none; }
    .funds-step-panel.active-panel { display: block; }
    
    .progress-bar-step {
        transition: background-color 0.3s;
    }
    
    .quick-amount-btn:hover {
        border-color: var(--gold-primary) !important;
        background: #222 !important;
    }
    
    .quick-amount-btn.active-btn {
        border-color: #a259ff !important;
        background: rgba(162, 89, 255, 0.1) !important;
        color: #a259ff !important;
    }
</style>

<section class="container fade-in" style="padding: 2rem 0; max-width: 800px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 2rem;">💰 My Wallet</h1>
    
    <!-- Balance Card -->
    <div class="card" style="text-align: center; padding: 3rem; margin-bottom: 2rem; background: linear-gradient(135deg, var(--bg-card), rgba(212,160,23,0.05)); border-color: var(--border-gold);">
        <p style="color: var(--text-muted); margin-bottom: 0.5rem;">Available Balance</p>
        <p style="font-size: 3rem; font-weight: 900; color: var(--gold-primary);"><?= formatPrice($wallet) ?></p>
        <button onclick="openFundsModal()" class="btn-gold" style="margin-top: 1.5rem;">
            + Add Funds
        </button>
    </div>
    
    <!-- Transaction History -->
    <div class="card">
        <h2 style="font-weight: 700; margin-bottom: 1.25rem;">Transaction History</h2>
        
        <!-- Pending Deposit Requests -->
        <?php if (!empty($depositRequests)): ?>
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-dark); padding-bottom: 1.5rem;">
                <h3 style="font-size: 0.8rem; font-weight: 700; color: var(--tan-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1rem;">⏳ Deposit Requests</h3>
                <div class="space-y-3">
                    <?php foreach ($depositRequests as $req): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #151515; border: 1px solid var(--border-dark); border-radius: 8px;">
                            <div>
                                <p style="font-weight: 700; font-size: 0.9rem; color: #fff; margin-bottom: 0.25rem;">
                                    Deposit via <?= e($req['gateway_name']) ?>
                                </p>
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.75rem;">
                                    <span style="color: var(--text-muted);"><?= timeAgo($req['created_at']) ?></span>
                                    <span style="color: var(--border-dark);">&bull;</span>
                                    <?php if ($req['status'] === 'pending'): ?>
                                        <span class="px-2 py-0.5 bg-yellow-950 text-yellow-500 border border-yellow-900 rounded text-[9px] font-bold uppercase tracking-wider">Pending Admin Approval</span>
                                    <?php elseif ($req['status'] === 'approved'): ?>
                                        <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[9px] font-bold uppercase tracking-wider">Approved</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 bg-red-950 text-red-500 border border-red-900 rounded text-[9px] font-bold uppercase tracking-wider">Rejected</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($req['notes'])): ?>
                                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem; font-style: italic;">
                                        Note: <?= e($req['notes']) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <span style="font-weight: 900; font-size: 1.1rem; color: <?= $req['status'] === 'approved' ? 'var(--green-save)' : ($req['status'] === 'rejected' ? 'var(--red-badge)' : '#e8c158') ?>;">
                                $<?= number_format($req['amount'], 2) ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (empty($transactions)): ?>
            <p style="color: var(--text-muted); text-align: center; padding: 2rem 0;">No completed transactions yet</p>
        <?php else: ?>
            <?php foreach ($transactions as $tx): ?>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid var(--border-dark);">
                    <div>
                        <p style="font-weight: 500; font-size: 0.9rem;"><?= e($tx['description'] ?? ucfirst($tx['type'])) ?></p>
                        <p style="font-size: 0.8rem; color: var(--text-muted);"><?= timeAgo($tx['created_at']) ?></p>
                    </div>
                    <span style="font-weight: 700; color: <?= $tx['amount'] >= 0 ? 'var(--green-save)' : 'var(--red-badge)' ?>;">
                        <?= $tx['amount'] >= 0 ? '+' : '' ?><?= formatPrice($tx['amount']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Add Funds Multi-Step Modal -->
<div class="modal-overlay" id="addFundsModal">
    <div class="modal-content" style="max-width: 480px; padding: 2rem; background: #0f0f12; border: 1px solid #232329; border-radius: 20px;">
        <button class="modal-close" onclick="closeFundsModal()" style="color: var(--text-muted); font-size: 1.5rem;">&times;</button>
        
        <!-- Modal Title Header -->
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
            <div style="width: 44px; height: 44px; background: #a259ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fff;">👛</div>
            <h2 style="font-weight: 800; font-size: 1.3rem; color: #fff;">Add Funds</h2>
        </div>
        
        <!-- Progress Bar -->
        <div id="addFundsProgressBar" style="display: flex; gap: 8px; margin-bottom: 1.5rem;">
            <div class="progress-bar-step" data-step="1" style="flex: 1; height: 4px; background: #a259ff; border-radius: 2px;"></div>
            <div class="progress-bar-step" data-step="2" style="flex: 1; height: 4px; background: var(--border-dark); border-radius: 2px;"></div>
            <div class="progress-bar-step" data-step="3" style="flex: 1; height: 4px; background: var(--border-dark); border-radius: 2px;"></div>
            <div class="progress-bar-step" data-step="4" style="flex: 1; height: 4px; background: var(--border-dark); border-radius: 2px;"></div>
        </div>
        
        <!-- Add Funds Form -->
        <form method="POST" action="<?= url('/wallet/add-funds') ?>" id="addFundsMultiForm" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <!-- Step 1: Select preferred payment method -->
            <div id="step-1" class="funds-step-panel active-panel">
                <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.9rem;">Choose your preferred payment method:</p>
                <div style="display: flex; flex-col; gap: 0.75rem;" class="space-y-2">
                    <?php foreach ($gateways as $g): if ($g['status'] !== 'Active') continue; ?>
                        <div class="payment-method-card" onclick="selectGateway(<?= $g['id'] ?>)" style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; border: 1px solid var(--border-dark); border-radius: 12px; cursor: pointer; transition: all 0.2s; background: #141417;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <span style="font-size: 1.5rem;"><?= e($g['icon']) ?></span>
                                <div>
                                    <div style="font-weight: 700; color: #fff; font-size: 0.95rem;"><?= e($g['name']) ?></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">Min: $<?= e($g['min']) ?></div>
                                </div>
                            </div>
                            <span style="color: var(--text-muted); font-size: 1.2rem;">›</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Step 2: Select Amount -->
            <div id="step-2" class="funds-step-panel">
                <!-- Selected Gateway Display -->
                <div id="selected-gateway-display-s2" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border: 1px solid var(--border-dark); border-radius: 12px; background: #141417; margin-bottom: 1.5rem;">
                    <!-- Filled dynamically -->
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Enter Amount (USD)</label>
                    <div style="position: relative; background: #141417; border: 1px solid var(--border-dark); border-radius: 12px; padding: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.8rem; font-weight: 900; color: var(--text-muted); font-family: monospace;">$</span>
                        <input type="number" id="deposit-amount-input" placeholder="0.00" value="50.00" min="1" step="any" style="background: transparent; border: none; outline: none; font-size: 2rem; font-weight: 900; color: #fff; width: 100%; font-family: monospace;" oninput="updateQuickAmountActiveState(this.value)">
                    </div>
                    <div id="amount-validation-error" style="display: none; color: #ef4444; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;"></div>
                </div>

                <!-- Quick Select Buttons -->
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.75rem; margin-bottom: 2rem;">
                    <button type="button" onclick="setDepositAmount(25)" class="quick-amount-btn" data-val="25" style="padding: 0.75rem; background: #141417; border: 1px solid var(--border-dark); border-radius: 8px; color: #fff; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">$25</button>
                    <button type="button" onclick="setDepositAmount(50)" class="quick-amount-btn active-btn" data-val="50" style="padding: 0.75rem; background: #141417; border: 1px solid var(--border-dark); border-radius: 8px; color: #fff; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">$50</button>
                    <button type="button" onclick="setDepositAmount(100)" class="quick-amount-btn" data-val="100" style="padding: 0.75rem; background: #141417; border: 1px solid var(--border-dark); border-radius: 8px; color: #fff; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">$100</button>
                    <button type="button" onclick="setDepositAmount(250)" class="quick-amount-btn" data-val="250" style="padding: 0.75rem; background: #141417; border: 1px solid var(--border-dark); border-radius: 8px; color: #fff; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s;">$250</button>
                </div>

                <!-- Navigation Controls -->
                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" onclick="backToStep1()" style="width: 52px; background: transparent; border: 1px solid var(--border-dark); border-radius: 10px; color: #fff; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">←</button>
                    <button type="button" onclick="goToStep3()" style="flex: 1; background: linear-gradient(135deg, #a259ff, #7c3aed); border: none; border-radius: 10px; color: #fff; font-weight: 700; font-size: 0.9rem; padding: 0.85rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; outline: none;">Continue ›</button>
                </div>
            </div>

            <!-- Step 3: Payment Details -->
            <div id="step-3" class="funds-step-panel">
                <!-- Sum Display Header -->
                <div style="text-align: center; margin-bottom: 1.5rem; padding: 1.5rem; background: rgba(162, 89, 255, 0.05); border: 1px solid rgba(162, 89, 255, 0.2); border-radius: 12px;">
                    <div style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">Send exactly</div>
                    <div id="payment-details-amount" style="font-size: 2.2rem; font-weight: 900; color: #fff; margin: 0.25rem 0; font-family: monospace;">$50.00</div>
                    <div id="payment-details-gateway" style="font-size: 0.8rem; color: #a259ff; font-weight: 700;">via Bank Transfer (ACH)</div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Payment Details</label>
                    <div style="background: #141417; border: 1px solid var(--border-dark); border-radius: 12px; padding: 1.25rem; position: relative;">
                        <pre id="gateway-address-pre" style="font-family: monospace; font-size: 0.85rem; color: #fff; white-space: pre-wrap; margin: 0; line-height: 1.5;"></pre>
                        <button type="button" onclick="copyGatewayAddress()" style="margin-top: 1rem; width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: #222; border: 1px solid var(--border-dark); border-radius: 8px; color: #fff; font-size: 0.75rem; font-weight: 700; padding: 0.55rem; cursor: pointer; outline: none;">
                            📋 Copy Details
                        </button>
                    </div>
                </div>

                <!-- Warnings instructions box -->
                <div id="gateway-instructions" style="padding: 1rem; background: #23180c; border: 1px solid #c27829; border-radius: 8px; color: #e8c158; font-size: 0.8rem; margin-bottom: 2rem; line-height: 1.4;">
                    Transfer to our bank account. Use your email as reference. Allow 1-2 business days for processing.
                </div>

                <!-- Navigation Controls -->
                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" onclick="backToStep2()" style="width: 52px; background: transparent; border: 1px solid var(--border-dark); border-radius: 10px; color: #fff; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">←</button>
                    <button type="button" onclick="goToStep4()" style="flex: 1; background: linear-gradient(135deg, #a259ff, #7c3aed); border: none; border-radius: 10px; color: #fff; font-weight: 700; font-size: 0.9rem; padding: 0.85rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; outline: none;">I've Paid ›</button>
                </div>
            </div>

            <!-- Step 4: Upload Proof -->
            <div id="step-4" class="funds-step-panel">
                <p style="color: #fff; font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem; text-align: center;">Upload a screenshot of your payment as proof:</p>
                
                <!-- Proof container drag and drop upload click trigger -->
                <div onclick="document.getElementById('proof-uploader').click()" style="border: 2px dashed var(--border-dark); border-radius: 12px; padding: 2.5rem; text-align: center; cursor: pointer; background: #141417; transition: border-color 0.2s;" id="drag-drop-area">
                    <span style="font-size: 2.5rem; display: block; margin-bottom: 0.75rem;">📤</span>
                    <div style="font-weight: 700; color: #fff; font-size: 0.9rem;" id="proof-filename-label">Click to upload screenshot</div>
                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.25rem;">PNG, JPG, JPEG</div>
                    <input type="file" name="screenshot" id="proof-uploader" accept="image/*" style="display: none;" onchange="handleFileUploaded(this)">
                </div>

                <div style="margin-top: 1.5rem; margin-bottom: 2rem;">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem; letter-spacing: 0.05em;">Notes (optional)</label>
                    <textarea name="notes" placeholder="Any notes for admin..." rows="3" style="width: 100%; background: #141417; border: 1px solid var(--border-dark); border-radius: 12px; padding: 0.75rem; color: #fff; font-size: 0.85rem; resize: none; outline: none; font-family: inherit; transition: border-color 0.2s;"></textarea>
                </div>

                <!-- Form fields storage values -->
                <input type="hidden" name="gateway_id" id="post-gateway-id">
                <input type="hidden" name="gateway_name" id="post-gateway-name">
                <input type="hidden" name="amount" id="post-amount">

                <!-- Navigation Controls -->
                <div style="display: flex; gap: 0.75rem;">
                    <button type="button" onclick="backToStep3()" style="width: 52px; background: transparent; border: 1px solid var(--border-dark); border-radius: 10px; color: #fff; font-weight: bold; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">←</button>
                    <button type="submit" style="flex: 1; background: #059669; border: none; border-radius: 10px; color: #fff; font-weight: 700; font-size: 0.9rem; padding: 0.85rem; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; outline: none;">Submit Deposit Request</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
$gatewayJs = [];
foreach ($gateways as $g) {
    $instructions = 'Transfer to our accounts. Use your email as reference. Allow 1-2 business days for processing.';
    $key = 'Account Detail';
    if (stripos($g['name'], 'BTC') !== false || stripos($g['name'], 'Bitcoin') !== false) {
        $key = 'BTC Wallet Address';
        $instructions = 'Transfer exactly the BTC amount to our wallet address. Allow blockchain confirmations.';
    } elseif (stripos($g['name'], 'ETH') !== false || stripos($g['name'], 'Ethereum') !== false) {
        $key = 'ETH Wallet Address';
        $instructions = 'Transfer exactly the ETH amount to our wallet address. Allow blockchain confirmations.';
    } elseif (stripos($g['name'], 'CashApp') !== false) {
        $key = 'CashTag';
        $instructions = 'Send the amount to our CashTag. Include your email in the notes. Allow 10-30 minutes for processing.';
    } elseif (stripos($g['name'], 'PayPal') !== false) {
        $key = 'PayPal Email';
        $instructions = 'Send to our PayPal email address as Friends & Family. Allow 1-2 hours for processing.';
    } elseif (stripos($g['name'], 'Zelle') !== false) {
        $key = 'Zelle Email';
        $instructions = 'Send to our Zelle email address. Use your email as reference. Allow 30-60 minutes for processing.';
    } elseif (stripos($g['name'], 'Bank') !== false || stripos($g['name'], 'ACH') !== false) {
        $key = 'Bank Details';
        $instructions = 'Transfer to our bank account. Use your email as reference. Allow 1-2 business days for processing.';
    }
    
    $gatewayJs[$g['id']] = [
        'id' => $g['id'],
        'name' => $g['name'],
        'min' => $g['min'],
        'key' => $key,
        'val' => $g['address'],
        'instructions' => $instructions,
        'icon' => $g['icon']
    ];
}
?>

<script>
    const gatewayDetails = <?= json_encode($gatewayJs) ?>;
    let selectedGateway = null;
    let selectedAmount = 50.00;

    function openFundsModal() {
        document.getElementById('addFundsModal').classList.add('active');
        goToStep1();
    }

    function closeFundsModal() {
        document.getElementById('addFundsModal').classList.remove('active');
    }

    // Step navigation controller
    function updateProgressBar(step) {
        const steps = document.querySelectorAll('.progress-bar-step');
        steps.forEach(el => {
            const stepNum = parseInt(el.getAttribute('data-step'));
            if (stepNum <= step) {
                el.style.backgroundColor = '#a259ff';
            } else {
                el.style.backgroundColor = 'var(--border-dark)';
            }
        });
    }

    function goToStep1() {
        // Reset panels
        document.querySelectorAll('.funds-step-panel').forEach(p => p.classList.remove('active-panel'));
        document.getElementById('step-1').classList.add('active-panel');
        updateProgressBar(1);
    }

    function selectGateway(id) {
        selectedGateway = gatewayDetails[id];
        
        // Populate Step 2 Gateway Display Card
        const gateInfo = document.getElementById('selected-gateway-display-s2');
        gateInfo.innerHTML = `
            <span style="font-size: 1.5rem;">${selectedGateway.icon}</span>
            <div>
                <div style="font-weight: 700; color: #fff; font-size: 0.9rem;">${selectedGateway.name}</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Min: $${selectedGateway.min}</div>
            </div>
        `;

        // Update default amount value
        const minVal = parseFloat(selectedGateway.min);
        selectedAmount = minVal > selectedAmount ? minVal : selectedAmount;
        document.getElementById('deposit-amount-input').value = selectedAmount.toFixed(2);
        updateQuickAmountActiveState(selectedAmount);

        // Go to Step 2
        document.querySelectorAll('.funds-step-panel').forEach(p => p.classList.remove('active-panel'));
        document.getElementById('step-2').classList.add('active-panel');
        updateProgressBar(2);
    }

    function backToStep1() {
        goToStep1();
    }

    function setDepositAmount(amount) {
        selectedAmount = parseFloat(amount);
        document.getElementById('deposit-amount-input').value = selectedAmount.toFixed(2);
        updateQuickAmountActiveState(selectedAmount);
    }

    function updateQuickAmountActiveState(val) {
        const floatVal = parseFloat(val);
        document.querySelectorAll('.quick-amount-btn').forEach(btn => {
            const btnVal = parseFloat(btn.getAttribute('data-val'));
            if (btnVal === floatVal) {
                btn.classList.add('active-btn');
            } else {
                btn.classList.remove('active-btn');
            }
        });
    }

    function goToStep3() {
        const amtInput = document.getElementById('deposit-amount-input');
        const amount = parseFloat(amtInput.value);
        const errDiv = document.getElementById('amount-validation-error');

        if (isNaN(amount) || amount < parseFloat(selectedGateway.min)) {
            errDiv.innerText = `Minimum deposit amount for ${selectedGateway.name} is $${selectedGateway.min}.`;
            errDiv.style.display = 'block';
            return;
        }
        errDiv.style.display = 'none';
        selectedAmount = amount;

        // Populate Step 3 Display
        document.getElementById('payment-details-amount').innerText = `$${selectedAmount.toFixed(2)}`;
        document.getElementById('payment-details-gateway').innerText = `via ${selectedGateway.name}`;
        
        // Show gateway address parameters
        const detailsPre = document.getElementById('gateway-address-pre');
        detailsPre.innerText = `${selectedGateway.key}:\n${selectedGateway.val}`;

        // Show instruction terms
        document.getElementById('gateway-instructions').innerText = selectedGateway.instructions;

        // Transition
        document.querySelectorAll('.funds-step-panel').forEach(p => p.classList.remove('active-panel'));
        document.getElementById('step-3').classList.add('active-panel');
        updateProgressBar(3);
    }

    function backToStep2() {
        document.querySelectorAll('.funds-step-panel').forEach(p => p.classList.remove('active-panel'));
        document.getElementById('step-2').classList.add('active-panel');
        updateProgressBar(2);
    }

    function copyGatewayAddress() {
        const copyText = selectedGateway.val;
        navigator.clipboard.writeText(copyText).then(() => {
            alert('Copied payment details to clipboard!');
        }).catch(err => {
            alert('Failed to copy text: ' + err);
        });
    }

    function goToStep4() {
        // Feed the forms inputs for the final POST
        document.getElementById('post-gateway-id').value = selectedGateway.id;
        document.getElementById('post-gateway-name').value = selectedGateway.name;
        document.getElementById('post-amount').value = selectedAmount;

        // Reset file uploader text
        document.getElementById('proof-filename-label').innerText = 'Click to upload screenshot';
        document.getElementById('drag-drop-area').style.borderColor = 'var(--border-dark)';

        // Transition
        document.querySelectorAll('.funds-step-panel').forEach(p => p.classList.remove('active-panel'));
        document.getElementById('step-4').classList.add('active-panel');
        updateProgressBar(4);
    }

    function backToStep3() {
        document.querySelectorAll('.funds-step-panel').forEach(p => p.classList.remove('active-panel'));
        document.getElementById('step-3').classList.add('active-panel');
        updateProgressBar(3);
    }

    function handleFileUploaded(input) {
        if (input.files && input.files[0]) {
            const filename = input.files[0].name;
            document.getElementById('proof-filename-label').innerText = `Selected: ${filename}`;
            document.getElementById('drag-drop-area').style.borderColor = '#059669';
        }
    }
</script>

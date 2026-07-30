<div class="fade-in space-y-6">
    <!-- Back Navigation -->
    <div>
        <a href="<?= url('/admin/customers') ?>" class="text-xs font-bold text-[#D4A017] hover:text-[#E8C158] transition-colors inline-flex items-center gap-1">
            ← Return to Customer Directory
        </a>
    </div>

    <!-- Grid Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side: Profile & Configs -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Customer profile edit card -->
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider border-b border-[#2a2a2a] pb-2">👤 Profile Details</h4>
                <form action="<?= url('/admin/customers/update-profile/' . $c['id']) ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Customer Name</label>
                        <input type="text" name="name" value="<?= e($c['name']) ?>" required class="input-dark">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" value="<?= e($c['email']) ?>" required class="input-dark">
                    </div>
                    <button type="submit" class="btn-gold w-full text-xs py-2 justify-center">
                        💾 Update Profile
                    </button>
                </form>
            </div>

            <!-- Wallet adjustment card -->
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider border-b border-[#2a2a2a] pb-2">💳 Wallet Balance Adjuster</h4>
                
                <div class="text-center py-2 bg-[#111] rounded-lg border border-[#2a2a2a]">
                    <span class="block text-[10px] text-gray-500 font-bold uppercase">Current Wallet Balance</span>
                    <span class="text-2xl font-black text-[#D4A017] font-mono">$<?= number_format($c['wallet_balance'], 2) ?></span>
                </div>

                <form action="<?= url('/admin/customers/update-balance/' . $c['id']) ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Adjustment Amount ($)</label>
                        <input type="number" name="amount" min="0.01" step="any" required placeholder="0.00" class="input-dark font-mono">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <button type="submit" name="balance_action" value="add" class="py-2.5 px-3 bg-green-900/30 border border-green-800 text-green-400 hover:bg-green-800 hover:text-white rounded-lg text-xs font-bold transition-all text-center">
                            ➕ Credit Wallet
                        </button>
                        <button type="submit" name="balance_action" value="subtract" class="py-2.5 px-3 bg-red-900/30 border border-red-800 text-red-400 hover:bg-red-800 hover:text-white rounded-lg text-xs font-bold transition-all text-center">
                            ➖ Debit Wallet
                        </button>
                    </div>
                </form>
            </div>

            <!-- Suspension state card -->
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider border-b border-[#2a2a2a] pb-2">🔒 Account Access Status</h4>
                
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-400 font-semibold">Current State:</span>
                    <?php if ($c['is_suspended']): ?>
                        <span class="px-2.5 py-0.5 bg-red-950 text-red-500 border border-red-900 rounded-full text-[10px] font-bold uppercase tracking-wider">Suspended</span>
                    <?php else: ?>
                        <span class="px-2.5 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded-full text-[10px] font-bold uppercase tracking-wider">Active</span>
                    <?php endif; ?>
                </div>

                <form action="<?= url('/admin/customers/update-status/' . $c['id']) ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Account Authorization</label>
                        <select name="status" class="select-dark w-full text-xs">
                            <option value="0" <?= !$c['is_suspended'] ? 'selected' : '' ?>>Activate Account (Unrestricted)</option>
                            <option value="1" <?= $c['is_suspended'] ? 'selected' : '' ?>>Suspend Account (Block Access)</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-[#222] border border-[#2a2a2a] hover:border-[#D4A017] text-white hover:text-black hover:bg-[#D4A017] w-full text-xs py-2 justify-center font-bold rounded-lg transition-all">
                        ⚙️ Update Authorization Status
                    </button>
                </form>
            </div>

            <!-- Direct Email Form -->
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider border-b border-[#2a2a2a] pb-2">📧 Direct Email Dispatcher</h4>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-wider mb-2">Send an email directly to this customer.</p>
                <form action="<?= url('/admin/customers/email/' . $c['id']) ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Subject</label>
                        <input type="text" name="subject" required class="input-dark text-xs w-full" placeholder="Enter email subject">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Message HTML</label>
                        <textarea name="message" required class="input-dark text-xs w-full min-h-[120px]" placeholder="Write your HTML or plain text message here..."></textarea>
                    </div>
                    <button type="submit" class="bg-[#222] border border-[#2a2a2a] hover:border-[#D4A017] text-white hover:text-black hover:bg-[#D4A017] w-full text-xs py-2 justify-center font-bold rounded-lg transition-all">
                        📤 Send Email
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Wallet History logs -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 space-y-4">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider border-b border-[#2a2a2a] pb-2">📝 Wallet Ledger Log (Last 20 transactions)</h4>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-[#111] border-b border-[#2a2a2a] text-[10px] font-bold text-gray-400 uppercase">
                                <th class="p-3">ID</th>
                                <th class="p-3">Type</th>
                                <th class="p-3">Amount</th>
                                <th class="p-3">Ledger Details</th>
                                <th class="p-3">Logged Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-gray-300 divide-y divide-[#2a2a2a]">
                            <?php if (empty($transactions)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-500 font-semibold">No wallet transaction history logged.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($transactions as $t): ?>
                                    <tr>
                                        <td class="p-3 font-mono text-gray-500">#<?= $t['id'] ?></td>
                                        <td class="p-3">
                                            <?php if ($t['type'] === 'deposit'): ?>
                                                <span class="px-1.5 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[9px] font-bold uppercase">Credit</span>
                                            <?php else: ?>
                                                <span class="px-1.5 py-0.5 bg-red-950 text-red-500 border border-red-900 rounded text-[9px] font-bold uppercase">Debit</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3 font-mono font-bold <?= $t['type'] === 'deposit' ? 'text-green-500' : 'text-red-500' ?>">
                                            <?= $t['type'] === 'deposit' ? '+' : '-' ?>$<?= number_format(abs($t['amount']), 2) ?>
                                        </td>
                                        <td class="p-3"><?= e($t['description']) ?></td>
                                        <td class="p-3 font-mono text-gray-500"><?= e($t['created_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

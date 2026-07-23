<div class="fade-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Generate Coupon Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit">
            <h3 class="text-base font-bold text-white mb-1">Generate Coupon</h3>
            <p class="text-xs text-gray-500 mb-6">Issue standard discount codes</p>
            
            <form action="<?= url('/admin/coupons/create') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Discount Code</label>
                    <input type="text" name="code" required placeholder="e.g. FLASH15" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Reduction Type</label>
                    <select name="type" class="select-dark w-full">
                        <option value="Percent">Percent Rate (%)</option>
                        <option value="Flat">Flat Deduction ($)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Discount Amount</label>
                    <input type="number" name="value" required placeholder="e.g. 15" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Usage Limit</label>
                    <input type="number" name="limit" value="100" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Expiration Date</label>
                    <input type="date" name="expiry" value="<?= date('Y-m-d', strtotime('+30 days')) ?>" class="input-dark">
                </div>
                <button type="submit" class="btn-gold w-full justify-center">
                    🎟️ Generate Coupon Code
                </button>
            </form>
        </div>

        <!-- Coupons Table -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden lg:col-span-2">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Voucher Code</th>
                        <th>Deduction Mode</th>
                        <th>Rate / Value</th>
                        <th>Cap / Usage</th>
                        <th>Expiry Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($coupons)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500 font-semibold">No active coupons generated.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($coupons as $c): ?>
                            <tr>
                                <td class="font-mono text-xs"><?= $c['id'] ?></td>
                                <td class="font-bold text-white font-mono text-sm tracking-wide"><?= e($c['code']) ?></td>
                                <td>
                                    <span class="px-2 py-0.5 bg-[#7c3aed]/10 text-[#7c3aed] border border-[#7c3aed]/30 rounded text-[10px] font-bold uppercase tracking-wider"><?= e($c['type']) ?></span>
                                </td>
                                <td class="font-mono font-bold text-[#D4A017]">
                                    <?= $c['type'] === 'Percent' ? e($c['value']) . '%' : '$' . number_format($c['value'], 2) ?>
                                </td>
                                <td class="text-xs">
                                    <span class="text-white font-bold font-mono"><?= $c['used'] ?></span> / <span class="text-gray-500 font-mono"><?= $c['limit'] ?></span>
                                </td>
                                <td class="text-xs font-mono text-gray-400"><?= e($c['expiry']) ?></td>
                                <td>
                                    <form action="<?= url('/admin/coupons/delete/' . $c['id']) ?>" method="POST" onsubmit="return confirm('Revoke this coupon?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-bold text-xs">
                                            ❌ Revoke
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

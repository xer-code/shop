<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Payment Gateways Configurations</h3>
            <p class="text-xs text-gray-500">Configure minimum deposit limits, processing fees, addresses, and active statuses for public checkout gateways</p>
        </div>
    </div>

    <!-- Gateways Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($gateways as $g): ?>
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 hover:border-[#D4A017] transition-all space-y-4 flex flex-col justify-between">
                <!-- Gateway Header -->
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-3xl text-gray-200"><?= e($g['icon']) ?></span>
                            <div>
                                <h4 class="text-base font-bold text-white"><?= e($g['name']) ?></h4>
                                <span class="text-[10px] text-gray-500 font-mono">Gateway #<?= $g['id'] ?></span>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider <?= $g['status'] === 'Active' ? 'bg-green-950 text-green-400 border border-green-900' : 'bg-red-950 text-red-400 border border-red-900' ?>">
                            <?= e($g['status']) ?>
                        </span>
                    </div>

                    <!-- Config Form -->
                    <form action="<?= url('/admin/payment-gateways/update/' . $g['id']) ?>" method="POST" class="space-y-4">
                        <?= csrf_field() ?>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Min Deposit ($)</label>
                                <input type="number" name="min" value="<?= e($g['min']) ?>" min="1" step="any" required class="input-dark text-xs">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Processing Fee ($)</label>
                                <input type="number" name="fee" value="<?= e($g['fee']) ?>" min="0" step="any" required class="input-dark text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Receiving Address / Account Details</label>
                            <input type="text" name="address" value="<?= e($g['address']) ?>" required class="input-dark text-xs font-mono">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Gateway Status</label>
                            <select name="status" class="select-dark w-full text-xs">
                                <option value="Active" <?= $g['status'] === 'Active' ? 'selected' : '' ?>>Active (Enabled)</option>
                                <option value="Inactive" <?= $g['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive (Disabled)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn-gold w-full text-xs py-2 justify-center mt-2">
                            💾 Save Settings
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

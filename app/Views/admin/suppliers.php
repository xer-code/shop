<div class="fade-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Supplier Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit">
            <h3 class="text-base font-bold text-white mb-1">Register Supplier</h3>
            <p class="text-xs text-gray-500 mb-6">Onboard a new wholesale supply partner</p>
            
            <form action="<?= url('/admin/suppliers/create') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Company Name</label>
                    <input type="text" name="name" required placeholder="e.g. Acme Corporation" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Contact Email</label>
                    <input type="email" name="contact" required placeholder="e.g. sales@acme.com" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Primary Category</label>
                    <select name="category" class="select-dark w-full">
                        <option value="Electronics">Electronics</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Home & Living">Home & Living</option>
                        <option value="Gaming">Gaming</option>
                        <option value="Automotive">Automotive</option>
                    </select>
                </div>
                <button type="submit" class="btn-gold w-full justify-center">
                    🤝 Add Supplier Partner
                </button>
            </form>
        </div>

        <!-- Supplier Directory Table -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden lg:col-span-2">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Supplier Partner</th>
                        <th>Email Contacts</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($suppliers)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500 font-semibold">No suppliers onboarded.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($suppliers as $s): ?>
                            <tr>
                                <td class="font-mono text-xs"><?= $s['id'] ?></td>
                                <td class="font-semibold text-white"><?= e($s['name']) ?></td>
                                <td class="text-sm font-mono"><?= e($s['contact']) ?></td>
                                <td>
                                    <span class="px-2 py-0.5 bg-[#8b5cf6]/10 text-[#8b5cf6] border border-[#8b5cf6]/30 rounded text-[10px] font-bold uppercase tracking-wider"><?= e($s['category']) ?></span>
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[10px] font-bold uppercase tracking-wider"><?= e($s['status']) ?></span>
                                </td>
                                <td>
                                    <form action="<?= url('/admin/suppliers/delete/' . $s['id']) ?>" method="POST" onsubmit="return confirm('Remove this supplier?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-bold text-xs">
                                            ❌ Remove
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

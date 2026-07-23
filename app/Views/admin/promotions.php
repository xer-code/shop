<div class="fade-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Promotion Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit">
            <h3 class="text-base font-bold text-white mb-1">Launch Campaign</h3>
            <p class="text-xs text-gray-500 mb-6">Create promotional banner or popup campaigns</p>
            
            <form action="<?= url('/admin/promotions/create') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Campaign Name</label>
                    <input type="text" name="name" required placeholder="e.g. Winter Electronics Fest" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Display Type</label>
                    <select name="type" class="select-dark w-full">
                        <option value="Banner">Banner Overlay</option>
                        <option value="Popup">Popup Alert</option>
                        <option value="Carousel">Carousel Slide</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Discount details</label>
                    <input type="text" name="discount" required placeholder="e.g. 15% Off storewide" class="input-dark">
                </div>
                <button type="submit" class="btn-gold w-full justify-center">
                    🔥 Launch Campaign Active
                </button>
            </form>
        </div>

        <!-- Promotions Directory Table -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden lg:col-span-2">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Campaign Name</th>
                        <th>Format Type</th>
                        <th>Discount Value</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($promotions)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500 font-semibold">No active promotional campaigns.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($promotions as $p): ?>
                            <tr>
                                <td class="font-mono text-xs"><?= $p['id'] ?></td>
                                <td class="font-semibold text-white"><?= e($p['name']) ?></td>
                                <td>
                                    <span class="px-2 py-0.5 bg-[#8b5cf6]/10 text-[#8b5cf6] border border-[#8b5cf6]/30 rounded text-[10px] font-bold uppercase tracking-wider"><?= e($p['type']) ?></span>
                                </td>
                                <td class="font-medium text-[#D4A017]"><?= e($p['discount']) ?></td>
                                <td>
                                    <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[10px] font-bold uppercase tracking-wider">Active</span>
                                </td>
                                <td>
                                    <form action="<?= url('/admin/promotions/delete/' . $p['id']) ?>" method="POST" onsubmit="return confirm('Archive this campaign?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-bold text-xs">
                                            ❌ Archive
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

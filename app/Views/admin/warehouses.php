<div class="fade-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Add Warehouse Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit">
            <h3 class="text-base font-bold text-white mb-1">Add Warehouse Profile</h3>
            <p class="text-xs text-gray-500 mb-6">Register a storage hub location</p>
            
            <form action="<?= url('/admin/warehouses/create') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Warehouse Name</label>
                    <input type="text" name="name" required placeholder="e.g. London Global Hub" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Location Address</label>
                    <input type="text" name="location" required placeholder="e.g. Heathrow Industrial Park" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Initial Storage Utilization</label>
                    <input type="text" name="capacity" placeholder="e.g. 45%" class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Site Manager</label>
                    <input type="text" name="manager" placeholder="e.g. John Doe" class="input-dark">
                </div>
                <button type="submit" class="btn-gold w-full justify-center">
                    🏢 Add Warehouse Hub
                </button>
            </form>
        </div>

        <!-- Warehouses Table -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden lg:col-span-2">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Warehouse Facility</th>
                        <th>Location</th>
                        <th>Capacity</th>
                        <th>Manager</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($warehouses)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500 font-semibold">No warehouse hubs registered.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($warehouses as $w): ?>
                            <tr>
                                <td class="font-mono text-xs"><?= $w['id'] ?></td>
                                <td class="font-semibold text-white"><?= e($w['name']) ?></td>
                                <td class="text-xs"><?= e($w['location']) ?></td>
                                <td>
                                    <!-- Progress Bar indicator -->
                                    <div class="w-24">
                                        <div class="flex justify-between text-[10px] mb-1 font-mono">
                                            <span>Used</span>
                                            <span class="text-[#D4A017] font-bold"><?= e($w['capacity']) ?></span>
                                        </div>
                                        <div class="w-full bg-[#2a2a2a] h-1.5 rounded-full overflow-hidden">
                                            <div class="bg-[#D4A017] h-full" style="width: <?= e($w['capacity']) ?>;"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-sm"><?= e($w['manager']) ?></td>
                                <td>
                                    <form action="<?= url('/admin/warehouses/delete/' . $w['id']) ?>" method="POST" onsubmit="return confirm('Delete this warehouse profile?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-bold text-xs">
                                            ❌ Delete
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

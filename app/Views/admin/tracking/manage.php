<div class="fade-in space-y-6" style="max-width: 900px; margin: 0 auto; padding: 1.5rem 0;">
    <div>
        <a href="<?= url('/admin/tracking') ?>" class="text-xs text-gray-400 hover:text-white transition-colors" style="text-decoration: none; display: inline-flex; align-items: center; gap: 0.25rem;">
            ← Back to Logistics Records
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Update status Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit lg:col-span-1">
            <h3 class="text-base font-bold text-white mb-1">Update Logistics Status</h3>
            <p class="text-xs text-gray-500 mb-6">Log a new transit checkpoint event</p>
            
            <form action="<?= url('/admin/tracking/manage/' . urlencode($item['tracking_code'])) ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Package Status Category</label>
                    <select name="status" class="select-dark w-full">
                        <option value="Pending" <?= $item['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Processing" <?= $item['status'] === 'Processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="Out for delivery" <?= $item['status'] === 'Out for delivery' ? 'selected' : '' ?>>Out for delivery</option>
                        <option value="Shipped" <?= $item['status'] === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option value="In transit" <?= $item['status'] === 'In transit' ? 'selected' : '' ?>>In transit</option>
                        <option value="Delivered" <?= $item['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                        <option value="Cancelled" <?= $item['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Transit Location</label>
                    <input type="text" name="location" required placeholder="e.g. Sorting Hub, Tokyo" class="input-dark">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status Description / Milestone</label>
                    <input type="text" name="description" required placeholder="e.g. Package arrived at port" class="input-dark">
                </div>

                <button type="submit" class="btn-gold w-full justify-center py-2.5" style="border-radius: 8px;">
                    📍 Save Transit Milestone
                </button>
            </form>
        </div>

        <!-- Info & Logs History -->
        <div class="space-y-6 lg:col-span-2">
            <!-- Summary Card -->
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
                <h3 class="text-base font-bold text-white mb-4 border-b border-[#2a2a2a] pb-2">📦 Waybill Shipment Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold">Tracking Code</span><br>
                        <strong class="text-xs text-[#D4A017] font-mono"><?= e($item['tracking_code']) ?></strong>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold">Logistics Type</span><br>
                        <strong class="text-xs text-white"><?= e($item['type']) ?></strong>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold">Associated Customer / Receiver</span><br>
                        <span class="text-xs text-white"><?= e($item['user']) ?></span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-500 uppercase font-bold">Destination Address</span><br>
                        <span class="text-xs text-white" title="<?= e($item['destination']) ?>"><?= e($item['destination']) ?></span>
                    </div>
                </div>
            </div>

            <!-- Logistics logs list -->
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
                <h3 class="text-base font-bold text-white mb-4">📜 Checkpoint History</h3>
                <?php if (empty($logs)): ?>
                    <p class="text-xs text-gray-500 italic">No checkpoint events logged yet.</p>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 1rem; border-left: 2px solid #2a2a2a; padding-left: 1.25rem; margin-left: 0.5rem;">
                        <?php foreach ($logs as $index => $log): ?>
                            <div style="position: relative;">
                                <div style="position: absolute; left: -1.65rem; top: 6px; width: 10px; height: 10px; border-radius: 50%; background: <?= $index === 0 ? 'var(--gold-primary)' : '#444' ?>; border: 2px solid #111;"></div>
                                <div class="bg-[#111] p-3 rounded-lg border border-[#222]">
                                    <div class="flex justify-between items-start gap-4">
                                        <div>
                                            <p class="text-xs font-bold text-white"><?= e($log['status']) ?></p>
                                            <p class="text-[10px] text-gray-500 mt-1">📍 <?= e($log['location']) ?></p>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-mono"><?= e($log['timestamp']) ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

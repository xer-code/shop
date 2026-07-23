<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Wholesale Requests & Quotes</h3>
            <p class="text-xs text-gray-500">Manage price negotiations and volume sales quotes from enterprise buyers</p>
        </div>
    </div>

    <!-- Quotes Table -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Quote Ref</th>
                    <th>Enterprise Client</th>
                    <th>Requested Goods</th>
                    <th>Offered Target Price</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($quotes)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500 font-semibold">No active wholesale quotes requested.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($quotes as $q): ?>
                        <tr>
                            <td class="font-mono text-sm font-bold text-white">#QT-00<?= $q['id'] ?></td>
                            <td class="font-bold text-gray-300"><?= e($q['customer']) ?></td>
                            <td><?= e($q['items']) ?></td>
                            <td class="font-mono font-bold text-[#D4A017]">$<?= number_format($q['target_price'], 2) ?></td>
                            <td class="text-xs"><?= e($q['date']) ?></td>
                            <td>
                                <?php if ($q['status'] === 'Approved'): ?>
                                    <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[10px] font-bold uppercase tracking-wider">Approved</span>
                                <?php elseif ($q['status'] === 'Rejected'): ?>
                                    <span class="px-2 py-0.5 bg-red-950 text-red-500 border border-red-900 rounded text-[10px] font-bold uppercase tracking-wider">Rejected</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-yellow-950 text-yellow-500 border border-yellow-900 rounded text-[10px] font-bold uppercase tracking-wider">Pending Review</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <form action="<?= url('/admin/quotes/update-status/' . $q['id']) ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="status" value="Approved">
                                        <button type="submit" class="px-2 py-1 bg-green-950 text-green-500 hover:bg-green-900 hover:text-white border border-green-900 rounded text-[10px] font-bold transition-all">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <form action="<?= url('/admin/quotes/update-status/' . $q['id']) ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="status" value="Rejected">
                                        <button type="submit" class="px-2 py-1 bg-red-950 text-red-500 hover:bg-red-900 hover:text-white border border-red-900 rounded text-[10px] font-bold transition-all">
                                            ✕ Reject
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

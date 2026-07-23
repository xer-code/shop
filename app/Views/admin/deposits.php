<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Pending Deposits Approval</h3>
            <p class="text-xs text-gray-500">Review, approve, or reject user wallet fund deposit requests and uploaded proof screenshots</p>
        </div>
    </div>

    <!-- Deposit Requests List Table -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Req ID</th>
                    <th>Customer</th>
                    <th>Gateway Method</th>
                    <th>Amount Requested</th>
                    <th>Notes / Ref</th>
                    <th>Proof Screenshot</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($deposits)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-8 text-gray-500 font-semibold">No deposit requests recorded in the system.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($deposits as $d): ?>
                        <tr>
                            <td class="font-mono text-xs">#DEP-0<?= $d['id'] ?></td>
                            <td>
                                <div class="font-bold text-white"><?= e($d['customer_name']) ?></div>
                                <div class="text-[10px] text-gray-500 font-mono"><?= e($d['customer_email']) ?></div>
                            </td>
                            <td>
                                <span class="font-semibold text-gray-300"><?= e($d['gateway_name']) ?></span>
                            </td>
                            <td class="font-mono font-bold text-[#D4A017]">$<?= number_format($d['amount'], 2) ?></td>
                            <td class="text-xs max-w-xs truncate" title="<?= e($d['notes']) ?>">
                                <?= !empty($d['notes']) ? e($d['notes']) : '<span class="text-gray-600 font-normal">None</span>' ?>
                            </td>
                            <td>
                                <?php if (!empty($d['screenshot_path'])): ?>
                                    <a href="<?= url('/' . $d['screenshot_path']) ?>" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#222] border border-[#2a2a2a] hover:border-[#D4A017] text-xs font-bold text-gray-300 rounded transition-all">
                                        🖼️ View Proof
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-600 text-xs">No file</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($d['status'] === 'pending'): ?>
                                    <span class="px-2 py-0.5 bg-yellow-950 text-yellow-500 border border-yellow-900 rounded text-[10px] font-bold uppercase tracking-wider">Pending</span>
                                <?php elseif ($d['status'] === 'approved'): ?>
                                    <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[10px] font-bold uppercase tracking-wider">Approved</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-red-950 text-red-500 border border-red-900 rounded text-[10px] font-bold uppercase tracking-wider">Rejected</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($d['status'] === 'pending'): ?>
                                    <div class="flex items-center gap-2">
                                        <form action="<?= url('/admin/deposits/approve/' . $d['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="px-2.5 py-1 bg-green-900/20 border border-green-800 text-green-400 hover:bg-green-800 hover:text-white rounded text-xs font-bold transition-all">
                                                ✓ Approve
                                            </button>
                                        </form>
                                        <form action="<?= url('/admin/deposits/reject/' . $d['id']) ?>" method="POST" class="inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="px-2.5 py-1 bg-red-900/20 border border-red-800 text-red-400 hover:bg-red-800 hover:text-white rounded text-xs font-bold transition-all">
                                                ✕ Reject
                                            </button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-600 text-xs font-semibold">Processed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

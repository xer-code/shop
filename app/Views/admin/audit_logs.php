<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Security Audit Trail</h3>
            <p class="text-xs text-gray-500">Immutable ledger tracking administrative updates, database operations, and user role adjustments</p>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Operator Email</th>
                    <th>Action Executed</th>
                    <th>IP Location</th>
                    <th>Log Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500 font-semibold">No audit logs recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $l): ?>
                        <tr>
                            <td class="font-mono text-xs">#LOG-0<?= $l['id'] ?></td>
                            <td class="font-bold text-gray-300"><?= e($l['user']) ?></td>
                            <td class="text-white text-sm font-semibold"><?= e($l['action']) ?></td>
                            <td class="font-mono text-xs text-gray-400"><?= e($l['ip']) ?></td>
                            <td class="text-xs font-mono"><?= e($l['timestamp']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

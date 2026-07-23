<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Financial Ledger</h3>
            <p class="text-xs text-gray-500">Live ledger of payment gateways, wallet transactions, and deposit accounts</p>
        </div>
        <div class="text-xs text-gray-500 font-semibold font-mono">
            DB Connections: <span class="text-green-500">Online</span>
        </div>
    </div>

    <!-- Payments Ledger Table -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Logged Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500 font-semibold">No transactions recorded.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td class="font-mono text-xs"><?= $t['id'] ?></td>
                            <td>
                                <div class="font-bold text-white"><?= e($t['customer_name']) ?></div>
                                <div class="text-[10px] text-gray-500 font-mono"><?= e($t['customer_email']) ?></div>
                            </td>
                            <td>
                                <?php if ($t['type'] === 'deposit'): ?>
                                    <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[10px] font-bold uppercase tracking-wider">Deposit</span>
                                <?php elseif ($t['type'] === 'purchase'): ?>
                                    <span class="px-2 py-0.5 bg-red-950 text-red-500 border border-red-900 rounded text-[10px] font-bold uppercase tracking-wider">Purchase</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-blue-950 text-blue-500 border border-blue-900 rounded text-[10px] font-bold uppercase tracking-wider"><?= e($t['type']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="font-mono font-bold <?= $t['type'] === 'deposit' ? 'text-green-500' : 'text-red-500' ?>">
                                <?= $t['type'] === 'deposit' ? '+' : '-' ?>$<?= number_format($t['amount'], 2) ?>
                            </td>
                            <td class="text-sm"><?= e($t['description']) ?></td>
                            <td class="text-xs font-mono"><?= e($t['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Billing Invoices</h3>
            <p class="text-xs text-gray-500">Record of customer transaction invoices and billing receipts</p>
        </div>
    </div>

    <!-- Invoices List Table -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Invoice ID</th>
                    <th>Order Ref</th>
                    <th>Customer Name</th>
                    <th>Billing Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500 font-semibold">No invoices generated.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($invoices as $inv): ?>
                        <tr>
                            <td class="font-mono text-sm font-bold text-white">#INV-<?= $inv['id'] ?></td>
                            <td class="font-mono text-xs">#<?= $inv['order_id'] ?></td>
                            <td><?= e($inv['customer']) ?></td>
                            <td class="text-xs"><?= e($inv['date']) ?></td>
                            <td class="font-mono font-bold text-[#D4A017]">$<?= number_format($inv['total'], 2) ?></td>
                            <td>
                                <?php if ($inv['status'] === 'Paid'): ?>
                                    <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[10px] font-bold uppercase tracking-wider">Paid</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-yellow-950 text-yellow-500 border border-yellow-900 rounded text-[10px] font-bold uppercase tracking-wider">Unpaid</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= url('/admin/invoices/' . $inv['id']) ?>" class="px-2.5 py-1 bg-[#2a2a2a] hover:bg-[#D4A017] text-white hover:text-black text-xs font-bold rounded transition-all">
                                    🔍 View Invoice
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

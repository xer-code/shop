<div class="fade-in space-y-6">
    <!-- Action controls -->
    <div class="flex justify-between items-center bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-4">
        <a href="<?= url('/admin/invoices') ?>" class="text-xs font-bold text-[#D4A017] hover:text-[#E8C158] transition-colors">
            ← Return to Invoices Ledger
        </a>
        <button onclick="window.print();" class="px-4 py-1.5 bg-[#D4A017] hover:bg-[#E8C158] text-black font-bold text-xs rounded transition-all">
            🖨️ Print / Download PDF
        </button>
    </div>

    <!-- Invoice Sheet (Printable Area) -->
    <div id="invoice-sheet" class="bg-[#111] border border-[#2a2a2a] rounded-xl p-8 max-w-3xl mx-auto shadow-2xl space-y-8 text-sm">
        <!-- Invoice Header -->
        <div class="flex justify-between items-start border-b border-[#2a2a2a] pb-6">
            <div>
                <div class="text-2xl font-black text-white">SHOP<span class="text-[#D4A017]">X</span> GLOBAL</div>
                <div class="text-xs text-gray-500 font-mono mt-1">E-Commerce International Enterprise</div>
            </div>
            <div class="text-right">
                <div class="text-xl font-bold text-white uppercase tracking-wider">INVOICE</div>
                <div class="text-sm font-mono text-[#D4A017] font-bold mt-1">#INV-<?= $invoice['id'] ?></div>
            </div>
        </div>

        <!-- Meta Details -->
        <div class="grid grid-cols-2 gap-8 text-xs border-b border-[#2a2a2a] pb-6">
            <div>
                <h4 class="font-bold text-gray-400 uppercase tracking-wider mb-2">Billed To:</h4>
                <div class="text-sm font-bold text-white"><?= e($invoice['customer']) ?></div>
                <div class="text-gray-400 mt-1">Registered Customer Portal Account</div>
                <div class="text-gray-500 mt-0.5">Reference User Account #<?= $invoice['order_id'] + 4 ?></div>
            </div>
            <div class="text-right">
                <h4 class="font-bold text-gray-400 uppercase tracking-wider mb-2">Invoice Info:</h4>
                <div class="text-gray-400">Date Issued: <strong class="text-white"><?= e($invoice['date']) ?></strong></div>
                <div class="text-gray-400 mt-1">Payment Method: <strong class="text-[#D4A017]">Wallet Balance</strong></div>
                <div class="text-gray-400 mt-1">Order Ref: <strong class="text-white">#<?= $invoice['order_id'] ?></strong></div>
            </div>
        </div>

        <!-- Line items -->
        <div class="space-y-4">
            <h4 class="font-bold text-gray-400 uppercase tracking-wider">Invoice Line Items</h4>
            <div class="border border-[#2a2a2a] rounded-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-[#1a1a1a] border-b border-[#2a2a2a] text-xs font-bold text-gray-400 uppercase">
                            <th class="p-3">Item Description</th>
                            <th class="p-3 text-center">Qty</th>
                            <th class="p-3 text-right">Unit Price</th>
                            <th class="p-3 text-right">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-[#2a2a2a] text-white">
                            <td class="p-3">
                                <div class="font-bold">Purchase of Marketplace Goods</div>
                                <div class="text-xs text-gray-500 font-mono mt-0.5">Order Ref #<?= $invoice['order_id'] ?> package routing</div>
                            </td>
                            <td class="p-3 text-center font-mono">1</td>
                            <td class="p-3 text-right font-mono">$<?= number_format($invoice['total'] - ($invoice['total'] * 0.085), 2) ?></td>
                            <td class="p-3 text-right font-mono">$<?= number_format($invoice['total'] - ($invoice['total'] * 0.085), 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Calculations -->
        <div class="flex justify-end pt-4">
            <div class="w-64 space-y-2 text-xs">
                <div class="flex justify-between text-gray-400">
                    <span>Subtotal:</span>
                    <span class="font-mono text-white">$<?= number_format($invoice['total'] - ($invoice['total'] * 0.085), 2) ?></span>
                </div>
                <div class="flex justify-between text-gray-400 border-b border-[#2a2a2a] pb-2">
                    <span>VAT (8.5%):</span>
                    <span class="font-mono text-white">$<?= number_format($invoice['total'] * 0.085, 2) ?></span>
                </div>
                <div class="flex justify-between text-sm font-bold text-white pt-1">
                    <span>Grand Total:</span>
                    <span class="font-mono text-[#D4A017]">$<?= number_format($invoice['total'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Footer terms -->
        <div class="text-center text-[10px] text-gray-600 border-t border-[#2a2a2a] pt-6 font-mono">
            Thank you for shopping at ShopX Global. For inquiries, email support@shopx.com.
        </div>
    </div>
</div>

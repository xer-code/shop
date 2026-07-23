<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden">
        <div style="padding: 1.5rem; border-bottom: 1px solid #222; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h3 class="text-base font-bold text-white mb-1">Package Logistics Waybill Records</h3>
                <p class="text-xs text-gray-500">Registry of store orders and manually dispatched shipments</p>
            </div>
            <a href="<?= url('/admin/shipments') ?>" class="btn-gold text-xs py-2 px-4" style="border-radius: 8px;">
                🚢 Dispatch New Shipment
            </a>
        </div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>Waybill / Tracking Code</th>
                    <th>Logistics Type</th>
                    <th>Associated User / Receiver</th>
                    <th>Destination</th>
                    <th>Delivery Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($trackingList)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500 font-semibold">No package logistics waybills found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($trackingList as $item): ?>
                        <tr>
                            <td class="font-mono text-xs text-[#D4A017] font-bold"><?= e($item['tracking_code']) ?></td>
                            <td>
                                <span class="px-2 py-0.5 bg-zinc-900 border border-zinc-800 text-zinc-300 rounded text-[9px] font-bold uppercase tracking-wider">
                                    <?= e($item['type']) ?>
                                </span>
                            </td>
                            <td class="text-xs font-semibold text-white"><?= e($item['user']) ?></td>
                            <td class="text-xs text-gray-400" title="<?= e($item['destination']) ?>"><?= e(truncate($item['destination'], 45)) ?></td>
                            <td>
                                <span class="badge-status badge-<?= strtolower(str_replace(' ', '-', $item['status'])) ?>" style="font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 4px;">
                                    <?= e(ucfirst($item['status'])) ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= url('/admin/tracking/manage/' . urlencode($item['tracking_code'])) ?>" class="btn-gold btn-sm py-1 px-2.5 text-xs" style="border-radius: 6px; display: inline-flex; align-items: center; gap: 0.25rem;">
                                    ⚙️ Manage
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

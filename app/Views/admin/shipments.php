<div class="fade-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Dispatch Shipment Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit">
            <h3 class="text-base font-bold text-white mb-1">Create Manual Shipment</h3>
            <p class="text-xs text-gray-500 mb-6">Dispatch goods and auto-generate tracking code</p>
            
            <form action="<?= url('/admin/shipments/create') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                
                <!-- Sender Details -->
                <div style="border-bottom: 1px solid #222; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                    <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">1. Sender Details</h4>
                    <div class="space-y-2">
                        <input type="text" name="sender_name" required placeholder="Sender Full Name" class="input-dark text-xs">
                        <input type="text" name="sender_address" placeholder="Sender Address" class="input-dark text-xs">
                        <input type="text" name="sender_contact" placeholder="Sender Contact (Phone or Email)" class="input-dark text-xs">
                    </div>
                </div>

                <!-- Receiver Details -->
                <div style="border-bottom: 1px solid #222; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                    <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">2. Receiver Details</h4>
                    <div class="space-y-2">
                        <input type="text" name="receiver_name" required placeholder="Receiver Full Name" class="input-dark text-xs">
                        <input type="text" name="receiver_address" required placeholder="Receiver Destination Address" class="input-dark text-xs">
                        <input type="text" name="receiver_contact" placeholder="Receiver Contact (Phone or Email)" class="input-dark text-xs">
                    </div>
                </div>

                <!-- Product Details -->
                <div style="border-bottom: 1px solid #222; padding-bottom: 0.75rem; margin-bottom: 0.75rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem;">
                        <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">3. Products & Value</h4>
                        <button type="button" id="add-product-btn" style="background: rgba(212, 160, 23, 0.15); color: #D4A017; border: 1px solid rgba(212, 160, 23, 0.3); padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.7rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem;" class="hover:bg-[#D4A017] hover:text-black transition-colors">
                            <span>+</span> Add Product
                        </button>
                    </div>

                    <div id="products-list" class="space-y-3">
                        <div class="product-item p-2.5 bg-[#111] border border-[#2a2a2a] rounded-lg relative space-y-2">
                            <div class="flex justify-between items-center text-[10px] font-bold text-gray-400">
                                <span class="product-index-label">Product #1</span>
                                <button type="button" class="remove-product-btn text-red-400 hover:text-red-300 text-[10px] font-bold px-1.5 py-0.5 bg-red-950/40 border border-red-900/50 rounded hidden" title="Remove product">
                                    ✕ Remove
                                </button>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="text" name="product_type[]" required placeholder="Product Type (e.g. Parts)" class="input-dark text-xs">
                                <input type="text" name="product_weight[]" placeholder="Weight (e.g. 5kg)" class="input-dark text-xs">
                            </div>
                            <input type="number" name="amount[]" step="0.01" required placeholder="Declared Value Amount ($)" class="input-dark text-xs">
                        </div>
                    </div>
                </div>

                <!-- Carrier & Routing -->
                <div>
                    <h4 style="font-size: 0.75rem; font-weight: 700; color: var(--gold-primary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">4. Carrier & Routing</h4>
                    <div class="space-y-2">
                        <select name="carrier" class="select-dark w-full text-xs">
                            <option value="DHL Express">DHL Express</option>
                            <option value="FedEx Economy">FedEx Economy</option>
                            <option value="UPS WorldWide">UPS WorldWide</option>
                            <option value="Royal Mail">Royal Mail</option>
                            <option value="ShopX Logistics">ShopX Logistics</option>
                        </select>
                        <input type="text" name="origin" placeholder="Origin Hub location" class="input-dark text-xs">
                        <input type="text" name="destination" required placeholder="Destination address (again)" class="input-dark text-xs">
                    </div>
                </div>

                <div class="text-[10px] text-gray-500 italic mt-2">
                    💡 Tracking code will be auto-generated on submission using the settings prefix.
                </div>

                <button type="submit" class="btn-gold w-full justify-center text-xs py-2">
                    🚢 Dispatch Manual Shipment
                </button>
            </form>
        </div>

        <!-- Shipments Table -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl lg:col-span-2 overflow-hidden">
            <div style="overflow-x: auto; width: 100%;">
                <table class="data-table" style="width: 100%; min-width: 700px;">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Receiver Details</th>
                            <th>Tracking Code</th>
                            <th>Routing Route</th>
                            <th>Delivery Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($shipments)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-8 text-gray-500 font-semibold">No active shipments in transit.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($shipments as $s): ?>
                                <tr>
                                    <td class="text-xs">
                                        <?php if (!empty($s['order_id'])): ?>
                                            <span class="font-mono text-[#D4A017] font-bold">#<?= $s['order_id'] ?></span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 bg-gray-800 text-gray-400 rounded text-[9px] font-bold uppercase">Manual</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-xs">
                                        <div class="font-bold text-white"><?= e($s['receiver_name'] ?? 'N/A') ?></div>
                                        <div class="text-[10px] text-gray-500"><?= e($s['receiver_contact'] ?? '') ?></div>
                                        <?php if (!empty($s['products']) && is_array($s['products'])): ?>
                                            <div class="text-[10px] text-[#D4A017] mt-1 flex flex-wrap gap-1">
                                                <span class="font-semibold">📦 <?= count($s['products']) ?> <?= count($s['products']) === 1 ? 'Product' : 'Products' ?>:</span>
                                                <span class="text-gray-300"><?= e(implode(', ', array_map(fn($p) => $p['type'] ?? 'Item', $s['products']))) ?></span>
                                            </div>
                                        <?php elseif (!empty($s['product_type'])): ?>
                                            <div class="text-[10px] text-[#D4A017] mt-1">
                                                📦 <?= e($s['product_type']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="font-mono text-xs text-[#D4A017]"><?= e($s['tracking_code']) ?></td>
                                    <td class="text-xs">
                                        <div class="text-[10px] text-gray-500">From: <?= e($s['origin']) ?></div>
                                        <div class="text-[10px] text-gray-300">To: <?= e($s['destination']) ?></div>
                                    </td>
                                    <td>
                                        <span class="badge-status badge-<?= strtolower($s['status'] ?? 'pending') ?>" style="font-size: 0.65rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 4px;">
                                            <?= e($s['status'] ?? 'Pending') ?>
                                        </span>
                                    </td>
                                    <td class="text-xs">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <!-- Change Status dropdown trigger -->
                                            <form action="<?= url('/admin/shipments/update-status/' . $s['id']) ?>" method="POST" style="display: flex; gap: 0.25rem; align-items: center;">
                                                <?= csrf_field() ?>
                                                <select name="status" class="bg-[#111] border border-[#2a2a2a] text-xs text-white rounded p-1 outline-none">
                                                    <option value="Pending" <?= $s['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="Processing" <?= $s['status'] === 'Processing' ? 'selected' : '' ?>>Processing</option>
                                                    <option value="Out for delivery" <?= $s['status'] === 'Out for delivery' ? 'selected' : '' ?>>Out for delivery</option>
                                                    <option value="Shipped" <?= $s['status'] === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                                                    <option value="In transit" <?= $s['status'] === 'In transit' ? 'selected' : '' ?>>In transit</option>
                                                    <option value="Delivered" <?= $s['status'] === 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                                    <option value="Cancelled" <?= $s['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                </select>
                                                <button type="submit" class="px-2 py-1 bg-[#222] hover:bg-[#D4A017] hover:text-black text-xs font-bold rounded transition-colors">
                                                    Save
                                                </button>
                                            </form>

                                            <!-- Delete Shipment Form -->
                                            <form action="<?= url('/admin/shipments/delete/' . $s['id']) ?>" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete shipment #<?= $s['id'] ?>?');" style="display: inline;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="px-2 py-1 bg-red-950 text-red-400 hover:bg-red-900 hover:text-white border border-red-900 text-xs font-bold rounded transition-colors whitespace-nowrap">
                                                    🗑️ Delete
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
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsList = document.getElementById('products-list');
    const addProductBtn = document.getElementById('add-product-btn');

    function updateProductIndices() {
        if (!productsList) return;
        const items = productsList.querySelectorAll('.product-item');
        items.forEach((item, idx) => {
            const label = item.querySelector('.product-index-label');
            if (label) {
                label.textContent = `Product #${idx + 1}`;
            }
            const removeBtn = item.querySelector('.remove-product-btn');
            if (removeBtn) {
                if (items.length > 1) {
                    removeBtn.classList.remove('hidden');
                } else {
                    removeBtn.classList.add('hidden');
                }
            }
        });
    }

    if (addProductBtn && productsList) {
        addProductBtn.addEventListener('click', function() {
            const firstItem = productsList.querySelector('.product-item');
            if (!firstItem) return;

            const newItem = firstItem.cloneNode(true);
            
            // Clear inputs inside newly added product block
            const inputs = newItem.querySelectorAll('input');
            inputs.forEach(input => {
                input.value = '';
            });

            productsList.appendChild(newItem);
            updateProductIndices();

            // Focus on the first input of the new product
            const firstInput = newItem.querySelector('input');
            if (firstInput) {
                firstInput.focus();
            }
        });

        productsList.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-product-btn');
            if (removeBtn) {
                const items = productsList.querySelectorAll('.product-item');
                if (items.length > 1) {
                    const itemToRemove = removeBtn.closest('.product-item');
                    if (itemToRemove) {
                        itemToRemove.remove();
                        updateProductIndices();
                    }
                }
            }
        });

        updateProductIndices();
    }
});
</script>

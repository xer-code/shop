<div class="fade-in space-y-6">
    <!-- Header Controls -->
    <div class="flex justify-between items-center bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
        <div>
            <h3 class="text-lg font-bold text-white">Customers Management</h3>
            <p class="text-xs text-gray-500">Manage customer credentials, wallets, and account statuses</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="document.getElementById('addCustomerModal').classList.add('active')" class="btn-gold text-xs py-2 px-4 rounded-lg flex items-center gap-2">
                ➕ Add New Customer
            </button>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 font-semibold">Total:</span>
                <span class="px-3 py-1 bg-[#D4A017]/10 border border-[#D4A017]/30 text-[#D4A017] rounded-full text-xs font-bold font-mono">
                    <?= count($customers) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Customers List Table -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Wallet Balance</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500 font-semibold">No customer accounts registered.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $cust): ?>
                        <tr>
                            <td class="font-mono text-xs"><?= $cust['id'] ?></td>
                            <td class="font-semibold text-white"><?= e($cust['name']) ?></td>
                            <td><?= e($cust['email']) ?></td>
                            <td class="font-mono font-bold text-[#D4A017]">$<?= number_format($cust['wallet_balance'], 2) ?></td>
                            <td>
                                <?php if ($cust['is_suspended']): ?>
                                    <span class="px-2 py-0.5 bg-red-950 text-red-500 border border-red-900 rounded text-[10px] font-bold uppercase tracking-wider">Suspended</span>
                                <?php else: ?>
                                    <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[10px] font-bold uppercase tracking-wider">Active</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                                    <a href="<?= url('/admin/customers/manage/' . $cust['id']) ?>" class="px-3.5 py-1.5 bg-[#2a2a2a] hover:bg-[#D4A017] text-white hover:text-black text-xs font-bold rounded-lg transition-all inline-flex items-center gap-1.5 w-fit">
                                        👤 Manage Account
                                    </a>
                                    <form method="POST" action="<?= url('/admin/customers/delete/' . $cust['id']) ?>" onsubmit="return confirm('Are you sure you want to delete this customer? This will permanently delete all order history, payments, tickets, and details associated with this account.')" style="display: inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="px-3.5 py-1.5 bg-red-950 hover:bg-red-600 text-red-500 hover:text-white border border-red-900/30 hover:border-transparent text-xs font-bold rounded-lg transition-all inline-flex items-center gap-1.5 w-fit cursor-pointer" style="outline: none; border: 1px solid rgba(239, 68, 68, 0.2);">
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

<!-- Add Customer Modal -->
<div class="modal-overlay" id="addCustomerModal">
    <div class="modal-content" style="max-width: 440px; padding: 2rem; background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 16px;">
        <button class="modal-close" onclick="document.getElementById('addCustomerModal').classList.remove('active')" style="color: var(--text-muted); font-size: 1.5rem;">&times;</button>
        
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
            <div style="width: 40px; height: 40px; background: rgba(212,160,23,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">👤</div>
            <h2 style="font-weight: 800; font-size: 1.2rem; color: #fff;">Register Customer Account</h2>
        </div>
        
        <form method="POST" action="<?= url('/admin/customers/create') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Customer Full Name</label>
                <input type="text" name="name" required placeholder="e.g. John Doe" class="input-dark">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="e.g. johndoe@example.com" class="input-dark">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Account Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="input-dark">
            </div>

            <button type="submit" class="btn-gold w-full justify-center mt-2">
                👤 Register Customer
            </button>
        </form>
    </div>
</div>

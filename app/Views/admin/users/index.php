<div class="fade-in space-y-6">
    <!-- Header Controls -->
    <div class="flex justify-between items-center bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
        <div>
            <h3 class="text-lg font-bold text-white">System User Management</h3>
            <p class="text-xs text-gray-500">Manage administrator, staff, and custom role accounts</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="document.getElementById('addUserModal').classList.add('active')" class="btn-gold text-xs py-2 px-4 rounded-lg flex items-center gap-2">
                ➕ Add New User
            </button>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 font-semibold">Total:</span>
                <span class="px-3 py-1 bg-[#D4A017]/10 border border-[#D4A017]/30 text-[#D4A017] rounded-full text-xs font-bold font-mono">
                    <?= count($users) ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Users List Table -->
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500 font-semibold">No administrative user accounts found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td class="font-mono text-xs">#<?= $u['id'] ?></td>
                            <td class="font-semibold text-white"><?= e($u['name']) ?></td>
                            <td><?= e($u['email']) ?></td>
                            <td>
                                <form method="POST" action="<?= url('/admin/users/update-role/' . $u['id']) ?>" style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                    <?= csrf_field() ?>
                                    <select name="role" class="select-dark" onchange="this.form.submit()" style="padding: 0.3rem 1.75rem 0.3rem 0.75rem; font-size: 0.8rem; background-position: right 0.5rem center; border-radius: 6px;">
                                        <option value="admin" <?= $u['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        <?php foreach (array_keys($roles) as $rName): ?>
                                            <?php if ($rName !== 'admin'): ?>
                                                <option value="<?= e($rName) ?>" <?= $u['role'] === $rName ? 'selected' : '' ?>><?= e(ucfirst($rName)) ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <option value="customer" <?= $u['role'] === 'customer' ? 'selected' : '' ?>>Customer (Move to Customer list)</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <span class="badge-status <?= $u['is_suspended'] ? 'badge-cancelled' : 'badge-delivered' ?>">
                                    <?= $u['is_suspended'] ? 'Suspended' : 'Active' ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($u['created_at'])) ?></td>
                            <td>
                                <?php if ($u['id'] !== \App\Core\Auth::id()): ?>
                                    <form method="POST" action="<?= url('/admin/users/suspend/' . $u['id']) ?>">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="<?= $u['is_suspended'] ? 'btn-gold' : 'btn-danger' ?> btn-sm" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                            <?= $u['is_suspended'] ? '🔓 Unsuspend' : '🔒 Suspend' ?>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size: 0.8rem; color: var(--text-muted); font-style: italic;">Self (Current User)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal-overlay" id="addUserModal">
    <div class="modal-content" style="max-width: 440px; padding: 2rem; background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 16px;">
        <button class="modal-close" onclick="document.getElementById('addUserModal').classList.remove('active')" style="color: var(--text-muted); font-size: 1.5rem;">&times;</button>
        
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
            <div style="width: 40px; height: 40px; background: rgba(212,160,23,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">👤</div>
            <h2 style="font-weight: 800; font-size: 1.2rem; color: #fff;">Register System User</h2>
        </div>
        
        <form method="POST" action="<?= url('/admin/users/create') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">User Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Staff Member" class="input-dark">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="e.g. staff@shopx.com" class="input-dark">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Account Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="input-dark">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">System Access Role</label>
                <select name="role" required class="select-dark w-full">
                    <option value="admin">Admin</option>
                    <?php foreach (array_keys($roles) as $rName): ?>
                        <?php if ($rName !== 'admin'): ?>
                            <option value="<?= e($rName) ?>"><?= e(ucfirst($rName)) ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-gold w-full justify-center mt-2">
                👤 Register User
            </button>
        </form>
    </div>
</div>

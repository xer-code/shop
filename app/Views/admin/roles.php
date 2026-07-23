<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">System Access Roles</h3>
            <p class="text-xs text-gray-500">Configure role profiles and toggle operational access control rights</p>
        </div>
        <button onclick="document.getElementById('addRoleModal').classList.add('active')" class="btn-gold text-xs py-2 px-4 rounded-lg flex items-center gap-2">
            ➕ Add New Role
        </button>
    </div>

    <!-- Roles Listing -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($roles as $roleName => $perms): ?>
            <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 hover:border-[#D4A017] transition-all space-y-4">
                <div class="flex justify-between items-center border-b border-[#2a2a2a] pb-3">
                    <span class="text-sm font-bold text-white uppercase tracking-wider">🛡️ <?= e($roleName) ?></span>
                    <span class="text-[10px] text-gray-500 font-mono">Profile</span>
                </div>
                
                <div class="space-y-2">
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider block">Assigned Capabilities:</span>
                    <?php 
                    $assignedCount = 0;
                    foreach ($perms as $pName => $enabled): 
                        if ($enabled):
                            $assignedCount++;
                    ?>
                            <div class="flex items-center gap-2 text-xs text-green-500">
                                <span>✓</span>
                                <span class="font-mono"><?= e(str_replace('_', ' ', $pName)) ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if ($assignedCount === 0): ?>
                        <p class="text-xs text-gray-600 italic">No operational access granted.</p>
                    <?php endif; ?>
                </div>

                <div class="pt-4 border-t border-[#2a2a2a] flex items-center justify-between gap-2">
                    <a href="<?= url('/admin/permissions') ?>" class="btn-outline w-full text-center text-xs py-2 justify-center" style="font-weight: 700;">
                        ⚙️ Edit Permissions
                    </a>
                    <?php if ($roleName !== 'admin'): ?>
                        <form action="<?= url('/admin/roles/delete/' . $roleName) ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this role? Any user assigned to this role will lose their permission context.')" style="display: inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="px-3 py-2 bg-red-950 hover:bg-red-600 text-red-500 hover:text-white border border-red-900/30 hover:border-transparent text-xs font-bold rounded-lg transition-all cursor-pointer">
                                🗑️ Delete
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal-overlay" id="addRoleModal">
    <div class="modal-content" style="max-width: 440px; padding: 2rem; background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 16px;">
        <button class="modal-close" onclick="document.getElementById('addRoleModal').classList.remove('active')" style="color: var(--text-muted); font-size: 1.5rem;">&times;</button>
        
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
            <div style="width: 40px; height: 40px; background: rgba(212,160,23,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">🛡️</div>
            <h2 style="font-weight: 800; font-size: 1.2rem; color: #fff;">Create Access Role</h2>
        </div>
        
        <form method="POST" action="<?= url('/admin/roles/create') ?>" class="space-y-4">
            <?= csrf_field() ?>
            
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Role Name</label>
                <input type="text" name="name" required placeholder="e.g. Content Moderator" class="input-dark">
                <p class="text-[10px] text-gray-500 mt-1">This will be converted into a system identifier key (e.g. "content_moderator").</p>
            </div>
            
            <button type="submit" class="btn-gold w-full justify-center mt-2">
                🛡️ Create Role
            </button>
        </form>
    </div>
</div>

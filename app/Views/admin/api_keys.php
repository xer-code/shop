<div class="fade-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Generate Key Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit">
            <h3 class="text-base font-bold text-white mb-1">Generate API Key</h3>
            <p class="text-xs text-gray-500 mb-6">Create credentials for external developer integration endpoints</p>
            
            <form action="<?= url('/admin/api-keys/create') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Integration Name</label>
                    <input type="text" name="name" required placeholder="e.g. QuickBooks Accounting" class="input-dark">
                </div>
                <button type="submit" class="btn-gold w-full justify-center">
                    🔌 Generate Token Credentials
                </button>
            </form>
        </div>

        <!-- API Keys List -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden lg:col-span-2">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Integration Name</th>
                        <th>Secret Access Key (Live Token)</th>
                        <th>Status</th>
                        <th>Toggle status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($keys)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500 font-semibold">No external integration keys generated.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($keys as $k): ?>
                            <tr>
                                <td class="font-mono text-xs"><?= $k['id'] ?></td>
                                <td class="font-semibold text-white"><?= e($k['name']) ?></td>
                                <td class="font-mono text-xs">
                                    <code><?= e($k['token']) ?></code>
                                </td>
                                <td>
                                    <?php if ($k['status'] === 'Active'): ?>
                                        <span class="px-2 py-0.5 bg-green-950 text-green-500 border border-green-900 rounded text-[10px] font-bold uppercase tracking-wider">Active</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 bg-red-950 text-red-500 border border-red-900 rounded text-[10px] font-bold uppercase tracking-wider">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <form action="<?= url('/admin/api-keys/toggle/' . $k['id']) ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="px-2 py-1 bg-[#111] hover:bg-[#252525] border border-[#2a2a2a] text-xs font-bold text-gray-300 rounded transition-colors">
                                            <?= $k['status'] === 'Active' ? '⏸ Deactivate' : '▶ Activate' ?>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?= url('/admin/api-keys/delete/' . $k['id']) ?>" method="POST" onsubmit="return confirm('Revoke and delete this API token permanently? This action cannot be undone.');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-500 hover:text-red-400 font-bold text-xs">
                                            ❌ Revoke
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

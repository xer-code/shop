<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6">
        <h3 class="text-lg font-bold text-white mb-1">Global System Configurations</h3>
        <p class="text-xs text-gray-500 mb-6">Modify international settings, currency displays, and server parameters</p>
        
        <form action="<?= url('/admin/settings/update') ?>" method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-6">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Marketplace App Name</label>
                    <input type="text" name="app_name" value="<?= e($settings['app_name']) ?>" required class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Default Currency</label>
                    <select name="currency" class="select-dark w-full">
                        <option value="USD" <?= $settings['currency'] === 'USD' ? 'selected' : '' ?>>United States Dollar ($)</option>
                        <option value="EUR" <?= $settings['currency'] === 'EUR' ? 'selected' : '' ?>>Euro (€)</option>
                        <option value="JPY" <?= $settings['currency'] === 'JPY' ? 'selected' : '' ?>>Japanese Yen (¥)</option>
                        <option value="GBP" <?= $settings['currency'] === 'GBP' ? 'selected' : '' ?>>British Pound (£)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">System Support Email</label>
                    <input type="email" name="support_email" value="<?= e($settings['support_email']) ?>" required class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Base Sales Tax Rate (%)</label>
                    <input type="number" name="tax_rate" step="0.1" value="<?= e($settings['tax_rate']) ?>" required class="input-dark">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Shipment Tracking Prefix</label>
                    <input type="text" name="tracking_prefix" value="<?= e($settings['tracking_prefix'] ?? 'SX') ?>" required class="input-dark" placeholder="e.g. SX">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-[#2a2a2a] pt-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Marketplace Logo</label>
                    <div class="flex items-center gap-4">
                        <?php if (!empty($settings['logo_path'])): ?>
                            <img src="<?= url('/' . $settings['logo_path']) ?>" alt="Logo" style="height: 36px; object-fit: contain;" class="bg-[#111] p-1 border border-[#2a2a2a] rounded">
                        <?php endif; ?>
                        <input type="file" name="logo" class="text-xs text-gray-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#D4A017] hover:file:text-black cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Browser Favicon</label>
                    <div class="flex items-center gap-4">
                        <?php if (!empty($settings['favicon_path'])): ?>
                            <img src="<?= url('/' . $settings['favicon_path']) ?>" alt="Favicon" style="height: 32px; width: 32px; object-fit: contain;" class="bg-[#111] p-1 border border-[#2a2a2a] rounded">
                        <?php endif; ?>
                        <input type="file" name="favicon" class="text-xs text-gray-400 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#2a2a2a] file:text-white hover:file:bg-[#D4A017] hover:file:text-black cursor-pointer">
                    </div>
                </div>
            </div>

                </div>
            </div>

            <div class="border-t border-[#2a2a2a] pt-6">
                <h4 class="text-sm font-bold text-white mb-4">Real-Time Chat Configuration (Pusher)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pusher App ID</label>
                        <input type="text" name="pusher_app_id" value="<?= e($settings['pusher_app_id'] ?? '') ?>" class="input-dark" placeholder="e.g. 1234567">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pusher Key</label>
                        <input type="text" name="pusher_key" value="<?= e($settings['pusher_key'] ?? '') ?>" class="input-dark" placeholder="e.g. abcd1234abcd">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pusher Secret</label>
                        <input type="password" name="pusher_secret" value="<?= e($settings['pusher_secret'] ?? '') ?>" class="input-dark" placeholder="e.g. 987654321">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pusher Cluster</label>
                        <input type="text" name="pusher_cluster" value="<?= e($settings['pusher_cluster'] ?? 'mt1') ?>" class="input-dark" placeholder="e.g. mt1">
                    </div>
                </div>
            </div>

            <div class="border-t border-[#2a2a2a] pt-6">
                <div class="flex items-center justify-between p-4 bg-[#111] border border-[#2a2a2a] rounded-lg">
                    <div>
                        <div class="text-sm font-bold text-white mb-1">Activate Maintenance Mode</div>
                        <p class="text-xs text-gray-500">Block public access to storefront while updates are running</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" <?= $settings['maintenance_mode'] ? 'checked' : '' ?> class="sr-only peer">
                        <div class="w-11 h-6 bg-[#2a2a2a] peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-gray-300 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#D4A017]"></div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn-gold py-2.5 px-6">
                💾 Save Configuration Updates
            </button>
        </form>
    </div>
</div>

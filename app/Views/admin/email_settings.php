<div class="space-y-8">
    <!-- Header banner -->
    <div class="bg-[var(--surface)] border border-[var(--border-dark)] rounded-xl p-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-96 h-96 bg-gold/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
        <div class="flex flex-wrap justify-between items-center gap-4 relative z-10">
            <div>
                <h2 class="text-2xl font-black text-white flex items-center gap-2">
                    <span>📧 System Email Configuration</span>
                </h2>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    Manage system SMTP/mail transport settings, edit email notification templates, and run real-time test dispatches.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider <?= !empty($settings['enabled']) ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' ?>">
                    <?= !empty($settings['enabled']) ? '● System Email Active' : '○ System Email Disabled' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Layout grid: Settings + Test Email -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- SMTP & Mailer Config Form -->
        <div class="lg:col-span-2 bg-[var(--surface)] border border-[var(--border-dark)] rounded-xl p-6 shadow-xl">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2 border-b border-[var(--border-dark)] pb-3">
                <span>⚙️ Transport & Server Settings</span>
            </h3>

            <form action="<?= url('/admin/email-settings/update') ?>" method="POST" class="space-y-5">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">System Email Status</label>
                        <select name="enabled" class="input-dark w-full" style="background:#111; color:#fff; padding:0.6rem 0.8rem; border-radius:6px; border:1px solid var(--border-dark);">
                            <option value="1" <?= !empty($settings['enabled']) ? 'selected' : '' ?>>✅ Enabled (Send Emails)</option>
                            <option value="0" <?= empty($settings['enabled']) ? 'selected' : '' ?>>⛔ Disabled (Pause All Emails)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mail Driver</label>
                        <select name="driver" id="mail_driver_select" class="input-dark w-full" style="background:#111; color:#fff; padding:0.6rem 0.8rem; border-radius:6px; border:1px solid var(--border-dark);">
                            <option value="smtp" <?= ($settings['driver'] ?? 'smtp') === 'smtp' ? 'selected' : '' ?>>🔌 SMTP Transport (Recommended)</option>
                            <option value="mail" <?= ($settings['driver'] ?? 'smtp') === 'mail' ? 'selected' : '' ?>>📨 PHP Native mail() Function</option>
                        </select>
                    </div>
                </div>

                <!-- Sender Config -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">System From Email</label>
                        <input type="email" name="from_email" value="<?= e($settings['from_email'] ?? 'noreply@shopxglobal.com') ?>" required class="input-dark w-full" placeholder="noreply@yourdomain.com">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sender Display Name</label>
                        <input type="text" name="from_name" value="<?= e($settings['from_name'] ?? 'ShopX Global Marketplace') ?>" required class="input-dark w-full" placeholder="ShopX Global System">
                    </div>
                </div>

                <!-- SMTP Specific Credentials -->
                <div id="smtp_fields" class="space-y-4 pt-4 border-t border-[var(--border-dark)]" style="display: <?= ($settings['driver'] ?? 'smtp') === 'smtp' ? 'block' : 'none' ?>;">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Host</label>
                            <input type="text" name="smtp_host" value="<?= e($settings['smtp_host'] ?? '') ?>" class="input-dark w-full" placeholder="e.g. smtp.gmail.com or smtp.mailtrap.io">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Port</label>
                            <input type="number" name="smtp_port" value="<?= e((string)($settings['smtp_port'] ?? 587)) ?>" class="input-dark w-full" placeholder="587">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Encryption Protocol</label>
                            <select name="smtp_encryption" class="input-dark w-full" style="background:#111; color:#fff; padding:0.6rem 0.8rem; border-radius:6px; border:1px solid var(--border-dark);">
                                <option value="tls" <?= ($settings['smtp_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS (Port 587)</option>
                                <option value="ssl" <?= ($settings['smtp_encryption'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL (Port 465)</option>
                                <option value="none" <?= ($settings['smtp_encryption'] ?? 'tls') === 'none' ? 'selected' : '' ?>>None (Port 25)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Username</label>
                            <input type="text" name="smtp_username" value="<?= e($settings['smtp_username'] ?? '') ?>" class="input-dark w-full" placeholder="Username or API Key">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">SMTP Password</label>
                            <input type="password" name="smtp_password" value="<?= e($settings['smtp_password'] ?? '') ?>" class="input-dark w-full" placeholder="Password or Token">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-[var(--gold-primary)] hover:bg-[var(--gold-light)] text-black font-bold px-6 py-2.5 rounded-lg transition-colors">
                        💾 Save Email Configuration
                    </button>
                </div>
            </form>
        </div>

        <!-- Test Email Card -->
        <div class="bg-[var(--surface)] border border-[var(--border-dark)] rounded-xl p-6 shadow-xl flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2 border-b border-[var(--border-dark)] pb-3">
                    <span>🧪 Dispatch Test Email</span>
                </h3>
                <p class="text-xs text-[var(--text-muted)] mb-4">
                    Send a test email to verify your SMTP server connections and driver settings instantly.
                </p>

                <form action="<?= url('/admin/email-settings/test') ?>" method="POST" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Recipient Email Address</label>
                        <input type="email" name="test_email" required value="<?= e(\App\Core\Auth::email() ?? '') ?>" class="input-dark w-full" placeholder="admin@example.com">
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <span>✈️ Send Test Email Now</span>
                    </button>
                </form>
            </div>

            <div class="mt-6 p-3 bg-black/40 border border-[var(--border-dark)] rounded-lg">
                <div class="text-[11px] text-gray-400 font-mono">
                    <div class="text-[var(--gold-primary)] font-bold mb-1">💡 Quick Diagnostic Tip</div>
                    If using Gmail, generate an <strong>App Password</strong> in your Google Account security settings. If testing locally without internet SMTP, select <strong>PHP Native mail()</strong>.
                </div>
            </div>
        </div>
    </div>

    <!-- Email Templates Management -->
    <div class="bg-[var(--surface)] border border-[var(--border-dark)] rounded-xl p-6 shadow-xl">
        <div class="flex flex-wrap justify-between items-center gap-4 mb-6 border-b border-[var(--border-dark)] pb-4">
            <div>
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span>📝 System Email Notification Templates</span>
                </h3>
                <p class="text-xs text-[var(--text-muted)] mt-1">
                    Customize subject lines and HTML template bodies sent on user creation, orders, deposits, gift cards, product tracking, and shipments.
                </p>
            </div>
        </div>

        <form action="<?= url('/admin/email-settings/templates/update') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Template Selector Tabs -->
            <div class="flex flex-wrap gap-2 mb-6 border-b border-[var(--border-dark)] pb-3" id="templateTabs">
                <?php 
                $first = true;
                foreach ($templates as $key => $tmpl): 
                ?>
                    <button type="button" 
                            onclick="switchTemplate('<?= $key ?>')" 
                            id="tab_btn_<?= $key ?>" 
                            class="tmpl-tab-btn px-4 py-2 text-xs font-bold rounded-lg transition-all border <?= $first ? 'bg-[var(--gold-primary)] text-black border-[var(--gold-primary)]' : 'bg-[#151515] text-gray-400 border-[var(--border-dark)] hover:text-white' ?>">
                        <?= e($tmpl['title'] ?? ucfirst($key)) ?>
                    </button>
                <?php 
                $first = false;
                endforeach; 
                ?>
            </div>

            <!-- Template Editors -->
            <?php 
            $first = true;
            foreach ($templates as $key => $tmpl): 
            ?>
                <div id="template_panel_<?= $key ?>" class="tmpl-panel space-y-4" style="display: <?= $first ? 'block' : 'none' ?>;">
                    <div class="bg-[#111] p-3 border border-[var(--border-dark)] rounded-lg flex items-center justify-between">
                        <span class="text-xs font-bold text-[var(--gold-primary)] uppercase tracking-wider">Trigger Event Key: <code class="text-white"><?= e($key) ?></code></span>
                        <span class="text-[11px] text-gray-400">Available Placeholders for this template:</span>
                    </div>

                    <!-- Variable badges reference -->
                    <div class="flex flex-wrap gap-1.5 text-[11px] font-mono mb-2">
                        <?php if ($key === 'user_welcome'): ?>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{email}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{app_name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{app_url}</span>
                        <?php elseif ($key === 'order_confirmation'): ?>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{order_id}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{total}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{tracking_code}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{shipping_address}</span>
                        <?php elseif ($key === 'deposit_confirmation'): ?>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{amount}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{gateway_name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{wallet_balance}</span>
                        <?php elseif ($key === 'gift_card_purchased'): ?>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{gift_card_code}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{amount}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{name_on_card}</span>
                        <?php elseif ($key === 'gift_card_redeemed'): ?>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{gift_card_code}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{amount}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{new_wallet_balance}</span>
                        <?php elseif ($key === 'product_tracking_updated'): ?>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{tracking_code}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{status}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{location}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{description}</span>
                        <?php elseif ($key === 'shipment_created'): ?>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{receiver_name}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{tracking_code}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{carrier}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{origin}</span>
                            <span class="px-2 py-0.5 bg-gold/10 text-gold rounded border border-gold/20">{destination}</span>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Subject Line</label>
                        <input type="text" name="templates[<?= $key ?>][subject]" value="<?= e($tmpl['subject'] ?? '') ?>" required class="input-dark w-full">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">HTML Content Template</label>
                        <textarea name="templates[<?= $key ?>][content]" rows="10" required class="input-dark w-full font-mono text-xs" style="line-height:1.5;"><?= e($tmpl['content'] ?? '') ?></textarea>
                    </div>
                </div>
            <?php 
            $first = false;
            endforeach; 
            ?>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-[var(--gold-primary)] hover:bg-[var(--gold-light)] text-black font-bold px-6 py-2.5 rounded-lg transition-colors">
                    💾 Save All Template Edits
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Email Logs -->
    <?php if (!empty($logs)): ?>
    <div class="bg-[var(--surface)] border border-[var(--border-dark)] rounded-xl p-6 shadow-xl">
        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2 border-b border-[var(--border-dark)] pb-3">
            <span>📜 Recent Email Dispatch Logs</span>
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-[var(--border-dark)] text-gray-400 uppercase tracking-wider">
                        <th class="py-2.5 px-3">Timestamp</th>
                        <th class="py-2.5 px-3">Trigger Event</th>
                        <th class="py-2.5 px-3">Recipient</th>
                        <th class="py-2.5 px-3">Subject</th>
                        <th class="py-2.5 px-3">Status</th>
                        <th class="py-2.5 px-3">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-dark)] font-mono">
                    <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-2.5 px-3 text-gray-400 whitespace-nowrap"><?= e($log['timestamp']) ?></td>
                            <td class="py-2.5 px-3 text-[var(--gold-primary)] font-bold"><?= e($log['trigger']) ?></td>
                            <td class="py-2.5 px-3 text-white"><?= e($log['recipient']) ?></td>
                            <td class="py-2.5 px-3 text-gray-300"><?= e($log['subject']) ?></td>
                            <td class="py-2.5 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase <?= $log['status'] === 'Sent' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' ?>">
                                    <?= e($log['status']) ?>
                                </span>
                            </td>
                            <td class="py-2.5 px-3 text-gray-400 text-[11px] truncate max-w-xs"><?= e($log['details']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const driverSelect = document.getElementById('mail_driver_select');
    const smtpFields = document.getElementById('smtp_fields');

    if (driverSelect && smtpFields) {
        driverSelect.addEventListener('change', function() {
            smtpFields.style.display = this.value === 'smtp' ? 'block' : 'none';
        });
    }
});

function switchTemplate(key) {
    document.querySelectorAll('.tmpl-panel').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tmpl-tab-btn').forEach(btn => {
        btn.classList.remove('bg-[var(--gold-primary)]', 'text-black', 'border-[var(--gold-primary)]');
        btn.classList.add('bg-[#151515]', 'text-gray-400', 'border-[var(--border-dark)]');
    });

    const activePanel = document.getElementById('template_panel_' + key);
    const activeBtn = document.getElementById('tab_btn_' + key);

    if (activePanel) activePanel.style.display = 'block';
    if (activeBtn) {
        activeBtn.classList.remove('bg-[#151515]', 'text-gray-400', 'border-[var(--border-dark)]');
        activeBtn.classList.add('bg-[var(--gold-primary)]', 'text-black', 'border-[var(--gold-primary)]');
    }
}
</script>

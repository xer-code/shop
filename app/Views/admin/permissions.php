<div class="fade-in space-y-6">
    <form action="<?= url('/admin/permissions/update') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-white">Capabilities Authorization Matrix</h3>
                <p class="text-xs text-gray-500">Configure side menu features visibility permissions mapped against system roles</p>
            </div>
            <div class="flex gap-2">
                <a href="<?= url('/admin/roles') ?>" class="btn-outline text-xs py-1.5 px-3">
                    🛡️ Manage Roles
                </a>
                <button type="submit" class="btn-gold text-xs py-1.5 px-3">
                    ✓ Save Capabilities Matrix
                </button>
            </div>
        </div>

        <?php
        $features = [
            'analytics' => ['name' => '📈 View Analytics', 'description' => 'Access system profit logs, charts, and warehouse capacities'],
            'reports' => ['name' => '📋 View Reports', 'description' => 'View transaction logs, inventory tallies, and performance summaries'],
            'notifications' => ['name' => '🔔 Send Notifications', 'description' => 'Publish announcements and flash notifications to users'],
            'products' => ['name' => '📦 Manage Products', 'description' => 'Add, update, activate, and delete catalog items'],
            'categories' => ['name' => '🏷️ Manage Categories', 'description' => 'Manage category sections, slugs, and custom icons'],
            'orders' => ['name' => '🛍️ Manage Orders', 'description' => 'View, update fulfillment status, and cancel customer orders'],
            'gift_cards' => ['name' => '🎁 Manage Gift Cards', 'description' => 'Issue, search, void, and track active/redeemed gift cards'],
            'invoices' => ['name' => '🧾 View Invoices', 'description' => 'Access, read, and print customer sales invoices'],
            'quotes' => ['name' => '📝 Manage Bulk Quotes', 'description' => 'Review, approve, or decline custom order price quotes'],
            'warehouses' => ['name' => '🏢 Manage Warehouses', 'description' => 'Track stock distributions and register new storage warehouses'],
            'shipments' => ['name' => '🚢 Manage Shipments', 'description' => 'Assign shipping carriers, origins, and destinations'],
            'tracking' => ['name' => '🚚 Live Tracking updates', 'description' => 'Post custom cargo updates and transit locations logs'],
            'customers' => ['name' => '👥 Manage Customers', 'description' => 'Edit customer credentials, status toggles, and view profiles'],
            'suppliers' => ['name' => '🏭 Manage Suppliers', 'description' => 'Register and oversee third-party wholesale supply contacts'],
            'payments' => ['name' => '💳 Ledger Payments', 'description' => 'Monitor customer deposits, purchases, and refunds history'],
            'deposits' => ['name' => '📥 Deposit Requests', 'description' => 'Approve or reject customer wallet top-up payment requests'],
            'support' => ['name' => '🎫 Support Desk', 'description' => 'Review inbound support tickets, close tickets, and submit replies'],
            'chat' => ['name' => '💬 Live Chat', 'description' => 'Engage in real-time communication with connected online customers'],
            'settings' => ['name' => '🔧 Global Settings', 'description' => 'Change site name, support contacts, tax margins, and currency'],
            'users' => ['name' => '👥 Admins & Users', 'description' => 'Create and modify administrator and manager access logins'],
            'roles' => ['name' => '🛡️ Access Roles', 'description' => 'Define system access roles, profiles, and clean up role list'],
            'permissions' => ['name' => '🔑 Permissions Matrix', 'description' => 'View and configure interactive capabilities for each role'],
            'api_keys' => ['name' => '🔌 API Keys', 'description' => 'Generate and manage keys for third-party developer platforms'],
            'gateways' => ['name' => '💳 Payment Gateways', 'description' => 'Configure active payment methods, rules, fees, and wallets address'],
            'audit_logs' => ['name' => '📝 Audit Logs', 'description' => 'Read detailed records of administrator/staff system actions'],
            'promotions' => ['name' => '🔥 Promotions Event', 'description' => 'Create and delete hot discount campaign banner cards'],
            'coupons' => ['name' => '🎟️ Coupons Registry', 'description' => 'Register and configure dynamic checkout percentage promo codes']
        ];
        ?>

        <!-- Permissions Matrix Table -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="min-width: 250px;">Sidebar Feature Section</th>
                        <th style="min-width: 150px;">Required Tag</th>
                        <?php foreach (array_keys($roles) as $roleName): ?>
                            <th class="text-center uppercase tracking-wider text-[10px]" style="min-width: 100px;">
                                🛡️ <?= e($roleName) ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($features as $fKey => $fData): ?>
                        <tr>
                            <td>
                                <div class="font-bold text-white"><?= e($fData['name']) ?></div>
                                <div class="text-[10px] text-gray-500 mt-0.5"><?= e($fData['description']) ?></div>
                            </td>
                            <td class="font-mono text-xs text-gray-500"><?= e($fKey) ?></td>
                            <?php foreach ($roles as $roleName => $perms): ?>
                                <td class="text-center">
                                    <?php if ($roleName === 'admin'): ?>
                                        <div class="flex items-center justify-center">
                                            <input type="checkbox" checked disabled class="rounded border-[#D4A017] text-[#D4A017] focus:ring-0 w-4 h-4 bg-[#111]">
                                        </div>
                                    <?php else: ?>
                                        <?php $enabled = !empty($perms[$fKey]); ?>
                                        <div class="flex items-center justify-center">
                                            <input type="checkbox" name="permissions[<?= e($roleName) ?>][<?= e($fKey) ?>]" value="1" <?= $enabled ? 'checked' : '' ?> class="rounded border-gray-700 text-[#D4A017] focus:ring-0 w-4 h-4 bg-[#111] cursor-pointer">
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="flex justify-end">
            <button type="submit" class="btn-gold text-xs py-2.5 px-6 rounded-lg font-bold">
                ✓ Save Capabilities Matrix
            </button>
        </div>
    </form>
</div>

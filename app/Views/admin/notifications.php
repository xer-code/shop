<div class="fade-in space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Broadcast Form -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 h-fit lg:col-span-1">
            <h3 class="text-base font-bold text-white mb-1">Direct Alerts System</h3>
            <p class="text-xs text-gray-500 mb-6">Send messages directly to user dashboards</p>
            
            <form action="<?= url('/admin/notifications/send') ?>" method="POST" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Target Segment</label>
                    <select name="target" class="select-dark w-full">
                        <option value="all">Broadcast (All Customers)</option>
                        <option value="vip">VIP Accounts Only (Balance > $500)</option>
                        <option value="new">New Users (Last 7 Days)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Alert Message</label>
                    <textarea name="message" rows="4" required placeholder="Type the notification alert body..." class="input-dark resize-none"></textarea>
                </div>
                <button type="submit" class="btn-gold w-full justify-center">
                    🔔 Broadcast Notification
                </button>
            </form>
        </div>

        <!-- Recent Broadcast Logs -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 lg:col-span-2 space-y-4">
            <h3 class="text-base font-bold text-white">Broadcast logs</h3>
            <p class="text-xs text-gray-500 mb-4">Chronological log of alerts dispatched to users</p>

            <div class="space-y-3">
                <div class="p-4 bg-[#111] border border-[#2a2a2a] rounded-lg relative">
                    <span class="absolute top-4 right-4 text-[10px] bg-blue-950 text-blue-500 border border-blue-900 px-2 py-0.5 rounded font-bold uppercase font-mono">Broadcast</span>
                    <div class="text-xs text-gray-500 font-mono">2026-07-19 19:35</div>
                    <div class="text-sm text-white font-semibold mt-1">Maintenance Notice</div>
                    <p class="text-xs text-gray-400 mt-1">Scheduled database optimization starting on 2026-07-20 at 02:00 UTC. The marketplace will remain online.</p>
                </div>
                
                <div class="p-4 bg-[#111] border border-[#2a2a2a] rounded-lg relative">
                    <span class="absolute top-4 right-4 text-[10px] bg-purple-950 text-purple-400 border border-purple-900 px-2 py-0.5 rounded font-bold uppercase font-mono">VIP Segment</span>
                    <div class="text-xs text-gray-500 font-mono">2026-07-18 12:10</div>
                    <div class="text-sm text-white font-semibold mt-1">Private VIP Concierge Launch</div>
                    <p class="text-xs text-gray-400 mt-1">We have released a new private support chat option for premium customers. Check your wallet balance profile page to connect.</p>
                </div>
            </div>
        </div>
    </div>
</div>

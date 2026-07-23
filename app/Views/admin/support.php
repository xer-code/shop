<div class="fade-in space-y-6">
    <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-white">Support Ticket Desk</h3>
            <p class="text-xs text-gray-500">Read and respond to incoming customer support tickets</p>
        </div>
    </div>

    <!-- Ticket List & Messages Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Tickets list -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl overflow-hidden lg:col-span-1">
            <div class="bg-[#111] p-4 border-b border-[#2a2a2a] text-xs font-bold text-white uppercase tracking-wider">Active Tickets</div>
            <div class="divide-y divide-[#2a2a2a]">
                <?php foreach ($tickets as $t): ?>
                    <div class="p-4 hover:bg-[#252525]/30 transition-all space-y-2">
                        <div class="flex justify-between items-start">
                            <span class="font-bold text-white">Ticket #<?= $t['id'] ?></span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider <?= $t['priority'] === 'High' ? 'bg-red-950 text-red-500 border border-red-900' : 'bg-yellow-950 text-yellow-500 border border-yellow-900' ?>">
                                <?= $t['priority'] ?>
                            </span>
                        </div>
                        <div class="text-xs text-white font-medium truncate"><?= e($t['subject']) ?></div>
                        <div class="flex justify-between items-center text-[10px] text-gray-500">
                            <span>Client: <?= e($t['customer']) ?></span>
                            <span><?= e($t['date']) ?></span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider <?= $t['status'] === 'Closed' ? 'bg-gray-950 text-gray-500 border border-gray-900' : ($t['status'] === 'Resolved' ? 'bg-green-950 text-green-500 border border-green-900' : 'bg-blue-950 text-blue-500 border border-blue-900') ?>">
                                <?= $t['status'] ?>
                            </span>
                            <?php if ($t['status'] !== 'Closed'): ?>
                                <form action="<?= url('/admin/support/close/' . $t['id']) ?>" method="POST" class="inline">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-400 font-bold">✕ Close</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Chat responses panel -->
        <div class="bg-[#1a1a1a] border border-[#2a2a2a] rounded-xl p-6 lg:col-span-2 space-y-6 flex flex-col justify-between">
            <div>
                <h4 class="text-base font-bold text-white mb-4 border-b border-[#2a2a2a] pb-3">Response Console</h4>
                
                <div class="space-y-4 max-h-[350px] overflow-y-auto pr-2">
                    <?php foreach ($tickets as $t): ?>
                        <?php if ($t['status'] !== 'Closed' && $t['status'] !== 'Resolved'): ?>
                            <div class="border-b border-[#2a2a2a] pb-4 mb-4">
                                <div class="bg-[#111] p-3 rounded-lg border border-[#2a2a2a] mb-3">
                                    <span class="text-xs text-[#D4A017] font-bold">Subject: <?= e($t['subject']) ?></span>
                                </div>
                                <?php foreach ($t['messages'] as $m): ?>
                                    <div class="flex flex-col mb-3 <?= $m['sender'] === 'admin' ? 'items-end' : 'items-start' ?>">
                                        <div class="px-3.5 py-2 rounded-xl text-xs max-w-md <?= $m['sender'] === 'admin' ? 'bg-[#D4A017] text-black font-semibold rounded-tr-none' : 'bg-[#222] text-gray-300 rounded-tl-none border border-[#2a2a2a]' ?>">
                                            <?= e($m['text']) ?>
                                        </div>
                                        <span class="text-[9px] text-gray-500 font-mono mt-1 px-1"><?= $m['sender'] === 'admin' ? 'Admin' : e($t['customer']) ?> • <?= $m['time'] ?></span>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Reply Form -->
                                <form action="<?= url('/admin/support/reply/' . $t['id']) ?>" method="POST" class="mt-4 flex gap-2">
                                    <?= csrf_field() ?>
                                    <input type="text" name="reply" required placeholder="Type reply message..." class="input-dark flex-1">
                                    <button type="submit" class="px-4 py-2 bg-[#D4A017] hover:bg-[#E8C158] text-black font-bold text-xs rounded transition-colors">
                                        ✉ Reply
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

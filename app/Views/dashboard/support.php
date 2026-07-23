<div class="fade-in space-y-6">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">🎫 Support Tickets</h2>
        <button onclick="document.getElementById('ticketModal').classList.add('active')" class="btn-gold" style="font-size: 0.75rem; padding: 0.4rem 1rem; border-radius: 8px;">+ New Ticket</button>
    </div>

    <?php if (empty($tickets)): ?>
        <div style="text-align: center; padding: 3rem; background: #151515; border: 1px solid #1a1a1a; border-radius: 12px;">
            <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">🎫</p>
            <p style="color: #555; font-size: 0.85rem;">No support tickets. Need help? Create one above.</p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <?php foreach (array_reverse($tickets) as $ticket): ?>
                <?php
                    $sColors = ['Open'=>'#eab308','In Progress'=>'#3b82f6','Resolved'=>'#10b981','Closed'=>'#555'];
                    $sc = $sColors[$ticket['status']] ?? '#888';
                    $pColors = ['Low'=>'#10b981','Medium'=>'#eab308','High'=>'#ef4444','Critical'=>'#dc2626'];
                    $pc = $pColors[$ticket['priority']] ?? '#888';
                ?>
                <details style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; overflow: hidden;">
                    <summary style="padding: 1rem 1.25rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; list-style: none;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 0.85rem; font-weight: 700; color: #fff;"><?= e($ticket['subject']) ?></span>
                            <span style="font-size: 0.55rem; font-weight: 700; text-transform: uppercase; color: <?= $pc ?>; background: <?= $pc ?>12; border: 1px solid <?= $pc ?>25; padding: 1px 6px; border-radius: 4px;"><?= $ticket['priority'] ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase; color: <?= $sc ?>;"><?= $ticket['status'] ?></span>
                            <span style="font-size: 0.65rem; color: #555;"><?= timeAgo($ticket['created_at']) ?></span>
                        </div>
                    </summary>
                    <div style="padding: 0 1.25rem 1.25rem; border-top: 1px solid #1a1a1a;">
                        <!-- Messages -->
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1rem;">
                            <?php foreach ($ticket['messages'] as $msg): ?>
                                <div style="padding: 0.6rem 0.75rem; background: <?= $msg['from'] === 'customer' ? '#111' : '#1a150a' ?>; border-radius: 8px; border-left: 2px solid <?= $msg['from'] === 'customer' ? '#3b82f6' : '#D4A017' ?>;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                                        <span style="font-size: 0.65rem; font-weight: 700; color: <?= $msg['from'] === 'customer' ? '#3b82f6' : '#D4A017' ?>;"><?= $msg['from'] === 'customer' ? 'You' : 'Support Agent' ?></span>
                                        <span style="font-size: 0.6rem; color: #555;"><?= timeAgo($msg['time']) ?></span>
                                    </div>
                                    <p style="font-size: 0.75rem; color: #ccc; line-height: 1.5;"><?= e($msg['text']) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Reply form -->
                        <?php if ($ticket['status'] !== 'Closed'): ?>
                            <form method="POST" action="<?= url('/dashboard/support/reply/' . $ticket['id']) ?>" style="display: flex; gap: 0.5rem; margin-top: 0.75rem;">
                                <?= csrf_field() ?>
                                <input type="text" name="message" required placeholder="Type a reply..." class="input-dark" style="flex: 1; font-size: 0.8rem;">
                                <button type="submit" class="btn-gold" style="padding: 0.4rem 1rem; font-size: 0.75rem; border-radius: 8px; white-space: nowrap;">Send</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- New Ticket Modal -->
<div class="modal-overlay" id="ticketModal">
    <div class="modal-content" style="max-width: 460px; padding: 2rem; background: #151515; border: 1px solid #2a2a2a; border-radius: 16px;">
        <button class="modal-close" onclick="document.getElementById('ticketModal').classList.remove('active')" style="color: #888; font-size: 1.5rem;">&times;</button>
        <h2 style="font-weight: 800; font-size: 1.1rem; color: #fff; margin-bottom: 1.25rem;">🎫 Open Support Ticket</h2>
        <form method="POST" action="<?= url('/dashboard/support/create') ?>" style="display: flex; flex-direction: column; gap: 1rem;">
            <?= csrf_field() ?>
            <div>
                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #666; text-transform: uppercase; margin-bottom: 0.4rem;">Subject</label>
                <input type="text" name="subject" required placeholder="e.g. Order not received" class="input-dark" style="width: 100%;">
            </div>
            <div>
                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #666; text-transform: uppercase; margin-bottom: 0.4rem;">Priority</label>
                <select name="priority" class="input-dark" style="width: 100%;">
                    <option value="Low">Low</option>
                    <option value="Medium" selected>Medium</option>
                    <option value="High">High</option>
                    <option value="Critical">Critical</option>
                </select>
            </div>
            <div>
                <label style="display: block; font-size: 0.7rem; font-weight: 700; color: #666; text-transform: uppercase; margin-bottom: 0.4rem;">Describe your issue</label>
                <textarea name="message" rows="4" required placeholder="Please describe the issue in detail..." class="input-dark" style="width: 100%; resize: vertical;"></textarea>
            </div>
            <button type="submit" class="btn-gold" style="width: 100%; justify-content: center; padding: 0.6rem; border-radius: 8px; font-size: 0.85rem;">Submit Ticket</button>
        </form>
    </div>
</div>

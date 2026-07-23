<div class="fade-in space-y-6">
    <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">💬 Messages</h2>

    <?php if (empty($messages)): ?>
        <div style="text-align: center; padding: 3rem; background: #151515; border: 1px solid #1a1a1a; border-radius: 12px;">
            <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">💬</p>
            <p style="color: #555; font-size: 0.85rem;">No messages yet. Use our live chat to start a conversation.</p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <?php foreach ($messages as $m): ?>
                <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: <?= $m['is_admin'] ? '#D4A01720' : '#3b82f620' ?>; display: flex; align-items: center; justify-content: center; font-size: 0.7rem;">
                                <?= $m['is_admin'] ? '🛡️' : '👤' ?>
                            </div>
                            <span style="font-size: 0.75rem; font-weight: 700; color: <?= $m['is_admin'] ? '#D4A017' : '#3b82f6' ?>;">
                                <?= $m['is_admin'] ? 'Support Agent' : 'You' ?>
                            </span>
                        </div>
                        <span style="font-size: 0.65rem; color: #555;"><?= timeAgo($m['created_at']) ?></span>
                    </div>
                    <p style="font-size: 0.8rem; color: #ccc; margin-top: 0.5rem; line-height: 1.5;"><?= e($m['message']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

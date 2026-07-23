<div class="fade-in space-y-6">
    <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">🔔 Notifications</h2>

    <?php if (empty($notifications)): ?>
        <div style="text-align: center; padding: 3rem; background: #151515; border: 1px solid #1a1a1a; border-radius: 12px;">
            <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">🔕</p>
            <p style="color: #555; font-size: 0.85rem;">No notifications at this time.</p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <?php foreach ($notifications as $n): ?>
                <?php
                    $typeIcons = ['info'=>'ℹ️','promo'=>'🎉','system'=>'⚙️','order'=>'📦'];
                    $typeColors = ['info'=>'#3b82f6','promo'=>'#D4A017','system'=>'#8b5cf6','order'=>'#10b981'];
                    $icon = $typeIcons[$n['type']] ?? '🔔';
                    $color = $typeColors[$n['type']] ?? '#888';
                ?>
                <div style="background: #151515; border: 1px solid <?= $n['read'] ? '#1a1a1a' : $color . '25' ?>; border-radius: 12px; padding: 1rem; <?= !$n['read'] ? 'border-left: 3px solid ' . $color : '' ?>">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                            <div style="width: 32px; height: 32px; border-radius: 8px; background: <?= $color ?>12; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0;">
                                <?= $icon ?>
                            </div>
                            <div>
                                <div style="font-size: 0.85rem; font-weight: 700; color: #fff;"><?= e($n['title']) ?></div>
                                <p style="font-size: 0.75rem; color: #888; margin-top: 0.25rem; line-height: 1.5;"><?= e($n['message']) ?></p>
                            </div>
                        </div>
                        <span style="font-size: 0.6rem; color: #555; white-space: nowrap; margin-left: 1rem;"><?= timeAgo($n['time']) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

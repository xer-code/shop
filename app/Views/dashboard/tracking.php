<div class="fade-in space-y-6">
    <h2 style="font-size: 1rem; font-weight: 700; color: #fff;">🚚 Order Tracking</h2>

    <?php $activeOrders = array_values($orders); ?>
    <?php if (empty($activeOrders)): ?>
        <div style="text-align: center; padding: 3rem; background: #151515; border: 1px solid #1a1a1a; border-radius: 12px;">
            <p style="font-size: 1.5rem; margin-bottom: 0.5rem;">✅</p>
            <p style="color: #555; font-size: 0.85rem;">No orders currently in transit.</p>
        </div>
    <?php else: ?>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php foreach ($activeOrders as $o): ?>
                <?php
                    $steps = ['pending', 'processing', 'shipped', 'delivered'];
                    $currentStep = array_search($o['status'], $steps);
                    if ($currentStep === false) $currentStep = 0;
                ?>
                <div style="background: #151515; border: 1px solid #1a1a1a; border-radius: 12px; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                        <div>
                            <span style="font-size: 0.85rem; font-weight: 700; color: #fff; font-family: monospace;">#<?= $o['tracking_code'] ?? $o['id'] ?></span>
                            <span style="font-size: 0.7rem; color: #555; margin-left: 0.5rem;"><?= date('M j, Y', strtotime($o['created_at'])) ?></span>
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 700; color: #D4A017; font-family: monospace;"><?= formatPrice($o['total']) ?></span>
                    </div>

                    <!-- Progress steps -->
                    <div style="display: flex; align-items: center; gap: 0; position: relative;">
                        <?php foreach ($steps as $i => $step): ?>
                            <?php
                                $isComplete = $i <= $currentStep;
                                $isCurrent = $i === $currentStep;
                                $dotColor = $isComplete ? '#D4A017' : '#333';
                                $labelColor = $isCurrent ? '#D4A017' : ($isComplete ? '#888' : '#444');
                            ?>
                            <div style="flex: 1; text-align: center; position: relative;">
                                <div style="width: 28px; height: 28px; border-radius: 50%; background: <?= $dotColor ?>; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; color: <?= $isComplete ? '#000' : '#666' ?>; font-weight: 800; border: 2px solid <?= $isCurrent ? '#E8C158' : 'transparent' ?>;">
                                    <?= $isComplete ? '✓' : ($i + 1) ?>
                                </div>
                                <div style="font-size: 0.65rem; font-weight: 600; color: <?= $labelColor ?>; margin-top: 0.4rem; text-transform: capitalize;"><?= $step ?></div>
                            </div>
                            <?php if ($i < count($steps) - 1): ?>
                                <div style="flex: 0.6; height: 2px; background: <?= $i < $currentStep ? '#D4A017' : '#333' ?>;"></div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

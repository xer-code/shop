<?php $pageTitle = 'Track Order — ShopX Global'; ?>

<section class="container fade-in" style="padding: 3rem 0; max-width: 800px;">
    <a href="<?= url('/') ?>" style="color: var(--text-muted); font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.35rem; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--text-muted)'">
        ← Back to Homepage
    </a>
    
    <!-- Title -->
    <div style="text-align: center; margin: 2.5rem 0 2rem;">
        <div style="width: 80px; height: 80px; border-radius: 20px; background: rgba(212,160,23,0.08); border: 1px solid rgba(212,160,23,0.25); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; font-size: 2.2rem; box-shadow: 0 8px 25px rgba(0,0,0,0.3);">
            🚚
        </div>
        <h1 style="font-size: 2.2rem; font-weight: 900; letter-spacing: -0.02em;">Order <span class="text-gold">Tracking</span></h1>
        <p style="color: var(--text-muted); margin-top: 0.35rem; font-size: 0.95rem;">Real-time logistics status for all global shipments</p>
        <div style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.4rem 1rem; border: 1px solid rgba(212,160,23,0.3); background: rgba(212,160,23,0.05); border-radius: 20px; margin-top: 1.25rem; font-size: 0.8rem; font-weight: 600; color: var(--gold-primary);">
            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981; animation: pulse 2s infinite;"></span>
            📡 Live Logistics Updates Active
        </div>
    </div>
    
    <!-- Search Form -->
    <form method="POST" action="<?= url('/track-order') ?>" style="max-width: 500px; margin: 0 auto 2.5rem;">
        <?= csrf_field() ?>
        <div class="track-search-row">
            <div style="position: relative; flex-grow: 1;">
                <svg style="position: absolute; left: 1.25rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                <input type="text" name="tracking_code" class="input-dark" placeholder="Enter tracking code (e.g. DHL-9842512-NX) or Order ID..." style="padding-left: 3rem; padding-right: 1.25rem; height: 50px; font-size: 0.95rem; width: 100%; box-sizing: border-box; display: block;" value="<?= e($searchQuery ?? '') ?>" required autocomplete="off">
            </div>
            <button type="submit" class="btn-gold" style="height: 50px; padding: 0 1.25rem; min-width: 100px; border-radius: 8px; font-size: 0.95rem; font-weight: 700; border: none; display: inline-flex; align-items: center; justify-content: center; white-space: nowrap; cursor: pointer; transition: all 0.2s;">
                Track
            </button>
        </div>
    </form>
    
    <!-- Search Result -->
    <!-- Search Result -->
    <?php if (isset($searchResult) && $searchResult): ?>
        <?php
            // Calculate step states based on searchResult/shipmentInfo status
            $status = strtolower($shipmentInfo['status'] ?? $searchResult['status']);

            // Options: pending, processing, out for delivery, shipped, in transit, delivered, cancelled
            $isShippedComp = in_array($status, ['shipped', 'in transit', 'out for delivery', 'delivered']);
            $isInTransitComp = in_array($status, ['out for delivery', 'delivered']);
            $isInTransitActive = ($status === 'in transit');
            $isOutForDeliveryComp = ($status === 'delivered');
            $isOutForDeliveryActive = ($status === 'out for delivery');
            $isDeliveredComp = ($status === 'delivered');

            // Header state text
            $headerText = "It's on the way.";
            if ($status === 'delivered') {
                $headerText = "Delivered.";
            } elseif ($status === 'cancelled') {
                $headerText = "Cancelled.";
            } elseif (in_array($status, ['pending', 'processing'])) {
                $headerText = "Preparing package.";
            }

            // Timestamps
            $createdAt = strtotime($searchResult['created_at'] ?? 'now');
            $updatedAt = strtotime($searchResult['updated_at'] ?? 'now');
            $shippedDate = date('D, M j', $createdAt);
            $estDeliveryDate = date('D, M j', $createdAt + 3 * 86400);
            $deliveredDate = date('D, M j', $updatedAt);
        ?>
        <div class="card" style="margin-bottom: 2.5rem; border-color: rgba(212,160,23,0.35); padding: 2rem; background: linear-gradient(135deg, #121212, #181818); border-radius: 16px;">
            <!-- Card Header -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid #222; padding-bottom: 1.25rem; margin-bottom: 1.5rem;">
                <div>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: white;"><?= e($headerText) ?></h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">
                        <?= isset($searchResult['is_manual']) ? 'Shipment' : 'Order' ?> #<?= $searchResult['id'] ?>
                    </p>
                </div>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <a href="<?= url('/track-order/invoice/' . urlencode($shipmentInfo['tracking_code'])) ?>" target="_blank" class="btn-gold btn-sm" style="padding: 0.5rem 1.25rem; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 0.35rem; border-radius: 8px; text-decoration: none; font-weight: 700;">
                        📄 Invoice / Receipt
                    </a>
                </div>
            </div>

            <!-- Vertical Timeline Stepper -->
            <div style="display: flex; flex-direction: column; margin: 2rem 0 3rem; padding-left: 2rem; position: relative;">
                
                <!-- Step 1: Shipped -->
                <div style="position: relative; padding-bottom: 2.5rem; display: flex; align-items: flex-start;">
                    <!-- Line connecting to next -->
                    <div style="position: absolute; left: -1.45rem; top: 22px; width: 2px; height: calc(100% - 10px); background: <?= $isShippedComp ? '#10b981' : '#333' ?>; z-index: 1;"></div>
                    
                    <!-- Circle node -->
                    <div style="position: absolute; left: -2.15rem; top: 0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;
                         background: <?= $isShippedComp ? '#10b981' : '#181818' ?>;
                         border: 2px solid <?= $isShippedComp ? '#10b981' : '#555' ?>;
                         color: #000; font-size: 0.75rem; font-weight: bold;">
                         <?= $isShippedComp ? '✓' : '' ?>
                    </div>
                    <div>
                        <p style="font-size: 0.95rem; font-weight: 700; color: <?= $isShippedComp ? '#fff' : '#666' ?>;">
                            Shipped <span style="font-weight: 500; font-size: 0.85rem; color: var(--text-muted); margin-left: 0.25rem;"><?= $shippedDate ?></span>
                        </p>
                    </div>
                </div>

                <!-- Step 2: In Transit -->
                <div style="position: relative; padding-bottom: 2.5rem; display: flex; align-items: flex-start;">
                    <!-- Line connecting to next -->
                    <div style="position: absolute; left: -1.45rem; top: 22px; width: 2px; height: calc(100% - 10px); background: <?= $isInTransitComp ? '#10b981' : '#333' ?>; z-index: 1;"></div>
                    
                    <!-- Circle node -->
                    <div style="position: absolute; left: -2.15rem; top: 0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;
                         background: <?= $isInTransitComp ? '#10b981' : '#181818' ?>;
                         border: 2px solid <?= ($isInTransitComp || $isInTransitActive) ? '#10b981' : '#555' ?>;
                         color: #000; font-size: 0.75rem; font-weight: bold;">
                         <?= $isInTransitComp ? '✓' : '' ?>
                    </div>
                    <div>
                        <p style="font-size: 0.95rem; font-weight: 700; color: <?= ($isInTransitComp || $isInTransitActive) ? '#fff' : '#666' ?>;">
                            In transit
                        </p>
                    </div>
                </div>

                <!-- Step 3: Out For Delivery -->
                <div style="position: relative; padding-bottom: 2.5rem; display: flex; align-items: flex-start;">
                    <!-- Line connecting to next -->
                    <div style="position: absolute; left: -1.45rem; top: 22px; width: 2px; height: calc(100% - 10px); background: <?= $isOutForDeliveryComp ? '#10b981' : '#333' ?>; z-index: 1;"></div>
                    
                    <!-- Circle node -->
                    <div style="position: absolute; left: -2.15rem; top: 0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;
                         background: <?= $isOutForDeliveryComp ? '#10b981' : '#181818' ?>;
                         border: 2px solid <?= ($isOutForDeliveryComp || $isOutForDeliveryActive) ? '#10b981' : '#555' ?>;
                         color: #000; font-size: 0.75rem; font-weight: bold;">
                         <?= $isOutForDeliveryComp ? '✓' : '' ?>
                    </div>
                    <div>
                        <p style="font-size: 0.95rem; font-weight: 700; color: <?= ($isOutForDeliveryComp || $isOutForDeliveryActive) ? '#fff' : '#666' ?>;">
                            Out for delivery
                        </p>
                    </div>
                </div>

                <!-- Step 4: Estimated Delivery / Delivered -->
                <div style="position: relative; display: flex; align-items: flex-start;">
                    <!-- Circle node -->
                    <div style="position: absolute; left: -2.15rem; top: 0; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 2;
                         background: <?= $isDeliveredComp ? '#10b981' : '#181818' ?>;
                         border: 2px solid <?= $isDeliveredComp ? '#10b981' : '#555' ?>;
                         color: #000; font-size: 0.75rem; font-weight: bold;">
                         <?= $isDeliveredComp ? '✓' : '' ?>
                    </div>
                    <div>
                        <p style="font-size: 0.95rem; font-weight: 700; color: <?= $isDeliveredComp ? '#fff' : '#666' ?>;">
                            <?php if ($isDeliveredComp): ?>
                                Delivered <span style="font-weight: 500; font-size: 0.85rem; color: var(--text-muted); margin-left: 0.25rem;"><?= $deliveredDate ?></span>
                            <?php else: ?>
                                Estimated delivery <span style="font-weight: 500; font-size: 0.85rem; color: var(--text-muted); margin-left: 0.25rem;"><?= $estDeliveryDate ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

            </div>

            <!-- Detailed Logs Layout (Matching Image Side-by-Side Dates and Descriptions) -->
            <?php if (!empty($trackingLogs)): ?>
                <div style="margin-top: 3rem; border-top: 1px solid #222; padding-top: 2rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: white; margin-bottom: 0.75rem; font-family: inherit;">
                        Tracking details
                    </h3>
                    
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 2.25rem; font-size: 0.9rem; font-family: monospace;">
                         <span style="font-weight: 700; color: var(--text-muted); text-transform: uppercase;"><?= e($shipmentInfo['carrier'] ?? 'ShopX Courier') ?></span>
                         <span style="color: #bbb;"><?= e($shipmentInfo['tracking_code']) ?></span>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-left: 0.25rem;">
                         <?php foreach ($trackingLogs as $log): ?>
                              <?php 
                                  $logTimestamp = strtotime($log['timestamp']);
                                  $logDate = date('M j, Y', $logTimestamp);
                                  $logTime = date('g:ia', $logTimestamp);
                              ?>
                              <div class="track-log-item">
                                   <!-- Left Side: Date and Time Stacked -->
                                   <div style="width: 110px; flex-shrink: 0; text-align: left; line-height: 1.4;">
                                        <div style="font-size: 0.85rem; font-weight: 700; color: white;"><?= $logDate ?></div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);"><?= $logTime ?></div>
                                   </div>
                                   
                                   <!-- Right Side: Status Title and Location -->
                                   <div style="flex-grow: 1; line-height: 1.4;">
                                        <div style="font-size: 0.9rem; font-weight: 600; color: #eee;"><?= e($log['status']) ?></div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-top: 0.15rem; letter-spacing: 0.02em;">
                                            <?= e($log['location']) ?>
                                        </div>
                                   </div>
                              </div>
                         <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php elseif (isset($searched) && $searched && !$searchResult): ?>
        <div class="flash-message flash-error" style="margin-bottom: 2rem; border-color: rgba(239, 68, 68, 0.35); background: rgba(239, 68, 68, 0.05); color: #f87171; border-radius: 8px; padding: 1rem 1.25rem;">
            Wrong tracking number
        </div>
    <?php endif; ?>
</section>

<style>
@keyframes pulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
.track-search-row {
    display: flex;
    gap: 0.75rem;
    align-items: center;
}
.track-log-item {
    display: flex;
    gap: 2rem;
    align-items: flex-start;
}
@media (max-width: 576px) {
    .track-log-item {
        flex-direction: column;
        gap: 0.4rem;
        background: rgba(255,255,255,0.02);
        padding: 0.85rem;
        border-radius: 8px;
        border: 1px solid #222;
    }
    .track-log-item > div:first-child {
        width: 100% !important;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #222;
        padding-bottom: 0.35rem;
        margin-bottom: 0.25rem;
    }
}
</style>

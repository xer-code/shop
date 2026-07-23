<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= e($pageTitle) ?></title>
    <style>
        :root {
            --gold-primary: #D4A017;
            --text-dark: #111;
            --text-muted: #666;
            --border-color: #e2e8f0;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--text-dark);
            background-color: #f8fafc;
            line-height: 1.5;
            padding: 3rem 1rem;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--gold-primary);
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .logo-section h1 {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .logo-section h1 span {
            color: var(--gold-primary);
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .invoice-title p {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-family: monospace;
            margin-top: 0.25rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2.5rem;
        }

        .details-block h3 {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .details-block p {
            font-size: 0.9rem;
            color: var(--text-dark);
            line-height: 1.4;
        }

        .table-container {
            margin-bottom: 2.5rem;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .invoice-table th {
            padding: 0.75rem 1rem;
            border-bottom: 2px solid var(--border-color);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.05em;
        }

        .invoice-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }

        .invoice-table td.amount {
            text-align: right;
            font-weight: 600;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 3rem;
        }

        .totals-table {
            width: 250px;
            border-collapse: collapse;
        }

        .totals-table td {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .totals-table tr.grand-total td {
            border-top: 2px solid var(--gold-primary);
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-dark);
            padding-top: 0.75rem;
        }

        .footer-note {
            text-align: center;
            border-top: 1px solid var(--border-color);
            padding-top: 2rem;
            margin-top: 2rem;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Action bar */
        .action-bar {
            max-width: 800px;
            margin: 0 auto 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-back {
            background-color: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        .btn-back:hover {
            color: var(--text-dark);
            background-color: #f1f5f9;
        }

        .btn-print {
            background-color: var(--gold-primary);
            color: #000;
            box-shadow: 0 4px 12px rgba(212, 160, 23, 0.2);
        }

        .btn-print:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                border: none;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Actions -->
    <div class="action-bar no-print">
        <button onclick="window.close();" class="btn btn-back">
            ✕ Close Window
        </button>
        <button onclick="window.print();" class="btn btn-print">
            🖨️ Print / Download PDF
        </button>
    </div>

    <!-- Container -->
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="logo-section">
                <h1>ShopX<span>Global</span></h1>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">E-Commerce & Logistics Worldwide</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p><?= e($invoice['invoice_num']) ?></p>
            </div>
        </div>

        <!-- Billed to & Invoice Info -->
        <div class="details-grid">
            <div class="details-block">
                <h3>Billed To</h3>
                <p>
                    <strong><?= e($invoice['billed_to']['name']) ?></strong><br>
                    <?php if (!empty($invoice['billed_to']['email'])): ?>
                        <?= e($invoice['billed_to']['email']) ?><br>
                    <?php endif; ?>
                    <?= e($invoice['billed_to']['address']) ?>
                </p>
            </div>
            <div class="details-block" style="text-align: right;">
                <h3>Invoice Info</h3>
                <p>
                    <strong>Date:</strong> <?= e($invoice['date']) ?><br>
                    <strong>Payment Method:</strong> <?= e($invoice['payment_method']) ?><br>
                    <strong>Tracking Code:</strong> <span style="font-family: monospace; font-weight: 700; color: var(--gold-primary);"><?= e($invoice['tracking_code']) ?></span><br>
                    <strong>Carrier:</strong> <?= e($invoice['carrier']) ?>
                </p>
            </div>
        </div>

        <!-- Table -->
        <div class="table-container">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th style="width: 55%;">Line Item Description</th>
                        <th style="text-align: center; width: 15%;">Quantity</th>
                        <th style="text-align: right; width: 15%;">Unit Price</th>
                        <th style="text-align: right; width: 15%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoice['items'] as $item): ?>
                        <tr>
                            <td>
                                <strong><?= e($item['name']) ?></strong>
                            </td>
                            <td style="text-align: center;"><?= $item['qty'] ?></td>
                            <td style="text-align: right;"><?= formatPrice($item['price']) ?></td>
                            <td class="amount"><?= formatPrice($item['total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="totals-section">
            <table class="totals-table">
                <tbody>
                    <tr>
                        <td style="color: var(--text-muted);">Subtotal</td>
                        <td style="text-align: right; font-weight: 600;"><?= formatPrice($invoice['total']) ?></td>
                    </tr>
                    <tr>
                        <td style="color: var(--text-muted);">Tax / Duty (0%)</td>
                        <td style="text-align: right; font-weight: 600;"><?= formatPrice(0.00) ?></td>
                    </tr>
                    <tr class="grand-total">
                        <td>Amount Due</td>
                        <td style="text-align: right; color: var(--gold-primary);"><?= formatPrice($invoice['total']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Thanks -->
        <div class="footer-note">
            <p style="font-weight: 700; margin-bottom: 0.5rem; letter-spacing: 0.02em;">Thank you for your patronage!</p>
            <p style="font-size: 0.75rem;">If you have any questions about this invoice, please contact support@shopx.com</p>
        </div>
    </div>

</body>
</html>

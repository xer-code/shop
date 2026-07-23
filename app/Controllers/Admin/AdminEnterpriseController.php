<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class AdminEnterpriseController extends Controller
{
    public function __construct()
    {
        $this->initEnterpriseData();
    }

    /**
     * Initialize mock datasets in Session for full interactive control
     */
    private function initEnterpriseData(): void
    {
        // Ensure chat_messages table has session_id column for guest support
        $db = Database::getInstance();
        $columns = $db->query("SHOW COLUMNS FROM chat_messages LIKE 'session_id'")->fetch();
        if (!$columns) {
            $db->query("ALTER TABLE chat_messages ADD COLUMN session_id VARCHAR(128) DEFAULT NULL");
            $db->query("ALTER TABLE chat_messages ADD INDEX idx_session_chat (session_id)");
        }

        // 1. Suppliers
        if (!Session::has('ent_suppliers')) {
            Session::set('ent_suppliers', [
                ['id' => 1, 'name' => 'Global ElectroTech Inc.', 'contact' => 'supply@electrotech.com', 'category' => 'Electronics', 'status' => 'Active'],
                ['id' => 2, 'name' => 'Vogue Wearhouse Co.', 'contact' => 'info@voguewearhouse.com', 'category' => 'Fashion', 'status' => 'Active'],
                ['id' => 3, 'name' => 'Apex Gaming Suppliers', 'contact' => 'support@apexgaming.io', 'category' => 'Gaming', 'status' => 'Active']
            ]);
        }

        // 2. Invoices
        if (!Session::has('ent_invoices')) {
            Session::set('ent_invoices', [
                ['id' => 1001, 'order_id' => 1, 'customer' => 'John Customer', 'date' => '2026-07-18', 'total' => 3499.00, 'status' => 'Paid'],
                ['id' => 1002, 'order_id' => 2, 'customer' => 'Jane Shopper', 'date' => '2026-07-19', 'total' => 599.00, 'status' => 'Paid'],
                ['id' => 1003, 'order_id' => 3, 'customer' => 'John Customer', 'date' => '2026-07-19', 'total' => 1199.00, 'status' => 'Unpaid']
            ]);
        }

        // 3. Quotes
        if (!Session::has('ent_quotes')) {
            Session::set('ent_quotes', [
                ['id' => 1, 'customer' => 'Enterprise TechCorp', 'items' => 'MacBook Pro 16" (Qty: 10)', 'target_price' => 32000.00, 'status' => 'Pending', 'date' => '2026-07-18'],
                ['id' => 2, 'customer' => 'FastGaming LLC', 'items' => 'PlayStation 5 Pro (Qty: 25)', 'target_price' => 16000.00, 'status' => 'Approved', 'date' => '2026-07-19']
            ]);
        }

        // 4. Warehouses
        if (!Session::has('ent_warehouses')) {
            Session::set('ent_warehouses', [
                ['id' => 1, 'name' => 'New York Central Warehouse', 'location' => 'Long Island City, NY, USA', 'capacity' => '85%', 'manager' => 'David Miller'],
                ['id' => 2, 'name' => 'Frankfurt Logistics Hub', 'location' => 'Frankfurt, Germany', 'capacity' => '42%', 'manager' => 'Stefan Becker'],
                ['id' => 3, 'name' => 'Tokyo East Depot', 'location' => 'Koto City, Tokyo, Japan', 'capacity' => '68%', 'manager' => 'Kenji Sato']
            ]);
        }

        // 5. Shipments
        if (!Session::has('ent_shipments')) {
            Session::set('ent_shipments', [
                ['id' => 1, 'order_id' => 1, 'carrier' => 'DHL Express', 'tracking_code' => 'DHL-9842512-NX', 'status' => 'Shipped', 'origin' => 'Tokyo East Depot', 'destination' => 'New York City, NY'],
                ['id' => 2, 'order_id' => 2, 'carrier' => 'FedEx Economy', 'tracking_code' => 'FDX-7764120-EM', 'status' => 'Delivered', 'origin' => 'Frankfurt Logistics Hub', 'destination' => 'London, UK']
            ]);
        }

        // 6. Tracking Log
        if (!Session::has('ent_tracking')) {
            Session::set('ent_tracking', [
                ['id' => 1, 'tracking_code' => 'DHL-9842512-NX', 'timestamp' => '2026-07-19 14:00', 'location' => 'Tokyo Sort Facility', 'status' => 'Arrived at Sort Facility'],
                ['id' => 2, 'tracking_code' => 'DHL-9842512-NX', 'timestamp' => '2026-07-19 16:30', 'location' => 'Tokyo Narita Airport', 'status' => 'Departed Port of Origin']
            ]);
        }

        // 7. Support Tickets
        if (!Session::has('ent_support')) {
            Session::set('ent_support', []);
        }

        // 8. Promotions
        if (!Session::has('ent_promotions')) {
            Session::set('ent_promotions', [
                ['id' => 1, 'name' => 'Summer Tech Sale', 'type' => 'Banner', 'discount' => 'Up to 25% Off', 'active' => 1],
                ['id' => 2, 'name' => 'First Order Welcome Discount', 'type' => 'Popup', 'discount' => '$50 Credit', 'active' => 1]
            ]);
        }

        // 9. Coupons
        if (!Session::has('ent_coupons')) {
            Session::set('ent_coupons', [
                ['id' => 1, 'code' => 'SUMMER20', 'type' => 'Percent', 'value' => 20, 'limit' => 500, 'used' => 42, 'expiry' => '2026-08-31'],
                ['id' => 2, 'code' => 'FLAT50', 'type' => 'Flat', 'value' => 50, 'limit' => 100, 'used' => 95, 'expiry' => '2026-07-31']
            ]);
        }

        // 10. Audit Logs
        if (!Session::has('ent_audit_logs')) {
            Session::set('ent_audit_logs', [
                ['id' => 1, 'user' => 'admin@shopx.com', 'action' => 'Reseeded database and updated hashes', 'ip' => '127.0.0.1', 'timestamp' => '2026-07-19 19:22:11'],
                ['id' => 2, 'user' => 'admin@shopx.com', 'action' => 'Updated base Model class with all() alias', 'ip' => '127.0.0.1', 'timestamp' => '2026-07-19 19:29:12'],
                ['id' => 3, 'user' => 'admin@shopx.com', 'action' => 'Configured Enterprise Router mappings', 'ip' => '127.0.0.1', 'timestamp' => '2026-07-19 19:35:58']
            ]);
        }

        // 11. API Keys
        if (!Session::has('ent_api_keys')) {
            Session::set('ent_api_keys', [
                ['id' => 1, 'name' => 'DHL Shipping Service', 'token' => 'sk_live_dhl_4f82d1c98e82a934bd489', 'status' => 'Active', 'created_at' => '2026-07-15'],
                ['id' => 2, 'name' => 'Stripe Gateway Integration', 'token' => 'sk_live_stripe_9a0b12e34d98a4d708a32', 'status' => 'Active', 'created_at' => '2026-07-16']
            ]);
        }

        // 12. System Settings
        if (!Session::has('ent_settings')) {
            Session::set('ent_settings', [
                'app_name' => 'ShopX Global',
                'currency' => 'USD',
                'support_email' => 'support@shopx.com',
                'maintenance_mode' => 0,
                'tax_rate' => 8.5,
                'tracking_prefix' => 'SX'
            ]);
        }

        // 13. Role Permissions
        if (!Session::has('ent_roles')) {
            Session::set('ent_roles', [
                'admin' => ['manage_products' => 1, 'view_analytics' => 1, 'manage_users' => 1, 'manage_settings' => 1],
                'manager' => ['manage_products' => 1, 'view_analytics' => 1, 'manage_users' => 0, 'manage_settings' => 0],
                'support' => ['manage_products' => 0, 'view_analytics' => 0, 'manage_users' => 0, 'manage_settings' => 0]
            ]);
        }

        // 14. Payment Gateways
        getPaymentGateways();
    }

    /**
     * Add action to audit logs
     */
    private function logAction(string $action): void
    {
        $logs = Session::get('ent_audit_logs', []);
        $newLog = [
            'id' => count($logs) + 1,
            'user' => Auth::email() ?? 'admin@shopx.com',
            'action' => $action,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        array_unshift($logs, $newLog);
        Session::set('ent_audit_logs', $logs);
    }

    // ============================================
    // ROUTE HANDLERS
    // ============================================

    public function analytics(): void
    {
        $this->render('admin/analytics', [
            'pageTitle' => 'Analytics Suite'
        ], 'admin');
    }

    public function customers(): void
    {
        $customers = User::where(['role' => 'customer']);
        $this->render('admin/customers', [
            'pageTitle' => 'Customer Accounts Directory',
            'customers' => $customers
        ], 'admin');
    }

    public function suspendCustomer(string $id): void
    {
        $this->validateCsrf();
        $user = User::find((int) $id);
        if ($user) {
            $newStatus = $user['is_suspended'] ? 0 : 1;
            User::update((int) $id, ['is_suspended' => $newStatus]);
            $action = $newStatus ? "Suspended customer: " . $user['email'] : "Unsuspended customer: " . $user['email'];
            $this->logAction($action);
            Session::flash('success', $newStatus ? 'Customer account suspended.' : 'Customer account unsuspended.');
        } else {
            Session::flash('error', 'Customer not found.');
        }
        $this->redirect('/admin/customers');
    }

    public function fundCustomer(string $id): void
    {
        $this->validateCsrf();
        $user = User::find((int) $id);
        $amount = (float) $this->input('amount', 0);
        if ($user && $amount > 0) {
            $newBalance = $user['wallet_balance'] + $amount;
            User::update((int) $id, ['wallet_balance' => $newBalance]);
            
            // Record a wallet transaction
            $db = Database::getInstance();
            $db->query(
                "INSERT INTO wallet_transactions (user_id, type, amount, description) VALUES (?, 'deposit', ?, 'Refund/Fund load by administrator')",
                [$id, $amount]
            );

            $this->logAction("Admin funded customer wallet: " . $user['email'] . " with $" . number_format($amount, 2));
            Session::flash('success', 'Customer wallet funded successfully by $' . number_format($amount, 2));
        } else {
            Session::flash('error', 'Invalid customer or funding amount.');
        }
        $this->redirect('/admin/customers');
    }

    public function suppliers(): void
    {
        $suppliers = Session::get('ent_suppliers', []);
        $this->render('admin/suppliers', [
            'pageTitle' => 'Supplier Directory & Management',
            'suppliers' => $suppliers
        ], 'admin');
    }

    public function createSupplier(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        $contact = trim($this->input('contact', ''));
        $category = trim($this->input('category', ''));

        if (!empty($name) && !empty($contact)) {
            $suppliers = Session::get('ent_suppliers', []);
            $newSupplier = [
                'id' => count($suppliers) + 1,
                'name' => $name,
                'contact' => $contact,
                'category' => $category,
                'status' => 'Active'
            ];
            $suppliers[] = $newSupplier;
            Session::set('ent_suppliers', $suppliers);
            $this->logAction("Created new supplier: " . $name);
            Session::flash('success', 'Supplier created successfully.');
        } else {
            Session::flash('error', 'Please fill in name and contact details.');
        }
        $this->redirect('/admin/suppliers');
    }

    public function deleteSupplier(string $id): void
    {
        $this->validateCsrf();
        $suppliers = Session::get('ent_suppliers', []);
        foreach ($suppliers as $key => $s) {
            if ($s['id'] == (int) $id) {
                $this->logAction("Deleted supplier: " . $s['name']);
                unset($suppliers[$key]);
                break;
            }
        }
        Session::set('ent_suppliers', array_values($suppliers));
        Session::flash('success', 'Supplier removed.');
        $this->redirect('/admin/suppliers');
    }

    public function categories(): void
    {
        $categories = Category::all();
        $this->render('admin/categories', [
            'pageTitle' => 'Taxonomy & Categories Management',
            'categories' => $categories
        ], 'admin');
    }

    public function createCategory(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        $slug = trim($this->input('slug', ''));
        $icon = trim($this->input('icon', ''));

        if (empty($slug)) {
            $slug = strtolower(str_replace(' ', '-', $name));
        }

        if (!empty($name)) {
            Category::create([
                'name' => $name,
                'slug' => $slug,
                'icon' => $icon ?: '📁'
            ]);
            $this->logAction("Created product category: " . $name);
            Session::flash('success', 'Category added successfully.');
        } else {
            Session::flash('error', 'Category name is required.');
        }
        $this->redirect('/admin/categories');
    }

    public function deleteCategory(string $id): void
    {
        $this->validateCsrf();
        $cat = Category::find((int) $id);
        if ($cat) {
            Category::delete((int) $id);
            $this->logAction("Deleted category: " . $cat['name']);
            Session::flash('success', 'Category removed successfully.');
        } else {
            Session::flash('error', 'Category not found.');
        }
        $this->redirect('/admin/categories');
    }

    public function payments(): void
    {
        $db = Database::getInstance();
        $transactions = $db->query(
            "SELECT t.*, u.name as customer_name, u.email as customer_email 
             FROM wallet_transactions t 
             JOIN users u ON t.user_id = u.id 
             ORDER BY t.created_at DESC"
        )->fetchAll();

        $this->render('admin/payments', [
            'pageTitle' => 'Financial Transactions Log',
            'transactions' => $transactions
        ], 'admin');
    }

    public function invoices(): void
    {
        $invoices = Session::get('ent_invoices', []);
        $this->render('admin/invoices', [
            'pageTitle' => 'Invoices Billing Ledger',
            'invoices' => $invoices
        ], 'admin');
    }

    public function showInvoice(string $id): void
    {
        $invoices = Session::get('ent_invoices', []);
        $invoice = null;
        foreach ($invoices as $i) {
            if ($i['id'] == (int) $id) {
                $invoice = $i;
                break;
            }
        }

        if (!$invoice) {
            Session::flash('error', 'Invoice not found.');
            $this->redirect('/admin/invoices');
            return;
        }

        $this->render('admin/invoice_details', [
            'pageTitle' => 'Invoice Details #' . $invoice['id'],
            'invoice' => $invoice
        ], 'admin');
    }

    public function quotes(): void
    {
        $quotes = Session::get('ent_quotes', []);
        $this->render('admin/quotes', [
            'pageTitle' => 'Wholesale Pricing Quotes',
            'quotes' => $quotes
        ], 'admin');
    }

    public function updateQuoteStatus(string $id): void
    {
        $this->validateCsrf();
        $status = $this->input('status', 'Pending');
        $quotes = Session::get('ent_quotes', []);
        foreach ($quotes as &$q) {
            if ($q['id'] == (int) $id) {
                $q['status'] = $status;
                $this->logAction("Updated Quote #{$id} status to " . $status);
                break;
            }
        }
        Session::set('ent_quotes', $quotes);
        Session::flash('success', 'Quote status updated.');
        $this->redirect('/admin/quotes');
    }

    public function warehouses(): void
    {
        $warehouses = Session::get('ent_warehouses', []);
        $this->render('admin/warehouses', [
            'pageTitle' => 'Warehouse & Storage Directory',
            'warehouses' => $warehouses
        ], 'admin');
    }

    public function createWarehouse(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        $location = trim($this->input('location', ''));
        $capacity = trim($this->input('capacity', '0%'));
        $manager = trim($this->input('manager', ''));

        if (!empty($name) && !empty($location)) {
            $warehouses = Session::get('ent_warehouses', []);
            $newWarehouse = [
                'id' => count($warehouses) + 1,
                'name' => $name,
                'location' => $location,
                'capacity' => $capacity,
                'manager' => $manager
            ];
            $warehouses[] = $newWarehouse;
            Session::set('ent_warehouses', $warehouses);
            $this->logAction("Added warehouse: " . $name);
            Session::flash('success', 'Warehouse profile added.');
        } else {
            Session::flash('error', 'Name and location are required.');
        }
        $this->redirect('/admin/warehouses');
    }

    public function deleteWarehouse(string $id): void
    {
        $this->validateCsrf();
        $warehouses = Session::get('ent_warehouses', []);
        foreach ($warehouses as $key => $w) {
            if ($w['id'] == (int) $id) {
                $this->logAction("Deleted warehouse: " . $w['name']);
                unset($warehouses[$key]);
                break;
            }
        }
        Session::set('ent_warehouses', array_values($warehouses));
        Session::flash('success', 'Warehouse deleted.');
        $this->redirect('/admin/warehouses');
    }

    public function shipments(): void
    {
        $shipments = Session::get('ent_shipments', []);
        $this->render('admin/shipments', [
            'pageTitle' => 'Shipments Order Routing',
            'shipments' => $shipments
        ], 'admin');
    }

    public function createShipment(): void
    {
        $this->validateCsrf();
        
        $senderName = trim($this->input('sender_name', ''));
        $senderAddress = trim($this->input('sender_address', ''));
        $senderContact = trim($this->input('sender_contact', ''));
        
        $receiverName = trim($this->input('receiver_name', ''));
        $receiverAddress = trim($this->input('receiver_address', ''));
        $receiverContact = trim($this->input('receiver_contact', ''));
        
        $productType = trim($this->input('product_type', ''));
        $productWeight = trim($this->input('product_weight', ''));
        $amount = (float) $this->input('amount', 0.00);
        
        $carrier = trim($this->input('carrier', 'DHL Express'));
        $origin = trim($this->input('origin', ''));
        $destination = trim($this->input('destination', ''));

        if (!empty($receiverName) && !empty($destination)) {
            $settings = Session::get('ent_settings', []);
            $prefix = $settings['tracking_prefix'] ?? 'SX';
            $trackingCode = strtoupper($prefix . '-' . substr(md5(uniqid()), 0, 10));
            
            $shipments = Session::get('ent_shipments', []);
            $newShipment = [
                'id' => count($shipments) + 1,
                'order_id' => 0, // Manual shipment, no store order ref
                'sender_name' => $senderName,
                'sender_address' => $senderAddress,
                'sender_contact' => $senderContact,
                'receiver_name' => $receiverName,
                'receiver_address' => $receiverAddress,
                'receiver_contact' => $receiverContact,
                'product_type' => $productType,
                'product_weight' => $productWeight,
                'amount' => $amount,
                'carrier' => $carrier,
                'tracking_code' => $trackingCode,
                'status' => 'Shipped',
                'origin' => $origin ?: 'Fulfillment Center',
                'destination' => $destination
            ];
            $shipments[] = $newShipment;
            Session::set('ent_shipments', $shipments);
            
            // Add initial tracking log checkpoint
            $tracking = Session::get('ent_tracking', []);
            $tracking[] = [
                'id' => count($tracking) + 1,
                'tracking_code' => $trackingCode,
                'timestamp' => date('Y-m-d H:i'),
                'location' => $origin ?: 'Origin Facility',
                'status' => 'Shipment created and dispatched'
            ];
            Session::set('ent_tracking', $tracking);

            $this->logAction("Created manual shipment: {$trackingCode} for {$receiverName}");
            Session::flash('success', "Shipment created successfully. Tracking Code: {$trackingCode}");
        } else {
            Session::flash('error', 'Receiver Name and Destination Address are required.');
        }
        $this->redirect('/admin/shipments');
    }

    public function updateShipmentStatus(string $id): void
    {
        $this->validateCsrf();
        $status = $this->input('status', 'Shipped');
        $shipments = Session::get('ent_shipments', []);
        foreach ($shipments as &$s) {
            if ($s['id'] == (int) $id) {
                $s['status'] = $status;
                $this->logAction("Updated Shipment #{$id} status to " . $status);
                break;
            }
        }
        Session::set('ent_shipments', $shipments);
        Session::flash('success', 'Shipment status updated.');
        $this->redirect('/admin/shipments');
    }

    public function deleteShipment(string $id): void
    {
        $this->validateCsrf();
        $shipments = Session::get('ent_shipments', []);
        $found = false;
        $deletedTrackingCode = '';

        foreach ($shipments as $key => $s) {
            if ($s['id'] == (int) $id) {
                $deletedTrackingCode = $s['tracking_code'] ?? '';
                unset($shipments[$key]);
                $found = true;
                break;
            }
        }

        if ($found) {
            Session::set('ent_shipments', array_values($shipments));

            // Clean up ent_tracking.json if tracking record exists
            if (!empty($deletedTrackingCode)) {
                $tracking = Session::get('ent_tracking', []);
                $trackingChanged = false;
                foreach ($tracking as $tKey => $t) {
                    if (isset($t['tracking_code']) && $t['tracking_code'] === $deletedTrackingCode) {
                        unset($tracking[$tKey]);
                        $trackingChanged = true;
                    }
                }
                if ($trackingChanged) {
                    Session::set('ent_tracking', array_values($tracking));
                }
            }

            $this->logAction("Permanently deleted shipment #{$id}");
            Session::flash('success', "Shipment #{$id} deleted successfully.");
        } else {
            Session::flash('error', 'Shipment record not found.');
        }

        $this->redirect('/admin/shipments');
    }

    public function tracking(): void
    {
        $trackingList = [];
        
        // Add database orders
        $db = Database::getInstance();
        $dbOrders = $db->query("
            SELECT o.id as order_id, o.tracking_code, o.status, u.name as customer_name, u.email as customer_email, o.shipping_address as destination
            FROM orders o 
            JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
        ")->fetchAll();
        
        foreach ($dbOrders as $order) {
            $trackingList[] = [
                'type' => 'Store Order',
                'id' => $order['order_id'],
                'order_id' => $order['order_id'],
                'tracking_code' => $order['tracking_code'],
                'user' => $order['customer_name'] . ' (' . $order['customer_email'] . ')',
                'destination' => $order['destination'],
                'status' => $order['status']
            ];
        }
        
        // Add session shipments (manually created ones, or merged)
        $sessionShipments = Session::get('ent_shipments', []);
        foreach ($sessionShipments as $s) {
            // Check if already in trackingList (by tracking_code)
            $exists = false;
            foreach ($trackingList as &$item) {
                if ($item['tracking_code'] === $s['tracking_code']) {
                    $item['type'] = 'Store Order & Shipment';
                    $item['carrier'] = $s['carrier'] ?? '';
                    $item['origin'] = $s['origin'] ?? '';
                    $item['destination'] = $s['destination'] ?? $item['destination'];
                    $item['status'] = $s['status'] ?? $item['status'];
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                // Manually created shipment
                $trackingList[] = [
                    'type' => 'Manual Shipment',
                    'id' => $s['id'],
                    'order_id' => $s['order_id'] ?? null,
                    'tracking_code' => $s['tracking_code'],
                    'user' => ($s['receiver_name'] ?? 'N/A') . ' (' . ($s['receiver_contact'] ?? 'Guest') . ')',
                    'destination' => $s['destination'] ?? '',
                    'status' => $s['status'] ?? 'Pending',
                    'carrier' => $s['carrier'] ?? '',
                    'origin' => $s['origin'] ?? ''
                ];
            }
        }

        $this->render('admin/tracking', [
            'pageTitle' => 'Package Logistics Tracking Logs',
            'trackingList' => $trackingList
        ], 'admin');
    }

    public function manageTracking(string $code): void
    {
        // Find the tracking code in orders or shipments
        $item = null;
        
        $db = Database::getInstance();
        $order = $db->query("
            SELECT o.*, u.name as customer_name, u.email as customer_email 
            FROM orders o 
            JOIN users u ON o.user_id = u.id 
            WHERE o.tracking_code = ?
        ", [$code])->fetch();
        
        if ($order) {
            $status = $order['status'];
            $sessionShipments = Session::get('ent_shipments', []);
            foreach ($sessionShipments as $s) {
                if ($s['tracking_code'] === $code) {
                    $status = $s['status'];
                    break;
                }
            }
            $item = [
                'type' => 'Store Order',
                'tracking_code' => $order['tracking_code'],
                'user' => $order['customer_name'] . ' (' . $order['customer_email'] . ')',
                'destination' => $order['shipping_address'],
                'status' => ucfirst($status),
                'origin' => 'Tokyo East Depot'
            ];
        } else {
            $shipments = Session::get('ent_shipments', []);
            foreach ($shipments as $s) {
                if ($s['tracking_code'] === $code) {
                    $item = [
                        'type' => 'Manual Shipment',
                        'tracking_code' => $s['tracking_code'],
                        'user' => ($s['receiver_name'] ?? 'N/A') . ' (' . ($s['receiver_contact'] ?? 'Guest') . ')',
                        'destination' => $s['destination'],
                        'status' => ucfirst($s['status']),
                        'origin' => $s['origin'] ?? 'Fulfillment Center'
                    ];
                    break;
                }
            }
        }
        
        if (!$item) {
            Session::flash('error', 'Tracking code not found.');
            $this->redirect('/admin/tracking');
            return;
        }
        
        // Fetch current checkpoint logs for this code
        $allLogs = Session::get('ent_tracking', []);
        $itemLogs = [];
        foreach ($allLogs as $log) {
            if ($log['tracking_code'] === $code) {
                $itemLogs[] = $log;
            }
        }
        usort($itemLogs, function($a, $b) {
            return strcmp($b['timestamp'], $a['timestamp']);
        });
        
        $this->render('admin/tracking/manage', [
            'pageTitle' => "Manage Tracking: {$code}",
            'item' => $item,
            'logs' => $itemLogs
        ], 'admin');
    }
    
    public function updateTracking(string $code): void
    {
        $this->validateCsrf();
        $status = trim($this->input('status', 'Pending'));
        $location = trim($this->input('location', ''));
        $description = trim($this->input('description', ''));
        
        if (empty($location) || empty($description)) {
            Session::flash('error', 'Transit location and status description are required.');
            $this->redirect('/admin/tracking/manage/' . $code);
            return;
        }
        
        // 1. Update status in DB orders if exists
        $db = Database::getInstance();
        $order = $db->query("SELECT * FROM orders WHERE tracking_code = ?", [$code])->fetch();
        if ($order) {
            // Map statuses for database table orders
            // Options: Pending, Processing, Out for delivery, Shipped, In transit, Delivered, Cancelled
            $dbStatus = strtolower($status);
            if (in_array($dbStatus, ['in transit', 'out for delivery'])) {
                $dbStatus = 'shipped';
            }
            $db->query("UPDATE orders SET status = ?, updated_at = NOW() WHERE tracking_code = ?", [$dbStatus, $code]);
        }
        
        // 2. Update status in session shipments
        $shipments = Session::get('ent_shipments', []);
        $found = false;
        foreach ($shipments as &$s) {
            if ($s['tracking_code'] === $code) {
                $s['status'] = $status;
                $found = true;
                break;
            }
        }
        if (!$found && $order) {
            $shipments[] = [
                'id' => count($shipments) + 1,
                'order_id' => $order['id'],
                'tracking_code' => $code,
                'status' => $status,
                'carrier' => 'ShopX Global Logistics',
                'origin' => 'Tokyo East Depot',
                'destination' => $order['shipping_address'] ?? ''
            ];
        }
        Session::set('ent_shipments', $shipments);
        
        // 3. Add new checkpoint tracking log entry
        $trackingLogs = Session::get('ent_tracking', []);
        $trackingLogs[] = [
            'id' => count($trackingLogs) + 1,
            'tracking_code' => $code,
            'timestamp' => date('Y-m-d H:i'),
            'location' => $location,
            'status' => $description
        ];
        Session::set('ent_tracking', $trackingLogs);
        
        $this->logAction("Updated tracking checkpoint status for waybill {$code} to: {$status}");
        Session::flash('success', "Tracking state for {$code} updated successfully.");
        $this->redirect('/admin/tracking');
    }

    public function support(): void
    {
        $tickets = Session::get('ent_support', []);
        $this->render('admin/support', [
            'pageTitle' => 'Support Desk Inbound Tickets',
            'tickets' => $tickets
        ], 'admin');
    }

    public function replySupport(string $id): void
    {
        $this->validateCsrf();
        $reply = trim($this->input('reply', ''));
        if (!empty($reply)) {
            $tickets = Session::get('ent_support', []);
            foreach ($tickets as &$t) {
                if ($t['id'] == (int) $id) {
                    $t['messages'][] = [
                        'sender' => 'admin',
                        'text' => $reply,
                        'time' => date('Y-m-d H:i:s')
                    ];
                    $t['status'] = 'Answered';
                    $this->logAction("Admin replied to support ticket #{$id}");
                    break;
                }
            }
            Session::set('ent_support', $tickets);
            Session::flash('success', 'Reply submitted.');
        } else {
            Session::flash('error', 'Message cannot be empty.');
        }
        $this->redirect('/admin/support');
    }

    public function closeSupport(string $id): void
    {
        $this->validateCsrf();
        $tickets = Session::get('ent_support', []);
        foreach ($tickets as &$t) {
            if ($t['id'] == (int) $id) {
                $t['status'] = 'Closed';
                $this->logAction("Closed support ticket #{$id}");
                break;
            }
        }
        Session::set('ent_support', $tickets);
        Session::flash('success', 'Ticket closed successfully.');
        $this->redirect('/admin/support');
    }

    public function promotions(): void
    {
        $promotions = Session::get('ent_promotions', []);
        $this->render('admin/promotions', [
            'pageTitle' => 'Marketing Campaigns & Banners',
            'promotions' => $promotions
        ], 'admin');
    }

    public function createPromotion(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        $type = trim($this->input('type', 'Banner'));
        $discount = trim($this->input('discount', ''));

        if (!empty($name)) {
            $promotions = Session::get('ent_promotions', []);
            $newPromo = [
                'id' => count($promotions) + 1,
                'name' => $name,
                'type' => $type,
                'discount' => $discount,
                'active' => 1
            ];
            $promotions[] = $newPromo;
            Session::set('ent_promotions', $promotions);
            $this->logAction("Launched promotion: " . $name);
            Session::flash('success', 'Promotion activated.');
        } else {
            Session::flash('error', 'Promotion name required.');
        }
        $this->redirect('/admin/promotions');
    }

    public function deletePromotion(string $id): void
    {
        $this->validateCsrf();
        $promotions = Session::get('ent_promotions', []);
        foreach ($promotions as $key => $p) {
            if ($p['id'] == (int) $id) {
                $this->logAction("Cancelled promotion campaign: " . $p['name']);
                unset($promotions[$key]);
                break;
            }
        }
        Session::set('ent_promotions', array_values($promotions));
        Session::flash('success', 'Promotion removed.');
        $this->redirect('/admin/promotions');
    }

    public function coupons(): void
    {
        $coupons = Session::get('ent_coupons', []);
        $this->render('admin/coupons', [
            'pageTitle' => 'Discount Vouchers & Coupons',
            'coupons' => $coupons
        ], 'admin');
    }

    public function createCoupon(): void
    {
        $this->validateCsrf();
        $code = strtoupper(trim($this->input('code', '')));
        $type = trim($this->input('type', 'Percent'));
        $value = (float) $this->input('value', 0);
        $limit = (int) $this->input('limit', 100);
        $expiry = trim($this->input('expiry', date('Y-m-d', strtotime('+30 days'))));

        if (!empty($code) && $value > 0) {
            $coupons = Session::get('ent_coupons', []);
            $newCoupon = [
                'id' => count($coupons) + 1,
                'code' => $code,
                'type' => $type,
                'value' => $value,
                'limit' => $limit,
                'used' => 0,
                'expiry' => $expiry
            ];
            $coupons[] = $newCoupon;
            Session::set('ent_coupons', $coupons);
            $this->logAction("Generated discount coupon: " . $code);
            Session::flash('success', 'Coupon code generated successfully.');
        } else {
            Session::flash('error', 'Valid coupon code and value are required.');
        }
        $this->redirect('/admin/coupons');
    }

    public function deleteCoupon(string $id): void
    {
        $this->validateCsrf();
        $coupons = Session::get('ent_coupons', []);
        foreach ($coupons as $key => $c) {
            if ($c['id'] == (int) $id) {
                $this->logAction("Revoked coupon: " . $c['code']);
                unset($coupons[$key]);
                break;
            }
        }
        Session::set('ent_coupons', array_values($coupons));
        Session::flash('success', 'Coupon code revoked.');
        $this->redirect('/admin/coupons');
    }

    public function notifications(): void
    {
        $this->render('admin/notifications', [
            'pageTitle' => 'System Notifications Center'
        ], 'admin');
    }

    public function sendNotification(): void
    {
        $this->validateCsrf();
        $target = $this->input('target', 'all');
        $message = trim($this->input('message', ''));

        if (!empty($message)) {
            $this->logAction("Broadcasted notification to {$target}: " . substr($message, 0, 40) . '...');
            Session::flash('success', 'Notification alert broadcasted successfully to targeting segment.');
        } else {
            Session::flash('error', 'Notification alert message body is required.');
        }
        $this->redirect('/admin/notifications');
    }

    public function auditLogs(): void
    {
        $logs = Session::get('ent_audit_logs', []);
        $this->render('admin/audit_logs', [
            'pageTitle' => 'System Security Audit Trails',
            'logs' => $logs
        ], 'admin');
    }

    public function reports(): void
    {
        $this->render('admin/reports', [
            'pageTitle' => 'Enterprise Reporting Suite'
        ], 'admin');
    }

    public function settings(): void
    {
        $settings = Session::get('ent_settings', []);
        $this->render('admin/settings', [
            'pageTitle' => 'System Configurations',
            'settings' => $settings
        ], 'admin');
    }

    public function updateSettings(): void
    {
        $this->validateCsrf();
        $appName = trim($this->input('app_name', ''));
        $currency = trim($this->input('currency', 'USD'));
        $supportEmail = trim($this->input('support_email', ''));
        $taxRate = (float) $this->input('tax_rate', 0);
        $maintenance = (int) $this->input('maintenance_mode', 0);
        $trackingPrefix = trim($this->input('tracking_prefix', 'SX'));

        if (!empty($appName) && !empty($supportEmail)) {
            $settings = Session::get('ent_settings', []);
            
            // Handle logo file upload
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['logo']['tmp_name'];
                $filename = basename($_FILES['logo']['name']);
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, ['png', 'jpg', 'jpeg', 'svg', 'webp'])) {
                    $uploadDir = APP_PATH . '/../public/uploads/settings/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $newFilename = 'logo_' . time() . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                        $settings['logo_path'] = 'uploads/settings/' . $newFilename;
                    }
                } else {
                    Session::flash('error', 'Logo image must be in PNG, JPG, JPEG, SVG, or WEBP format.');
                    $this->redirect('/admin/settings');
                    return;
                }
            }

            // Handle favicon file upload
            if (isset($_FILES['favicon']) && $_FILES['favicon']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['favicon']['tmp_name'];
                $filename = basename($_FILES['favicon']['name']);
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if (in_array($ext, ['png', 'jpg', 'jpeg', 'ico', 'svg'])) {
                    $uploadDir = APP_PATH . '/../public/uploads/settings/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $newFilename = 'favicon_' . time() . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                        $settings['favicon_path'] = 'uploads/settings/' . $newFilename;
                    }
                } else {
                    Session::flash('error', 'Favicon must be in PNG, JPG, JPEG, ICO, or SVG format.');
                    $this->redirect('/admin/settings');
                    return;
                }
            }

            $settings['app_name'] = $appName;
            $settings['currency'] = $currency;
            $settings['support_email'] = $supportEmail;
            $settings['tax_rate'] = $taxRate;
            $settings['maintenance_mode'] = $maintenance;
            $settings['tracking_prefix'] = $trackingPrefix;

            Session::set('ent_settings', $settings);
            $this->logAction("Updated system-wide settings configurations.");
            Session::flash('success', 'System configurations updated.');
        } else {
            Session::flash('error', 'App Name and Support Email are required.');
        }
        $this->redirect('/admin/settings');
    }

    private function getSidebarFeatures(): array
    {
        return [
            'analytics', 'reports', 'notifications', 'products', 'categories', 'orders',
            'gift_cards', 'invoices', 'quotes', 'warehouses', 'shipments', 'tracking',
            'customers', 'suppliers', 'payments', 'deposits', 'support', 'chat',
            'settings', 'users', 'roles', 'permissions', 'api_keys', 'gateways', 'audit_logs',
            'promotions', 'coupons'
        ];
    }

    private function syncRolesWithFeatures(): array
    {
        $features = $this->getSidebarFeatures();
        $roles = Session::get('ent_roles', []);
        
        if (empty($roles) || !isset($roles['admin']['analytics'])) {
            $roles = [
                'admin' => array_fill_keys($features, 1),
                'manager' => array_merge(array_fill_keys($features, 0), [
                    'analytics' => 1, 'products' => 1, 'categories' => 1, 'orders' => 1,
                    'invoices' => 1, 'quotes' => 1, 'customers' => 1, 'payments' => 1
                ]),
                'support' => array_merge(array_fill_keys($features, 0), [
                    'support' => 1, 'chat' => 1
                ])
            ];
            Session::set('ent_roles', $roles);
        } else {
            foreach ($roles as $roleName => $perms) {
                foreach ($features as $f) {
                    if (!isset($roles[$roleName][$f])) {
                        $roles[$roleName][$f] = ($roleName === 'admin') ? 1 : 0;
                    }
                }
            }
            Session::set('ent_roles', $roles);
        }
        
        return $roles;
    }

    public function roles(): void
    {
        $roles = $this->syncRolesWithFeatures();
        $this->render('admin/roles', [
            'pageTitle' => 'Access Roles Configuration',
            'roles' => $roles
        ], 'admin');
    }

    public function createRole(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        $roleName = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $name)));
        
        if (empty($roleName)) {
            Session::flash('error', 'Valid role name is required.');
            $this->redirect('/admin/roles');
            return;
        }
        
        $roles = Session::get('ent_roles', []);
        if (isset($roles[$roleName])) {
            Session::flash('error', 'Role profile already exists.');
            $this->redirect('/admin/roles');
            return;
        }
        
        $features = $this->getSidebarFeatures();
        $roles[$roleName] = array_fill_keys($features, 0);
        
        Session::set('ent_roles', $roles);
        $this->logAction("Created new operational access role: " . $roleName);
        Session::flash('success', "Role '{$name}' created successfully.");
        $this->redirect('/admin/roles');
    }

    public function deleteRole(string $roleName): void
    {
        $this->validateCsrf();
        if ($roleName === 'admin') {
            Session::flash('error', 'The primary administrator role cannot be deleted.');
            $this->redirect('/admin/roles');
            return;
        }
        
        $roles = Session::get('ent_roles', []);
        if (isset($roles[$roleName])) {
            unset($roles[$roleName]);
            Session::set('ent_roles', $roles);
            $this->logAction("Deleted operational access role: " . $roleName);
            Session::flash('success', "Role '{$roleName}' deleted successfully.");
        } else {
            Session::flash('error', 'Role profile not found.');
        }
        $this->redirect('/admin/roles');
    }

    public function updateRolePermissions(string $id): void
    {
        $this->validateCsrf();
        $roles = Session::get('ent_roles', []);
        if (isset($roles[$id])) {
            $perms = $roles[$id];
            foreach ($perms as $perm => $val) {
                $roles[$id][$perm] = $this->input($perm, 0) ? 1 : 0;
            }
            Session::set('ent_roles', $roles);
            $this->logAction("Admin updated role authorization permissions for: " . $id);
            Session::flash('success', 'Role capability authorization settings updated.');
        } else {
            Session::flash('error', 'Role profile not found.');
        }
        $this->redirect('/admin/roles');
    }

    public function permissions(): void
    {
        $roles = $this->syncRolesWithFeatures();
        $this->render('admin/permissions', [
            'pageTitle' => 'Detailed Capabilities Matrix',
            'roles' => $roles
        ], 'admin');
    }

    public function updateAllPermissions(): void
    {
        $this->validateCsrf();
        $roles = Session::get('ent_roles', []);
        $features = $this->getSidebarFeatures();
        
        $submittedPermissions = $this->input('permissions', []);
        
        foreach ($roles as $roleName => $perms) {
            if ($roleName === 'admin') {
                foreach ($features as $f) {
                    $roles['admin'][$f] = 1;
                }
                continue;
            }
            
            foreach ($features as $f) {
                $roles[$roleName][$f] = isset($submittedPermissions[$roleName][$f]) ? 1 : 0;
            }
        }
        
        Session::set('ent_roles', $roles);
        $this->logAction("Updated bulk system permissions capabilities matrix.");
        Session::flash('success', 'Permissions capabilities matrix updated successfully.');
        $this->redirect('/admin/permissions');
    }

    public function apiKeys(): void
    {
        $keys = Session::get('ent_api_keys', []);
        $this->render('admin/api_keys', [
            'pageTitle' => 'Third-Party Developer Integrations API Keys',
            'keys' => $keys
        ], 'admin');
    }

    public function createApiKey(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        if (!empty($name)) {
            $keys = Session::get('ent_api_keys', []);
            $newToken = 'sk_live_' . strtolower(str_replace(' ', '', $name)) . '_' . bin2hex(random_bytes(10));
            $newKey = [
                'id' => count($keys) + 1,
                'name' => $name,
                'token' => $newToken,
                'status' => 'Active',
                'created_at' => date('Y-m-d')
            ];
            $keys[] = $newKey;
            Session::set('ent_api_keys', $keys);
            $this->logAction("Generated security API Token for: " . $name);
            Session::flash('success', 'API Developer Key generated.');
        } else {
            Session::flash('error', 'API integration name is required.');
        }
        $this->redirect('/admin/api-keys');
    }

    public function toggleApiKey(string $id): void
    {
        $this->validateCsrf();
        $keys = Session::get('ent_api_keys', []);
        foreach ($keys as &$k) {
            if ($k['id'] == (int) $id) {
                $newStatus = $k['status'] === 'Active' ? 'Suspended' : 'Active';
                $k['status'] = $newStatus;
                $this->logAction("Toggled API Integration status for: " . $k['name'] . " to " . $newStatus);
                break;
            }
        }
        Session::set('ent_api_keys', $keys);
        Session::flash('success', 'API Token status toggled.');
        $this->redirect('/admin/api-keys');
    }

    public function deleteApiKey(string $id): void
    {
        $this->validateCsrf();
        $keys = Session::get('ent_api_keys', []);
        foreach ($keys as $key => $k) {
            if ($k['id'] == (int) $id) {
                $this->logAction("Revoked developer API Access Key for: " . $k['name']);
                unset($keys[$key]);
                break;
            }
        }
        Session::set('ent_api_keys', array_values($keys));
        Session::flash('success', 'API Token permanently deleted and access revoked.');
        $this->redirect('/admin/api-keys');
    }

    public function paymentGateways(): void
    {
        $gateways = getPaymentGateways();
        $this->render('admin/payment_gateways', [
            'pageTitle' => 'Payment Gateways Settings',
            'gateways' => $gateways
        ], 'admin');
    }

    public function updatePaymentGateway(string $id): void
    {
        $this->validateCsrf();
        $gateways = getPaymentGateways();
        
        $min = (float) $this->input('min', 0);
        $fee = (float) $this->input('fee', 0);
        $address = trim($this->input('address', ''));
        $status = $this->input('status', 'Inactive');

        foreach ($gateways as &$g) {
            if ($g['id'] == (int) $id) {
                $g['min'] = $min;
                $g['fee'] = $fee;
                $g['address'] = $address;
                $g['status'] = $status;
                $this->logAction("Updated Payment Gateway configurations for: " . $g['name']);
                break;
            }
        }
        savePaymentGateways($gateways);
        Session::flash('success', 'payment gateway configurations saved.');
        $this->redirect('/admin/payment-gateways');
    }

    public function deposits(): void
    {
        $db = Database::getInstance();
        $deposits = $db->query(
            "SELECT d.*, u.name as customer_name, u.email as customer_email 
             FROM deposit_requests d 
             JOIN users u ON d.user_id = u.id 
             ORDER BY d.created_at DESC"
        )->fetchAll();

        $this->render('admin/deposits', [
            'pageTitle' => 'Pending Deposits Approval',
            'deposits' => $deposits
        ], 'admin');
    }

    public function approveDeposit(string $id): void
    {
        $this->validateCsrf();
        $db = Database::getInstance();
        
        $deposit = $db->query("SELECT * FROM deposit_requests WHERE id = ?", [$id])->fetch();
        if ($deposit && $deposit['status'] === 'pending') {
            $userId = $deposit['user_id'];
            $amount = (float) $deposit['amount'];
            $gatewayName = $deposit['gateway_name'];

            // 1. Approve status in deposit_requests
            $db->query("UPDATE deposit_requests SET status = 'approved' WHERE id = ?", [$id]);

            // 2. Add amount to user's wallet balance
            $db->query("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?", [$amount, $userId]);

            // 3. Record completed wallet transaction
            $db->query(
                "INSERT INTO wallet_transactions (user_id, type, amount, description) VALUES (?, 'deposit', ?, ?)",
                [$userId, $amount, "Deposit approved via " . $gatewayName]
            );

            // Fetch user info for logging
            $user = User::find((int) $userId);
            $userEmail = $user ? $user['email'] : 'Unknown';

            $this->logAction("Approved deposit request #{$id} for {$userEmail} of $" . number_format($amount, 2));
            Session::flash('success', 'Deposit request approved. Funds successfully added to user wallet.');
        } else {
            Session::flash('error', 'Deposit request not found or already processed.');
        }

        $this->redirect('/admin/deposits');
    }

    public function rejectDeposit(string $id): void
    {
        $this->validateCsrf();
        $db = Database::getInstance();
        
        $deposit = $db->query("SELECT * FROM deposit_requests WHERE id = ?", [$id])->fetch();
        if ($deposit && $deposit['status'] === 'pending') {
            $userId = $deposit['user_id'];
            $amount = (float) $deposit['amount'];

            // 1. Reject status in deposit_requests
            $db->query("UPDATE deposit_requests SET status = 'rejected' WHERE id = ?", [$id]);

            // Fetch user info for logging
            $user = User::find((int) $userId);
            $userEmail = $user ? $user['email'] : 'Unknown';

            $this->logAction("Rejected deposit request #{$id} for {$userEmail} of $" . number_format($amount, 2));
            Session::flash('success', 'Deposit request rejected.');
        } else {
            Session::flash('error', 'Deposit request not found or already processed.');
        }

        $this->redirect('/admin/deposits');
    }

    public function manageCustomer(string $id): void
    {
        $customer = User::find((int) $id);
        if (!$customer || $customer['role'] !== 'customer') {
            Session::flash('error', 'Customer not found.');
            $this->redirect('/admin/customers');
            return;
        }

        $db = Database::getInstance();
        $transactions = $db->query(
            "SELECT * FROM wallet_transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 20",
            [$id]
        )->fetchAll();

        $this->render('admin/manage_customer', [
            'pageTitle' => 'Manage Customer: ' . $customer['name'],
            'c' => $customer,
            'transactions' => $transactions
        ], 'admin');
    }

    public function updateCustomerProfile(string $id): void
    {
        $this->validateCsrf();
        $customer = User::find((int) $id);
        if (!$customer) {
            Session::flash('error', 'Customer not found.');
            $this->redirect('/admin/customers');
            return;
        }

        $name = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));

        if (!empty($name) && !empty($email)) {
            User::update((int) $id, [
                'name' => $name,
                'email' => $email
            ]);
            $this->logAction("Updated customer profile details for ID #{$id}: {$email}");
            Session::flash('success', 'Customer profile updated successfully.');
        } else {
            Session::flash('error', 'Name and Email are required.');
        }

        $this->redirect('/admin/customers/manage/' . $id);
    }

    public function updateCustomerBalance(string $id): void
    {
        $this->validateCsrf();
        $customer = User::find((int) $id);
        if (!$customer) {
            Session::flash('error', 'Customer not found.');
            $this->redirect('/admin/customers');
            return;
        }

        $amount = (float) $this->input('amount', 0);
        $action = $this->input('balance_action', 'add');

        if ($amount <= 0) {
            Session::flash('error', 'Amount must be greater than zero.');
            $this->redirect('/admin/customers/manage/' . $id);
            return;
        }

        $db = Database::getInstance();
        if ($action === 'add') {
            $db->query("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?", [$amount, $id]);
            $db->query(
                "INSERT INTO wallet_transactions (user_id, type, amount, description) VALUES (?, 'deposit', ?, 'Balance adjustment (Credit) by administrator')",
                [$id, $amount]
            );
            $this->logAction("Credited customer wallet ID #{$id} by $" . number_format($amount, 2));
            Session::flash('success', 'Successfully added $' . number_format($amount, 2) . ' to wallet.');
        } else {
            $db->query("UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?", [$amount, $id]);
            $db->query(
                "INSERT INTO wallet_transactions (user_id, type, amount, description) VALUES (?, 'purchase', ?, 'Balance adjustment (Debit) by administrator')",
                [$id, -$amount]
            );
            $this->logAction("Debited customer wallet ID #{$id} by $" . number_format($amount, 2));
            Session::flash('success', 'Successfully subtracted $' . number_format($amount, 2) . ' from wallet.');
        }

        $this->redirect('/admin/customers/manage/' . $id);
    }

    public function updateCustomerStatus(string $id): void
    {
        $this->validateCsrf();
        $customer = User::find((int) $id);
        if (!$customer) {
            Session::flash('error', 'Customer not found.');
            $this->redirect('/admin/customers');
            return;
        }

        $status = (int) $this->input('status', 0); // 0 = Active, 1 = Suspended
        User::update((int) $id, ['is_suspended' => $status]);

        $actionWord = $status ? 'Suspended' : 'Activated';
        $this->logAction("{$actionWord} customer account ID #{$id}");
        Session::flash('success', "Customer account status set to: " . ($status ? 'Suspended' : 'Active'));

        $this->redirect('/admin/customers/manage/' . $id);
    }

    public function createCustomer(): void
    {
        $this->validateCsrf();
        $name = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));
        $password = trim($this->input('password', ''));

        if (empty($name) || empty($email) || empty($password)) {
            Session::flash('error', 'All registration fields are required.');
            $this->redirect('/admin/customers');
            return;
        }

        if (User::emailExists($email)) {
            Session::flash('error', 'Email is already registered.');
            $this->redirect('/admin/customers');
            return;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        User::create([
            'name' => $name,
            'email' => $email,
            'password_hash' => $hash,
            'role' => 'customer',
            'wallet_balance' => 0.00
        ]);

        $this->logAction("Created new customer account: {$email}");
        Session::flash('success', 'Customer account registered successfully.');
        $this->redirect('/admin/customers');
    }

    public function deleteCustomer(string $id): void
    {
        $this->validateCsrf();
        
        $customer = User::find((int) $id);
        if (!$customer || $customer['role'] !== 'customer') {
            Session::flash('error', 'Customer not found.');
            $this->redirect('/admin/customers');
            return;
        }

        $db = Database::getInstance();

        // 1. Fetch customer order IDs and tracking codes for shipment/tracking JSON cleanup
        $orders = $db->query("SELECT id, tracking_code FROM orders WHERE user_id = ?", [$id])->fetchAll();
        $orderIds = array_map('intval', array_column($orders, 'id'));
        $trackingCodes = array_filter(array_column($orders, 'tracking_code'));

        // 2. Clean up ent_shipments.json
        $shipmentsFile = dirname(dirname(dirname(__DIR__))) . '/database/ent_shipments.json';
        if (file_exists($shipmentsFile)) {
            $content = @file_get_contents($shipmentsFile);
            if ($content !== false) {
                $shipments = json_decode($content, true);
                if (is_array($shipments)) {
                    $filteredShipments = array_filter($shipments, function($s) use ($orderIds, $trackingCodes) {
                        $orderIdMatch = isset($s['order_id']) && in_array((int)$s['order_id'], $orderIds, true);
                        $trackMatch = isset($s['tracking_code']) && in_array($s['tracking_code'], $trackingCodes, true);
                        return !$orderIdMatch && !$trackMatch;
                    });
                    @file_put_contents($shipmentsFile, json_encode(array_values($filteredShipments), JSON_PRETTY_PRINT));
                }
            }
        }

        // 3. Clean up ent_tracking.json
        $trackingFile = dirname(dirname(dirname(__DIR__))) . '/database/ent_tracking.json';
        if (file_exists($trackingFile)) {
            $content = @file_get_contents($trackingFile);
            if ($content !== false) {
                $tracking = json_decode($content, true);
                if (is_array($tracking)) {
                    $filteredTracking = array_filter($tracking, function($t) use ($trackingCodes) {
                        return !isset($t['tracking_code']) || !in_array($t['tracking_code'], $trackingCodes, true);
                    });
                    @file_put_contents($trackingFile, json_encode(array_values($filteredTracking), JSON_PRETTY_PRINT));
                }
            }
        }

        // 4. Clean up ent_support.json
        $supportFile = dirname(dirname(dirname(__DIR__))) . '/database/ent_support.json';
        if (file_exists($supportFile)) {
            $content = @file_get_contents($supportFile);
            if ($content !== false) {
                $tickets = json_decode($content, true);
                if (is_array($tickets)) {
                    $filteredTickets = array_filter($tickets, function($tk) use ($id) {
                        return !isset($tk['user_id']) || (int)$tk['user_id'] !== (int)$id;
                    });
                    @file_put_contents($supportFile, json_encode(array_values($filteredTickets), JSON_PRETTY_PRINT));
                }
            }
        }

        // 5. Delete the user from the DB (foreign keys ON DELETE CASCADE handle orders, transactions, wishlists, etc.)
        $db->query("DELETE FROM users WHERE id = ? AND role = 'customer'", [$id]);

        $this->logAction("Permanently deleted customer account ID #{$id} and all related records.");
        Session::flash('success', 'Customer and all associated records permanently deleted.');
        
        $this->redirect('/admin/customers');
    }

    public function profile(): void
    {
        $user = User::find(Auth::id());
        $this->render('admin/profile', [
            'pageTitle' => 'Admin Profile Settings',
            'user' => $user
        ], 'admin');
    }

    public function updateProfile(): void
    {
        $this->validateCsrf();
        $id = Auth::id();
        $name = trim($this->input('name', ''));
        $email = trim($this->input('email', ''));
        $password = trim($this->input('password', ''));

        if (empty($name) || empty($email)) {
            Session::flash('error', 'Name and Email are required.');
            $this->redirect('/admin/profile');
            return;
        }

        $currentUser = User::find($id);
        if ($email !== $currentUser['email']) {
            if (User::emailExists($email)) {
                Session::flash('error', 'Email already in use.');
                $this->redirect('/admin/profile');
                return;
            }
        }

        $updateData = [
            'name' => $name,
            'email' => $email
        ];

        if (!empty($password)) {
            $updateData['password_hash'] = password_hash($password, PASSWORD_BCRYPT);
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['avatar']['tmp_name'];
            $filename = basename($_FILES['avatar']['name']);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, ['png', 'jpg', 'jpeg'])) {
                $uploadDir = APP_PATH . '/../public/uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $newFilename = uniqid('avatar_', true) . '.' . $ext;
                if (move_uploaded_file($tmpName, $uploadDir . $newFilename)) {
                    $updateData['avatar'] = 'uploads/avatars/' . $newFilename;
                }
            } else {
                Session::flash('error', 'Avatar image must be in PNG, JPG, or JPEG format.');
                $this->redirect('/admin/profile');
                return;
            }
        }

        User::update($id, $updateData);
        $this->logAction("Admin updated profile settings coordinates: {$email}");
        
        $refreshed = User::find($id);
        Session::set('user_name', $refreshed['name']);
        Session::set('user_email', $refreshed['email']);

        Session::flash('success', 'Profile updated successfully.');
        $this->redirect('/admin/profile');
    }

    public function liveChat(): void
    {
        $db = Database::getInstance();

        // Get all users and guests who have sent at least one chat message
        $conversations = $db->query(
            "SELECT MAX(cm.user_id) as user_id,
                    MAX(cm.session_id) as session_id,
                    COALESCE(u.name, CONCAT('Guest (', SUBSTRING(MIN(cm.session_id), 1, 6), ')')) as name,
                    COALESCE(u.email, 'Guest Customer') as email,
                    u.avatar,
                    COUNT(cm.id) as message_count,
                    MAX(cm.created_at) as last_message_at,
                    SUM(CASE WHEN cm.is_read = 0 AND cm.sender = 'user' THEN 1 ELSE 0 END) as unread_count
             FROM chat_messages cm
             LEFT JOIN users u ON cm.user_id = u.id
             GROUP BY COALESCE(cm.user_id, cm.session_id), u.name, u.email, u.avatar
             ORDER BY last_message_at DESC"
        )->fetchAll();

        // If a specific user or guest is selected, load their messages
        $selectedUserId = isset($_GET['user']) && $_GET['user'] !== '' ? (int) $_GET['user'] : null;
        $selectedGuestId = isset($_GET['guest']) && $_GET['guest'] !== '' ? $_GET['guest'] : null;
        $selectedMessages = [];
        $selectedUser = null;

        if ($selectedUserId) {
            $selectedUser = User::find($selectedUserId);
            $selectedMessages = $db->query(
                "SELECT * FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC",
                [$selectedUserId]
            )->fetchAll();

            // Mark messages from this user as read
            $db->query(
                "UPDATE chat_messages SET is_read = 1 WHERE user_id = ? AND sender = 'user' AND is_read = 0",
                [$selectedUserId]
            );
        } elseif ($selectedGuestId) {
            $selectedUser = [
                'id' => null,
                'session_id' => $selectedGuestId,
                'name' => 'Guest (' . substr($selectedGuestId, 0, 6) . ')',
                'email' => 'Guest Customer',
                'avatar' => null
            ];
            $selectedMessages = $db->query(
                "SELECT * FROM chat_messages WHERE session_id = ? AND user_id IS NULL ORDER BY created_at ASC",
                [$selectedGuestId]
            )->fetchAll();

            // Mark messages from this guest as read
            $db->query(
                "UPDATE chat_messages SET is_read = 1 WHERE session_id = ? AND user_id IS NULL AND sender = 'user' AND is_read = 0",
                [$selectedGuestId]
            );
        }

        if (isset($_GET['ajax']) || $this->isAjax()) {
            $this->json([
                'conversations' => $conversations,
                'selectedUserId' => $selectedUserId,
                'selectedGuestId' => $selectedGuestId,
                'selectedUser' => $selectedUser,
                'selectedMessages' => $selectedMessages,
            ]);
            return;
        }

        $this->render('admin/live_chat', [
            'pageTitle' => 'Live Chat Messages',
            'conversations' => $conversations,
            'selectedUserId' => $selectedUserId,
            'selectedGuestId' => $selectedGuestId,
            'selectedUser' => $selectedUser,
            'selectedMessages' => $selectedMessages,
        ], 'admin');
    }

    public function liveChatReply(string $identifier): void
    {
        $this->validateCsrf();
        $message = trim($this->input('message', ''));
        $isUserId = is_numeric($identifier);

        if (empty($message)) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Message cannot be empty.'], 400);
                return;
            }
            Session::flash('error', 'Message cannot be empty.');
            $redirectUrl = $isUserId ? '/admin/live-chat?user=' . $identifier : '/admin/live-chat?guest=' . $identifier;
            $this->redirect($redirectUrl);
            return;
        }

        $db = Database::getInstance();
        if ($isUserId) {
            $db->query(
                "INSERT INTO chat_messages (user_id, sender, message, is_read, created_at) VALUES (?, 'admin', ?, 0, NOW())",
                [(int)$identifier, $message]
            );
            $user = User::find((int) $identifier);
            $this->logAction("Sent live chat reply to customer: " . ($user ? $user['email'] : "ID#{$identifier}"));
        } else {
            $db->query(
                "INSERT INTO chat_messages (session_id, sender, message, is_read, created_at) VALUES (?, 'admin', ?, 0, NOW())",
                [$identifier, $message]
            );
            $this->logAction("Sent live chat reply to guest: " . substr($identifier, 0, 6));
        }

        if ($this->isAjax()) {
            $this->json(['success' => true]);
            return;
        }

        $redirectUrl = $isUserId ? '/admin/live-chat?user=' . $identifier : '/admin/live-chat?guest=' . $identifier;
        $this->redirect($redirectUrl);
    }
}

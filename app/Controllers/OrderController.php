<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function myOrders(): void
    {
        $orders = Order::getByUser(Auth::id());
        $this->render('orders/index', [
            'pageTitle' => 'My Orders — ShopX Global',
            'orders' => $orders,
        ]);
    }
    
    public function show(string $id): void
    {
        $order = Order::getWithItems((int) $id);
        if (!$order || ($order['user_id'] != Auth::id() && !Auth::isAdmin())) {
            $this->redirect('/my-orders');
            return;
        }
        $this->render('orders/show', ['pageTitle' => 'Order #' . $id, 'order' => $order]);
    }
    
    public function track(): void
    {
        $query = trim($this->query('code', $this->input('code', $this->query('id', $this->input('id', '')))));
        $result = null;
        $trackingLogs = [];
        $shipmentInfo = null;
        $searched = false;
        
        if (!empty($query)) {
            $searched = true;
            $result = Order::findByTracking($query);
            if (!$result && is_numeric($query)) {
                $result = Order::find((int) $query);
            }
            
            $isManualShipment = false;
            if (!$result) {
                // Check session shipments (manual shipments)
                $sessionShipments = \App\Core\Session::get('ent_shipments', []);
                foreach ($sessionShipments as $s) {
                    if (strcasecmp($s['tracking_code'], $query) === 0) {
                        $shipmentInfo = $s;
                        $isManualShipment = true;
                        $trackingCode = $s['tracking_code'];
                        
                        // Get tracking logs from session for this manual shipment
                        $sessionTracking = \App\Core\Session::get('ent_tracking', []);
                        foreach ($sessionTracking as $t) {
                            if (strcasecmp($t['tracking_code'], $trackingCode) === 0) {
                                $trackingLogs[] = $t;
                            }
                        }
                        
                        // Extract timestamps from tracking logs if available
                        $oldestTime = null;
                        $newestTime = null;
                        foreach ($trackingLogs as $log) {
                            $ts = strtotime($log['timestamp']);
                            if ($oldestTime === null || $ts < $oldestTime) {
                                $oldestTime = $ts;
                            }
                            if ($newestTime === null || $ts > $newestTime) {
                                $newestTime = $ts;
                            }
                        }
                        
                        $createdAtVal = $oldestTime ? date('Y-m-d H:i:s', $oldestTime) : date('Y-m-d H:i:s');
                        $updatedAtVal = $newestTime ? date('Y-m-d H:i:s', $newestTime) : date('Y-m-d H:i:s');
                        
                        $result = [
                            'id' => $s['id'],
                            'status' => strtolower($s['status']),
                            'created_at' => $createdAtVal,
                            'updated_at' => $updatedAtVal,
                            'shipping_address' => $s['destination'],
                            'tracking_code' => $trackingCode,
                            'is_manual' => true
                        ];
                        break;
                    }
                }
            }
            
            if ($result) {
                if (!$isManualShipment) {
                    $trackingCode = $result['tracking_code'] ?: ('SX-' . str_pad($result['id'], 6, '0', STR_PAD_LEFT));
                    
                    // Get shipment info from session if exists (prioritize tracking code first)
                    $sessionShipments = \App\Core\Session::get('ent_shipments', []);
                    foreach ($sessionShipments as $s) {
                        if (strcasecmp($s['tracking_code'], $trackingCode) === 0) {
                            $shipmentInfo = $s;
                            break;
                        }
                    }
                    if (!$shipmentInfo) {
                        foreach ($sessionShipments as $s) {
                            if ($s['order_id'] == $result['id']) {
                                $shipmentInfo = $s;
                                break;
                            }
                        }
                    }
                    
                    // Get tracking logs from session if exists
                    $sessionTracking = \App\Core\Session::get('ent_tracking', []);
                    foreach ($sessionTracking as $t) {
                        if (strcasecmp($t['tracking_code'], $trackingCode) === 0) {
                            $trackingLogs[] = $t;
                        }
                    }
                    
                    // Fallback shipment details
                    if (!$shipmentInfo) {
                        $shipmentInfo = [
                            'carrier' => 'ShopX Global Logistics',
                            'tracking_code' => $trackingCode,
                            'status' => ucfirst($result['status']),
                            'origin' => 'Tokyo East Depot',
                            'destination' => $result['shipping_address']
                        ];
                    }
                    
                    // Fallback dynamic log generation matching current status
                    if (empty($trackingLogs)) {
                        $createdAt = strtotime($result['created_at']);
                        $updatedAt = strtotime($result['updated_at']);
                        
                        $trackingLogs[] = [
                            'timestamp' => date('Y-m-d H:i', $createdAt),
                            'location' => 'Tokyo East Depot',
                            'status' => 'Order Placed & Confirmed'
                        ];
                        
                        if (in_array($result['status'], ['processing', 'shipped', 'delivered'])) {
                            $trackingLogs[] = [
                                'timestamp' => date('Y-m-d H:i', $createdAt + 7200),
                                'location' => 'Tokyo East Depot',
                                'status' => 'Processing at Fulfillment Center'
                            ];
                        }
                        
                        if (in_array($result['status'], ['shipped', 'delivered'])) {
                            $trackingLogs[] = [
                                'timestamp' => date('Y-m-d H:i', $createdAt + 21600),
                                'location' => 'Tokyo Narita Airport',
                                'status' => 'Departed Port of Origin - In Transit'
                            ];
                        }
                        
                        if ($result['status'] === 'delivered') {
                            $trackingLogs[] = [
                                'timestamp' => date('Y-m-d H:i', $updatedAt),
                                'location' => $result['shipping_address'],
                                'status' => 'Delivered to Doorstep (Signed)'
                            ];
                        }
                        
                        if ($result['status'] === 'cancelled') {
                            $trackingLogs[] = [
                                'timestamp' => date('Y-m-d H:i', $updatedAt),
                                'location' => 'System',
                                'status' => 'Order Cancelled'
                            ];
                        }
                    }
                } else {
                    // It is a manual shipment, make sure we have a fallback log if empty
                    if (empty($trackingLogs)) {
                        $trackingLogs[] = [
                            'timestamp' => $result['created_at'],
                            'location' => $shipmentInfo['origin'] ?? 'Origin Facility',
                            'status' => 'Shipment status: ' . ($shipmentInfo['status'] ?? 'Pending')
                        ];
                    }
                }
                
                // Sort newest first
                usort($trackingLogs, function($a, $b) {
                    return strcmp($b['timestamp'], $a['timestamp']);
                });
            }
        }
        
        $orders = Auth::check() ? Order::getByUser(Auth::id()) : [];
        $totalOrders = count($orders);
        $inTransit = count(array_filter($orders, fn($o) => in_array($o['status'], ['shipped', 'processing'])));
        $delivered = count(array_filter($orders, fn($o) => $o['status'] === 'delivered'));
        
        $this->render('orders/track', [
            'pageTitle' => 'Track Order — ShopX Global',
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'inTransit' => $inTransit,
            'delivered' => $delivered,
            'searchResult' => $result,
            'shipmentInfo' => $shipmentInfo,
            'trackingLogs' => $trackingLogs,
            'searched' => $searched,
            'searchQuery' => $query
        ]);
    }
    
    public function trackSearch(): void
    {
        $this->validateCsrf();
        $query = trim($this->input('tracking_code', ''));
        $this->redirect('/track-order?code=' . urlencode($query));
    }
    
    public function invoice(string $code): void
    {
        $order = Order::findByTracking($code);
        if (!$order) {
            $order = Order::find((int) $code);
        }
        
        $invoice = null;
        
        if ($order) {
            $orderWithItems = Order::getWithItems($order['id']);
            
            // Get customer name and email
            $db = \App\Core\Database::getInstance();
            $customer = $db->query("SELECT name, email FROM users WHERE id = ?", [$order['user_id']])->fetch();
            
            $invoice = [
                'type' => 'Store Order',
                'invoice_num' => 'INV-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT),
                'date' => date('M j, Y', strtotime($order['created_at'])),
                'billed_to' => [
                    'name' => $customer['name'] ?? 'Valued Customer',
                    'email' => $customer['email'] ?? '',
                    'address' => $order['shipping_address']
                ],
                'tracking_code' => $order['tracking_code'],
                'payment_method' => strtoupper($order['payment_method']),
                'carrier' => 'ShopX Global Courier',
                'items' => array_map(function($item) {
                    return [
                        'name' => $item['title'],
                        'qty' => $item['qty'],
                        'price' => $item['price_at_purchase'],
                        'total' => $item['price_at_purchase'] * $item['qty']
                    ];
                }, $orderWithItems['items'] ?? []),
                'total' => $order['total']
            ];
        } else {
            // Check session shipments
            $sessionShipments = \App\Core\Session::get('ent_shipments', []);
            foreach ($sessionShipments as $s) {
                if ($s['tracking_code'] === $code) {
                    $invoice = [
                        'type' => 'Manual Shipment',
                        'invoice_num' => 'INV-MS-' . str_pad($s['id'], 4, '0', STR_PAD_LEFT),
                        'date' => date('M j, Y'),
                        'billed_to' => [
                            'name' => $s['receiver_name'] ?? 'Recipient',
                            'email' => $s['receiver_contact'] ?? '',
                            'address' => $s['destination']
                        ],
                        'tracking_code' => $s['tracking_code'],
                        'payment_method' => 'PREPAID',
                        'carrier' => $s['carrier'] ?? 'ShopX Logistics',
                        'items' => [
                            [
                                'name' => ($s['product_type'] ?? 'Goods') . ' (' . ($s['product_weight'] ?? 'N/A') . ')',
                                'qty' => 1,
                                'price' => $s['amount'] ?? 0.00,
                                'total' => $s['amount'] ?? 0.00
                            ]
                        ],
                        'total' => $s['amount'] ?? 0.00
                    ];
                    break;
                }
            }
        }
        
        if (!$invoice) {
            $this->redirect('/track-order');
            return;
        }
        
        $this->renderPartial('orders/invoice', [
            'pageTitle' => 'Invoice ' . $invoice['invoice_num'],
            'invoice' => $invoice
        ]);
    }
}

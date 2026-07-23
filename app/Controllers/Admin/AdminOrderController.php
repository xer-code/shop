<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index(): void
    {
        $db = \App\Core\Database::getInstance();
        $orders = $db->query(
            "SELECT o.*, u.name as customer_name 
             FROM orders o 
             JOIN users u ON o.user_id = u.id 
             ORDER BY o.created_at DESC"
        )->fetchAll();

        $this->render('admin/orders/index', [
            'pageTitle' => 'Order Management Console',
            'orders' => $orders,
        ], 'admin');
    }

    public function show(string $id): void
    {
        $order = Order::getWithItems((int) $id);
        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect('/admin/orders');
            return;
        }

        // Get customer details
        $db = \App\Core\Database::getInstance();
        $customer = $db->query("SELECT * FROM users WHERE id = ?", [$order['user_id']])->fetch();
        $order['customer_name'] = $customer['name'] ?? 'Unknown';
        $order['customer_email'] = $customer['email'] ?? '';

        $this->render('admin/orders/show', [
            'pageTitle' => 'Manage Order #' . $id,
            'order' => $order,
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $this->validateCsrf();
        $order = Order::find((int) $id);
        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect('/admin/orders');
            return;
        }

        $status = $this->input('status', '');
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        if (!in_array($status, $validStatuses)) {
            Session::flash('error', 'Invalid status selection.');
            $this->redirect('/admin/orders/' . $id);
            return;
        }

        Order::update((int) $id, ['status' => $status]);
        Session::flash('success', 'Order status updated successfully.');
        $this->redirect('/admin/orders/' . $id);
    }

    public function delete(string $id): void
    {
        $this->validateCsrf();
        $order = Order::find((int) $id);
        if (!$order) {
            Session::flash('error', 'Order not found.');
            $this->redirect('/admin/orders');
            return;
        }
        Order::delete((int) $id);
        Session::flash('success', 'Order deleted successfully.');
        $this->redirect('/admin/orders');
    }
}

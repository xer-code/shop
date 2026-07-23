<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\GiftCard;

class AdminController extends Controller
{
    /**
     * Show the separate admin login form
     */
    public function loginForm(): void
    {
        // Check if already logged in as admin
        if (Auth::check() && Auth::isAdmin()) {
            $this->redirect('/admin/dashboard');
            return;
        }
        $this->render('admin/login', ['pageTitle' => 'Admin Panel Login'], 'admin_login_layout');
    }

    /**
     * Handle the admin login submission
     */
    public function login(): void
    {
        $this->validateCsrf();
        $email = $this->input('email', '');
        $password = $this->input('password', '');

        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please fill in all fields.');
            $this->redirect('/admin/login');
            return;
        }

        if (Auth::attempt($email, $password)) {
            if (Auth::isAdmin()) {
                Session::flash('success', 'Admin session started.');
                $this->redirect('/admin/dashboard');
            } else {
                Auth::logout();
                Session::flash('error', 'Access denied. Administrator privileges required.');
                $this->redirect('/admin/login');
            }
        } else {
            Session::flash('error', 'Invalid admin credentials.');
            $this->redirect('/admin/login');
        }
    }

    /**
     * Render the admin dashboard
     */
    public function dashboard(): void
    {
        $orderStats = Order::getStats();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        
        // Fetch recent orders
        $db = \App\Core\Database::getInstance();
        $recentOrders = $db->query(
            "SELECT o.*, u.name as customer_name 
             FROM orders o 
             JOIN users u ON o.user_id = u.id 
             ORDER BY o.created_at DESC LIMIT 5"
        )->fetchAll();

        // Fetch low stock items
        $lowStockProducts = Product::where(['is_active' => 1], 'stock', 'ASC', 5);

        $this->render('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard — ShopX Global',
            'stats' => $orderStats,
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
        ], 'admin');
    }
}

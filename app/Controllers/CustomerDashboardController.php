<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Models\User;
use App\Models\Order;
use App\Models\WalletTransaction;
use App\Models\Wishlist;
use App\Models\GiftCard;

class CustomerDashboardController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'Please log in to access your dashboard.');
            $this->redirect('/login');
            return;
        }

        $section = $_GET['section'] ?? 'overview';
        $userId = Auth::id();
        $db = Database::getInstance();

        $data = [
            'dashSection' => $section,
        ];

        switch ($section) {
            case 'orders':
                $data['pageTitle'] = 'My Orders';
                $data['orders'] = Order::getByUser($userId);
                $viewFile = 'dashboard/orders';
                break;

            case 'tracking':
                $data['pageTitle'] = 'Order Tracking';
                $orders = Order::getByUser($userId);
                $data['orders'] = array_filter($orders, fn($o) => in_array($o['status'], ['processing', 'shipped']));
                $viewFile = 'dashboard/tracking';
                break;

            case 'wallet':
                $data['pageTitle'] = 'My Wallet';
                $data['balance'] = Auth::wallet();
                $data['transactions'] = WalletTransaction::getByUser($userId, 30);
                $data['pendingDeposits'] = $db->query(
                    "SELECT * FROM deposit_requests WHERE user_id = ? AND status = 'pending' ORDER BY created_at DESC",
                    [$userId]
                )->fetchAll();
                $viewFile = 'dashboard/wallet';
                break;

            case 'payments':
                $data['pageTitle'] = 'Payment History';
                $data['transactions'] = WalletTransaction::getByUser($userId, 50);
                $viewFile = 'dashboard/payments';
                break;

            case 'quotes':
                $data['pageTitle'] = 'My Quotes';
                $data['quotes'] = Session::get('customer_quotes_' . $userId, []);
                $viewFile = 'dashboard/quotes';
                break;

            case 'giftcards':
                $data['pageTitle'] = 'Gift Cards';
                $data['purchased'] = $db->query(
                    "SELECT * FROM gift_cards WHERE purchased_by = ? ORDER BY created_at DESC",
                    [$userId]
                )->fetchAll();
                $data['redeemed'] = $db->query(
                    "SELECT * FROM gift_cards WHERE redeemed_by = ? ORDER BY created_at DESC",
                    [$userId]
                )->fetchAll();
                $viewFile = 'dashboard/giftcards';
                break;

            case 'messages':
                $data['pageTitle'] = 'Messages';
                $data['messages'] = $db->query(
                    "SELECT * FROM chat_messages WHERE user_id = ? ORDER BY created_at DESC LIMIT 30",
                    [$userId]
                )->fetchAll();
                $viewFile = 'dashboard/messages';
                break;

            case 'notifications':
                $data['pageTitle'] = 'Notifications';
                $data['notifications'] = Session::get('customer_notifications_' . $userId, $this->getDefaultNotifications());
                $viewFile = 'dashboard/notifications';
                break;

            case 'saved':
                $data['pageTitle'] = 'Saved Products';
                $data['products'] = Wishlist::getUserWishlist($userId);
                $viewFile = 'dashboard/saved';
                break;

            case 'support':
                $data['pageTitle'] = 'Support Tickets';
                $data['tickets'] = Session::get('customer_tickets_' . $userId, []);
                $viewFile = 'dashboard/support';
                break;

            default: // overview
                $data['pageTitle'] = 'My Dashboard';
                $orders = Order::getByUser($userId);
                $data['totalOrders'] = count($orders);
                $data['pendingOrders'] = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
                $data['shippedOrders'] = count(array_filter($orders, fn($o) => in_array($o['status'], ['shipped', 'processing'])));
                $data['deliveredOrders'] = count(array_filter($orders, fn($o) => $o['status'] === 'delivered'));
                $data['totalSpent'] = array_sum(array_column($orders, 'total'));
                $data['balance'] = Auth::wallet();
                $data['savedCount'] = count(Wishlist::getUserWishlist($userId));
                $data['recentOrders'] = array_slice($orders, 0, 5);
                $data['recentTransactions'] = WalletTransaction::getByUser($userId, 5);
                $viewFile = 'dashboard/overview';
                break;
        }

        $this->render($viewFile, $data, 'dashboard');
    }

    public function requestQuote(): void
    {
        $this->validateCsrf();
        $userId = Auth::id();
        $product = trim($this->input('product', ''));
        $quantity = (int) $this->input('quantity', 1);
        $notes = trim($this->input('notes', ''));

        if (empty($product)) {
            Session::flash('error', 'Product name is required for a quote request.');
            $this->redirect('/dashboard?section=quotes');
            return;
        }

        $quotes = Session::get('customer_quotes_' . $userId, []);
        $quotes[] = [
            'id' => count($quotes) + 1,
            'product' => $product,
            'quantity' => $quantity,
            'notes' => $notes,
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        Session::set('customer_quotes_' . $userId, $quotes);
        Session::flash('success', 'Quote request submitted successfully. We will respond shortly.');
        $this->redirect('/dashboard?section=quotes');
    }

    public function createSupportTicket(): void
    {
        $this->validateCsrf();
        $userId = Auth::id();
        $subject = trim($this->input('subject', ''));
        $message = trim($this->input('message', ''));
        $priority = $this->input('priority', 'Medium');

        if (empty($subject) || empty($message)) {
            Session::flash('error', 'Subject and message are required.');
            $this->redirect('/dashboard?section=support');
            return;
        }

        $tickets = Session::get('customer_tickets_' . $userId, []);
        $tickets[] = [
            'id' => count($tickets) + 1,
            'subject' => $subject,
            'priority' => $priority,
            'status' => 'Open',
            'messages' => [
                ['from' => 'customer', 'text' => $message, 'time' => date('Y-m-d H:i:s')]
            ],
            'created_at' => date('Y-m-d H:i:s'),
        ];
        Session::set('customer_tickets_' . $userId, $tickets);
        Session::flash('success', 'Support ticket created. Our team will respond within 24 hours.');
        $this->redirect('/dashboard?section=support');
    }

    public function replySupportTicket(string $id): void
    {
        $this->validateCsrf();
        $userId = Auth::id();
        $message = trim($this->input('message', ''));

        if (empty($message)) {
            Session::flash('error', 'Message cannot be empty.');
            $this->redirect('/dashboard?section=support');
            return;
        }

        $tickets = Session::get('customer_tickets_' . $userId, []);
        foreach ($tickets as &$ticket) {
            if ($ticket['id'] == (int) $id) {
                $ticket['messages'][] = ['from' => 'customer', 'text' => $message, 'time' => date('Y-m-d H:i:s')];
                break;
            }
        }
        Session::set('customer_tickets_' . $userId, $tickets);
        Session::flash('success', 'Reply sent.');
        $this->redirect('/dashboard?section=support');
    }

    public function redeemGiftCard(): void
    {
        $this->validateCsrf();
        $code = strtoupper(trim($this->input('code', '')));

        $gc = GiftCard::findByCode($code);
        if (!$gc || $gc['status'] !== 'active' || $gc['remaining_value'] <= 0) {
            Session::flash('error', 'Invalid or already used gift card code.');
            $this->redirect('/dashboard?section=giftcards');
            return;
        }

        $amount = $gc['remaining_value'];
        User::updateWallet(Auth::id(), $amount);
        GiftCard::update($gc['id'], [
            'remaining_value' => 0,
            'redeemed_by' => Auth::id(),
            'status' => 'used',
        ]);
        WalletTransaction::create([
            'user_id' => Auth::id(), 'type' => 'gift_card',
            'amount' => $amount, 'description' => "Gift card redeemed: $code",
        ]);
        Auth::refreshWallet();

        Session::flash('success', "Gift card redeemed! " . formatPrice($amount) . " added to your wallet.");
        $this->redirect('/dashboard?section=giftcards');
    }

    private function getDefaultNotifications(): array
    {
        return [
            ['id' => 1, 'type' => 'info', 'title' => 'Welcome to ShopX Global!', 'message' => 'Your account is set up and ready. Explore our marketplace for exclusive deals.', 'time' => date('Y-m-d H:i:s', strtotime('-1 day')), 'read' => false],
            ['id' => 2, 'type' => 'promo', 'title' => '🎉 Limited Time: Free Shipping', 'message' => 'Enjoy free shipping on orders above $50 this weekend only!', 'time' => date('Y-m-d H:i:s', strtotime('-2 days')), 'read' => false],
            ['id' => 3, 'type' => 'system', 'title' => 'Security Update', 'message' => 'We recommend updating your password regularly for account security.', 'time' => date('Y-m-d H:i:s', strtotime('-5 days')), 'read' => true],
        ];
    }
}

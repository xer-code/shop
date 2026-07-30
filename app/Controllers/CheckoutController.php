<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Database;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\GiftCard;

class CheckoutController extends Controller
{
    public function index(): void
    {
        $cartId = Cart::getOrCreate(Auth::id(), session_id());
        $items = Cart::getItems($cartId);
        $total = Cart::getTotal($cartId);
        
        if (empty($items)) {
            Session::flash('error', 'Your cart is empty.');
            $this->redirect('/shop');
            return;
        }
        
        Auth::refreshWallet();
        
        $this->render('checkout/index', [
            'pageTitle' => 'Checkout — ShopX Global',
            'items' => $items,
            'total' => $total,
            'wallet' => Auth::wallet(),
        ]);
    }
    
    public function process(): void
    {
        $this->validateCsrf();
        $db = Database::getInstance();
        
        $cartId = Cart::getOrCreate(Auth::id(), session_id());
        $items = Cart::getItems($cartId);
        $total = Cart::getTotal($cartId);
        
        if (empty($items)) {
            Session::flash('error', 'Your cart is empty.');
            $this->redirect('/shop');
            return;
        }
        
        $address = $this->input('address', '');
        $city = $this->input('city', '');
        $country = $this->input('country', '');
        $paymentMethod = $this->input('payment_method', 'wallet');
        $giftCardCode = $this->input('gift_card_code', '');
        
        if (empty($address) || empty($city) || empty($country)) {
            Session::flash('error', 'Please fill in your shipping address.');
            $this->redirect('/checkout');
            return;
        }
        
        $shippingAddress = "$address, $city, $country";
        
        try {
            $db->beginTransaction();
            
            // Handle payment
            if ($paymentMethod === 'wallet') {
                Auth::refreshWallet();
                if (Auth::wallet() < $total) {
                    throw new \Exception('Insufficient wallet balance.');
                }
                User::updateWallet(Auth::id(), -$total);
                WalletTransaction::create([
                    'user_id' => Auth::id(),
                    'type' => 'purchase',
                    'amount' => -$total,
                    'description' => 'Order payment',
                ]);
            } elseif ($paymentMethod === 'gift_card' && !empty($giftCardCode)) {
                $gc = GiftCard::findByCode($giftCardCode);
                if (!$gc || $gc['status'] !== 'active' || $gc['remaining_value'] < $total) {
                    throw new \Exception('Invalid gift card or insufficient balance.');
                }
                GiftCard::update($gc['id'], [
                    'remaining_value' => $gc['remaining_value'] - $total,
                    'redeemed_by' => Auth::id(),
                    'status' => ($gc['remaining_value'] - $total <= 0) ? 'used' : 'active',
                ]);
            }
            
            // Create order
            $trackingCode = 'SX-' . strtoupper(substr(md5(uniqid()), 0, 10));
            $orderId = Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
                'total' => $total,
                'tracking_code' => $trackingCode,
                'shipping_address' => $shippingAddress,
                'payment_method' => $paymentMethod,
            ]);
            
            // Create order items
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'price_at_purchase' => $item['price'],
                ]);
            }
            
            // Clear cart
            Cart::clearCart($cartId);
            Session::set('cart_count', 0);
            Auth::refreshWallet();
            
            $db->commit();
            
            // Dispatch order confirmation email
            if (Auth::email()) {
                \App\Core\Mailer::sendTriggerEmail('order_confirmation', Auth::email(), [
                    'name' => Auth::name(),
                    'order_id' => $orderId,
                    'total' => formatPrice($total),
                    'tracking_code' => $trackingCode,
                    'shipping_address' => $shippingAddress
                ], Auth::name());
            }
            
            Session::flash('success', 'Order placed successfully! Tracking: ' . $trackingCode);
            $this->redirect('/checkout/success/' . $orderId);
        } catch (\Exception $e) {
            $db->rollback();
            Session::flash('error', $e->getMessage());
            $this->redirect('/checkout');
        }
    }
    
    public function success(string $id): void
    {
        $order = Order::getWithItems((int) $id);
        if (!$order || $order['user_id'] != Auth::id()) {
            $this->redirect('/my-orders');
            return;
        }
        
        $this->render('checkout/success', [
            'pageTitle' => 'Order Confirmed — ShopX Global',
            'order' => $order,
        ]);
    }
}

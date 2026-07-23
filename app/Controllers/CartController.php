<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Cart as CartModel;
use App\Models\CartItem;
use App\Core\Database;

class CartController extends Controller
{
    public function index(): void
    {
        $cartId = CartModel::getOrCreate(Auth::id(), session_id());
        $items = CartModel::getItems($cartId);
        $total = CartModel::getTotal($cartId);
        
        $this->render('cart/index', [
            'pageTitle' => 'Your Cart — ShopX Global',
            'items' => $items,
            'total' => $total,
        ]);
    }
    
    public function add(): void
    {
        $this->validateCsrf();
        $productId = (int) $this->input('product_id');
        $qty = max(1, (int) $this->input('qty', 1));
        
        $cartId = CartModel::getOrCreate(Auth::id(), session_id());
        $db = Database::getInstance();
        
        $existing = $db->query("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?", [$cartId, $productId])->fetch();
        if ($existing) {
            $db->query("UPDATE cart_items SET qty = qty + ? WHERE id = ?", [$qty, $existing['id']]);
        } else {
            CartItem::create(['cart_id' => $cartId, 'product_id' => $productId, 'qty' => $qty]);
        }
        
        Session::set('cart_count', CartModel::getItemCount($cartId));
        
        if ($this->isAjax()) {
            $this->json(['success' => true, 'cart_count' => CartModel::getItemCount($cartId)]);
        } else {
            Session::flash('success', 'Item added to cart!');
            $this->redirect('/cart');
        }
    }
    
    public function update(): void
    {
        $this->validateCsrf();
        $itemId = (int) $this->input('item_id');
        $qty = max(1, (int) $this->input('qty', 1));
        
        CartItem::update($itemId, ['qty' => $qty]);
        
        $cartId = CartModel::getOrCreate(Auth::id(), session_id());
        Session::set('cart_count', CartModel::getItemCount($cartId));
        
        if ($this->isAjax()) {
            $this->json(['success' => true, 'cart_count' => CartModel::getItemCount($cartId), 'total' => CartModel::getTotal($cartId)]);
        } else {
            $this->redirect('/cart');
        }
    }
    
    public function remove(): void
    {
        $this->validateCsrf();
        $itemId = (int) $this->input('item_id');
        CartItem::delete($itemId);
        
        $cartId = CartModel::getOrCreate(Auth::id(), session_id());
        Session::set('cart_count', CartModel::getItemCount($cartId));
        
        if ($this->isAjax()) {
            $this->json(['success' => true, 'cart_count' => CartModel::getItemCount($cartId)]);
        } else {
            Session::flash('success', 'Item removed from cart.');
            $this->redirect('/cart');
        }
    }
}

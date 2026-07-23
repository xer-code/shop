<?php
namespace App\Models;
use App\Core\Model;

class Cart extends Model
{
    protected static string $table = 'carts';
    
    public static function getOrCreate(?int $userId, string $sessionId): int
    {
        $db = \App\Core\Database::getInstance();
        if ($userId) {
            $cart = self::whereFirst(['user_id' => $userId]);
            if ($cart) return $cart['id'];
            return self::create(['user_id' => $userId, 'session_id' => $sessionId]);
        }
        $cart = self::whereFirst(['session_id' => $sessionId]);
        if ($cart) return $cart['id'];
        return self::create(['session_id' => $sessionId]);
    }
    
    public static function mergeGuestCart(string $sessionId, int $userId): void
    {
        $db = \App\Core\Database::getInstance();
        $guestCart = self::whereFirst(['session_id' => $sessionId]);
        if (!$guestCart) return;
        
        $userCart = self::whereFirst(['user_id' => $userId]);
        if (!$userCart) {
            $db->query("UPDATE carts SET user_id = ? WHERE id = ?", [$userId, $guestCart['id']]);
            return;
        }
        
        // Merge items
        $guestItems = $db->query("SELECT * FROM cart_items WHERE cart_id = ?", [$guestCart['id']])->fetchAll();
        foreach ($guestItems as $item) {
            $existing = $db->query("SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ?", [$userCart['id'], $item['product_id']])->fetch();
            if ($existing) {
                $db->query("UPDATE cart_items SET qty = qty + ? WHERE id = ?", [$item['qty'], $existing['id']]);
            } else {
                $db->query("INSERT INTO cart_items (cart_id, product_id, qty) VALUES (?, ?, ?)", [$userCart['id'], $item['product_id'], $item['qty']]);
            }
        }
        $db->query("DELETE FROM carts WHERE id = ?", [$guestCart['id']]);
    }
    
    public static function getItems(int $cartId): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->query(
            "SELECT ci.*, p.title, p.price, p.original_price, p.image_url, p.stock 
             FROM cart_items ci 
             JOIN products p ON ci.product_id = p.id 
             WHERE ci.cart_id = ?",
            [$cartId]
        )->fetchAll();
    }
    
    public static function getItemCount(int $cartId): int
    {
        $db = \App\Core\Database::getInstance();
        $stmt = $db->query("SELECT COALESCE(SUM(qty), 0) as count FROM cart_items WHERE cart_id = ?", [$cartId]);
        return (int) $stmt->fetch()['count'];
    }
    
    public static function getTotal(int $cartId): float
    {
        $db = \App\Core\Database::getInstance();
        $stmt = $db->query(
            "SELECT COALESCE(SUM(ci.qty * p.price), 0) as total FROM cart_items ci JOIN products p ON ci.product_id = p.id WHERE ci.cart_id = ?",
            [$cartId]
        );
        return (float) $stmt->fetch()['total'];
    }
    
    public static function clearCart(int $cartId): void
    {
        $db = \App\Core\Database::getInstance();
        $db->query("DELETE FROM cart_items WHERE cart_id = ?", [$cartId]);
    }
}

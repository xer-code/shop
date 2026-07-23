<?php
namespace App\Models;
use App\Core\Model;

class Wishlist extends Model
{
    protected static string $table = 'wishlists';
    
    public static function isWishlisted(int $userId, int $productId): bool
    {
        return self::count(['user_id' => $userId, 'product_id' => $productId]) > 0;
    }
    
    public static function toggle(int $userId, int $productId): bool
    {
        $db = \App\Core\Database::getInstance();
        if (self::isWishlisted($userId, $productId)) {
            $db->query("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?", [$userId, $productId]);
            return false;
        }
        $db->query("INSERT INTO wishlists (user_id, product_id) VALUES (?, ?)", [$userId, $productId]);
        return true;
    }
    
    public static function getUserWishlist(int $userId): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->query(
            "SELECT p.* FROM products p JOIN wishlists w ON p.id = w.product_id WHERE w.user_id = ?",
            [$userId]
        )->fetchAll();
    }
}

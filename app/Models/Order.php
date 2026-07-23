<?php
namespace App\Models;
use App\Core\Model;

class Order extends Model
{
    protected static string $table = 'orders';
    
    public static function getByUser(int $userId): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->query(
            "SELECT o.*, (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count 
             FROM orders o WHERE o.user_id = ? ORDER BY o.created_at DESC",
            [$userId]
        )->fetchAll();
    }
    
    public static function getWithItems(int $orderId): ?array
    {
        $order = self::find($orderId);
        if (!$order) return null;
        $db = \App\Core\Database::getInstance();
        $order['items'] = $db->query(
            "SELECT oi.*, p.title, p.image_url FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?",
            [$orderId]
        )->fetchAll();
        return $order;
    }
    
    public static function findByTracking(string $code): ?array
    {
        return self::whereFirst(['tracking_code' => $code]);
    }
    
    public static function getStats(): array
    {
        $db = \App\Core\Database::getInstance();
        $stats = $db->query("SELECT 
            COUNT(*) as total_orders,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
            SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END) as shipped,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
            COALESCE(SUM(total), 0) as total_revenue
            FROM orders")->fetch();
        return $stats;
    }
}

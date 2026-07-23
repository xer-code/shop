<?php
namespace App\Models;
use App\Core\Model;

class VirtualStore extends Model
{
    protected static string $table = 'virtual_stores';
    
    public static function getWithProductCount(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->query(
            "SELECT vs.*, u.name as owner_name, COUNT(vsp.product_id) as product_count
             FROM virtual_stores vs
             JOIN users u ON vs.owner_user_id = u.id
             LEFT JOIN virtual_store_products vsp ON vs.id = vsp.store_id
             WHERE vs.is_active = 1
             GROUP BY vs.id"
        )->fetchAll();
    }
    
    public static function getProducts(int $storeId, int $limit = 50): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->query(
            "SELECT p.*, c.name as category_name FROM products p
             JOIN virtual_store_products vsp ON p.id = vsp.product_id
             JOIN categories c ON p.category_id = c.id
             WHERE vsp.store_id = ? AND p.is_active = 1
             ORDER BY p.created_at DESC LIMIT ?",
            [$storeId, $limit]
        )->fetchAll();
    }
}

<?php
namespace App\Models;
use App\Core\Model;

class Category extends Model
{
    protected static string $table = 'categories';
    
    public static function getWithProductCount(): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->query(
            "SELECT c.*, COUNT(p.id) as product_count 
             FROM categories c 
             LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1 
             GROUP BY c.id 
             ORDER BY c.name ASC"
        )->fetchAll();
    }
    
    public static function findBySlug(string $slug): ?array
    {
        return self::whereFirst(['slug' => $slug]);
    }
}

<?php
namespace App\Models;
use App\Core\Model;

class Product extends Model
{
    protected static string $table = 'products';
    
    public static function getFiltered(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $db = \App\Core\Database::getInstance();
        $where = ['p.is_active = 1'];
        $params = [];
        
        if (!empty($filters['search'])) {
            $where[] = '(p.title LIKE ? OR p.description LIKE ?)';
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'p.category_id = ?';
            $params[] = $filters['category_id'];
        }
        
        $whereStr = implode(' AND ', $where);
        $orderBy = match($filters['sort'] ?? 'newest') {
            'price_low' => 'p.price ASC',
            'price_high' => 'p.price DESC',
            'rating' => 'p.rating DESC',
            'popular' => 'p.review_count DESC',
            default => 'p.created_at DESC',
        };
        
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                WHERE {$whereStr} 
                ORDER BY {$orderBy} 
                LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        return $db->query($sql, $params)->fetchAll();
    }
    
    public static function countFiltered(array $filters = []): int
    {
        $db = \App\Core\Database::getInstance();
        $where = ['is_active = 1'];
        $params = [];
        
        if (!empty($filters['search'])) {
            $where[] = '(title LIKE ? OR description LIKE ?)';
            $params[] = "%{$filters['search']}%";
            $params[] = "%{$filters['search']}%";
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'category_id = ?';
            $params[] = $filters['category_id'];
        }
        
        $whereStr = implode(' AND ', $where);
        $stmt = $db->query("SELECT COUNT(*) as count FROM products WHERE {$whereStr}", $params);
        return (int) $stmt->fetch()['count'];
    }
    
    public static function getHot(int $limit = 10): array
    {
        $db = \App\Core\Database::getInstance();
        return $db->query(
            "SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.is_hot = 1 AND p.is_active = 1 ORDER BY p.created_at DESC LIMIT ?",
            [$limit]
        )->fetchAll();
    }
}

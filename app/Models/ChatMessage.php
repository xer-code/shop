<?php
namespace App\Models;
use App\Core\Model;

class ChatMessage extends Model
{
    protected static string $table = 'chat_messages';
    
    public static function getConversation(?int $userId, ?string $sessionId, int $limit = 50): array
    {
        $db = \App\Core\Database::getInstance();
        if ($userId) {
            return $db->query(
                "SELECT * FROM chat_messages WHERE user_id = ? ORDER BY created_at ASC LIMIT ?",
                [$userId, $limit]
            )->fetchAll();
        } elseif ($sessionId) {
            return $db->query(
                "SELECT * FROM chat_messages WHERE session_id = ? AND user_id IS NULL ORDER BY created_at ASC LIMIT ?",
                [$sessionId, $limit]
            )->fetchAll();
        }
        return [];
    }
    
    public static function getUnreadCount(int $userId): int
    {
        return self::count(['user_id' => $userId, 'is_read' => 0, 'sender' => 'admin']);
    }
}

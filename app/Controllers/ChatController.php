<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Models\ChatMessage;
use App\Core\PusherService;

class ChatController extends Controller
{
    public function __construct()
    {
        $db = \App\Core\Database::getInstance();
        try {
            // Ensure chat_messages table exists dynamically so live servers don't crash
            $db->query("CREATE TABLE IF NOT EXISTS chat_messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT DEFAULT NULL,
                session_id VARCHAR(128) DEFAULT NULL,
                message TEXT NOT NULL,
                sender ENUM('user', 'admin', 'bot') NOT NULL DEFAULT 'user',
                is_read TINYINT(1) DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_user_chat (user_id),
                INDEX idx_session_chat (session_id),
                INDEX idx_sender (sender)
            ) ENGINE=InnoDB");

            // Also double check for session_id column in case they had an old table
            $columns = $db->query("SHOW COLUMNS FROM chat_messages LIKE 'session_id'")->fetch();
            if (!$columns) {
                $db->query("ALTER TABLE chat_messages ADD COLUMN session_id VARCHAR(128) DEFAULT NULL");
                $db->query("ALTER TABLE chat_messages ADD INDEX idx_session_chat (session_id)");
            }
        } catch (\PDOException $e) {
            // Silently ignore if lacking privileges, but at least we tried
        }
    }

    public function send(): void
    {
        $message = trim($this->input('message', ''));
        if (empty($message)) {
            $this->json(['error' => 'Message cannot be empty'], 400);
            return;
        }

        $userId = Auth::id();
        $sessionId = session_id();

        // If logged in, sync any past guest messages from this session
        if ($userId) {
            $db = \App\Core\Database::getInstance();
            $db->query(
                "UPDATE chat_messages SET user_id = ? WHERE session_id = ? AND user_id IS NULL",
                [$userId, $sessionId]
            );
        }
        
        ChatMessage::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'message' => $message,
            'sender' => 'user',
        ]);
        
        $userName = Auth::user()['name'] ?? 'Guest';
        $userEmail = Auth::user()['email'] ?? 'Guest Customer';
        
        PusherService::trigger('private-admin-chat', 'new-message', [
            'user_id' => $userId,
            'session_id' => $sessionId,
            'message' => $message,
            'sender' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
            'name' => $userName,
            'email' => $userEmail,
        ]);
        
        $this->json([
            'success' => true,
            'admin_online' => $this->isAdminOnline()
        ]);
    }

    
    public function messages(): void
    {
        $userId = Auth::id();
        $sessionId = session_id();

        // If logged in, sync any past guest messages from this session
        if ($userId) {
            $db = \App\Core\Database::getInstance();
            $db->query(
                "UPDATE chat_messages SET user_id = ? WHERE session_id = ? AND user_id IS NULL",
                [$userId, $sessionId]
            );
        }

        $messages = ChatMessage::getConversation($userId, $sessionId);

        // Mark incoming replies as read
        $db = \App\Core\Database::getInstance();
        if ($userId) {
            $db->query(
                "UPDATE chat_messages SET is_read = 1 WHERE user_id = ? AND sender IN ('admin', 'bot') AND is_read = 0",
                [$userId]
            );
        } else {
            $db->query(
                "UPDATE chat_messages SET is_read = 1 WHERE session_id = ? AND user_id IS NULL AND sender IN ('admin', 'bot') AND is_read = 0",
                [$sessionId]
            );
        }

        $this->json([
            'messages' => $messages,
            'admin_online' => $this->isAdminOnline()
        ]);
    }

    public function adminStatus(): void
    {
        $this->json(['online' => $this->isAdminOnline()]);
    }

    private function isAdminOnline(): bool
    {
        $db = \App\Core\Database::getInstance();
        $hasStatusTable = $db->query("SHOW TABLES LIKE 'admin_online_status'")->fetch();
        if (!$hasStatusTable) {
            return false;
        }
        $row = $db->query(
            "SELECT COUNT(*) as count FROM admin_online_status WHERE last_heartbeat >= (NOW() - INTERVAL 60 SECOND)"
        )->fetch();
        return !empty($row) && (int)$row['count'] > 0;
    }
    
    private function getBotReply(string $message): string
    {
        $msg = strtolower($message);
        if (str_contains($msg, 'shipping') || str_contains($msg, 'delivery')) {
            return "🚚 We ship to 190+ countries worldwide! Standard delivery takes 5-15 business days. Express shipping is available at checkout for faster delivery.";
        }
        if (str_contains($msg, 'return') || str_contains($msg, 'refund')) {
            return "↩️ We offer a 30-day return policy on most items. Please visit your Orders page to initiate a return. Refunds are processed within 5-7 business days.";
        }
        if (str_contains($msg, 'payment') || str_contains($msg, 'pay')) {
            return "💳 We accept wallet balance, gift cards, Bitcoin, CashApp, Ethereum, PayPal, Zelle, and Bank Transfer. You can add funds to your wallet from the Wallet page.";
        }
        if (str_contains($msg, 'track') || str_contains($msg, 'order')) {
            return "📦 You can track your order using the Track Order page. Enter your order ID or tracking code to see real-time status updates.";
        }
        if (str_contains($msg, 'hello') || str_contains($msg, 'hi') || str_contains($msg, 'hey')) {
            return "👋 Hello! Welcome to ShopX Global. How can I assist you today? Feel free to ask about shipping, payments, orders, or anything else!";
        }
        return "Thank you for reaching out! Our support team will get back to you shortly. In the meantime, you can browse our FAQ or explore our shop. Is there anything specific I can help with? 🛍️";
    }
}


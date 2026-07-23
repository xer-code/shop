<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Auth;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    public function __construct()
    {
        $db = \App\Core\Database::getInstance();
        $columns = $db->query("SHOW COLUMNS FROM chat_messages LIKE 'session_id'")->fetch();
        if (!$columns) {
            $db->query("ALTER TABLE chat_messages ADD COLUMN session_id VARCHAR(128) DEFAULT NULL");
            $db->query("ALTER TABLE chat_messages ADD INDEX idx_session_chat (session_id)");
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
        
        // Check if an admin has ever replied to this user or session
        $db = \App\Core\Database::getInstance();
        if ($userId) {
            $hasAdminReplied = $db->query(
                "SELECT COUNT(*) as count FROM chat_messages WHERE user_id = ? AND sender = 'admin'",
                [$userId]
            )->fetch();
        } else {
            $hasAdminReplied = $db->query(
                "SELECT COUNT(*) as count FROM chat_messages WHERE session_id = ? AND user_id IS NULL AND sender = 'admin'",
                [$sessionId]
            )->fetch();
        }
        
        $botReply = null;
        if (!$hasAdminReplied || (int)$hasAdminReplied['count'] === 0) {
            // Auto-reply bot (only if admin hasn't intervened yet)
            $botReply = $this->getBotReply($message);
            ChatMessage::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'message' => $botReply,
                'sender' => 'bot',
            ]);
        }
        
        $this->json([
            'success' => true,
            'bot_reply' => $botReply
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

        $this->json(['messages' => $messages]);
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

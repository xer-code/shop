<?php

require_once dirname(__DIR__) . '/config/config.php';

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_PATH . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Database;

try {
    $db = Database::getInstance();
    
    // Create deposit_requests table
    $db->query("
        CREATE TABLE IF NOT EXISTS deposit_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            amount DECIMAL(12, 2) NOT NULL,
            gateway_id INT NOT NULL,
            gateway_name VARCHAR(100) NOT NULL,
            screenshot_path VARCHAR(255) DEFAULT NULL,
            notes TEXT DEFAULT NULL,
            status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_user_deposit (user_id),
            INDEX idx_deposit_status (status)
        ) ENGINE=InnoDB;
    ");

    // Insert sample pending deposits for John Customer (ID 2)
    $stmt = $db->query("SELECT id FROM users WHERE email = 'customer@shopx.com'");
    $user = $stmt->fetch();
    if ($user) {
        $userId = $user['id'];
        // Check if sample requests exist
        $check = $db->query("SELECT count(*) FROM deposit_requests WHERE user_id = ?", [$userId])->fetchColumn();
        if ($check == 0) {
            $db->query("
                INSERT INTO deposit_requests (user_id, amount, gateway_id, gateway_name, screenshot_path, notes, status) VALUES
                (?, 50.00, 6, 'Bank Transfer (ACH)', 'sample_ach_proof.png', 'Transferred from Chase checking ending in 9201', 'pending'),
                (?, 100.00, 1, 'Bitcoin (BTC)', 'sample_btc_proof.png', 'Tx hash: a81f3d...', 'pending')
            ", [$userId, $userId]);
        }
    }

    echo "Migration completed successfully! Table deposit_requests created and populated.";
} catch (Exception $e) {
    echo "Error during migration: " . $e->getMessage();
}

<?php

namespace App\Core;

/**
 * Auth — Authentication & authorization helpers
 */
class Auth
{
    /**
     * Attempt to log in a user
     */
    public static function attempt(string $email, string $password): bool
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM users WHERE email = ? LIMIT 1", [$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            self::login($user);
            return true;
        }

        return false;
    }

    /**
     * Log in a user (set session)
     */
    public static function login(array $user): void
    {
        Session::set('user_id', $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['role']);
        Session::set('user_wallet', $user['wallet_balance']);
        
        // Regenerate session ID for security
        session_regenerate_id(true);
    }

    /**
     * Log out the current user
     */
    public static function logout(): void
    {
        Session::remove('user_id');
        Session::remove('user_name');
        Session::remove('user_email');
        Session::remove('user_role');
        Session::remove('user_wallet');
        session_regenerate_id(true);
    }

    /**
     * Check if a user is logged in
     */
    public static function check(): bool
    {
        return Session::has('user_id');
    }

    /**
     * Check if the current user is an admin
     */
    public static function isAdmin(): bool
    {
        return Session::get('user_role') === 'admin';
    }

    /**
     * Get the current user's ID
     */
    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    /**
     * Get the current user's name
     */
    public static function name(): ?string
    {
        return Session::get('user_name');
    }

    /**
     * Get the current user's email
     */
    public static function email(): ?string
    {
        return Session::get('user_email');
    }

    /**
     * Get the current user's role
     */
    public static function role(): ?string
    {
        return Session::get('user_role');
    }

    /**
     * Get the current user's wallet balance
     */
    public static function wallet(): float
    {
        return (float) Session::get('user_wallet', 0);
    }

    /**
     * Refresh wallet balance from DB
     */
    public static function refreshWallet(): void
    {
        if (self::check()) {
            $db = Database::getInstance();
            $stmt = $db->query("SELECT wallet_balance FROM users WHERE id = ?", [self::id()]);
            $user = $stmt->fetch();
            if ($user) {
                Session::set('user_wallet', $user['wallet_balance']);
            }
        }
    }

    /**
     * Get the full current user record
     */
    public static function user(): ?array
    {
        if (!self::check()) return null;
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM users WHERE id = ? LIMIT 1", [self::id()]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Hash a password
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Record heartbeat timestamp for an admin user (Unix timestamp)
     */
    public static function recordAdminHeartbeat(?int $adminId = null): void
    {
        $id = $adminId ?? self::id();
        if (!$id) return;
        
        try {
            $db = Database::getInstance();
            $hasTable = $db->query("SHOW TABLES LIKE 'admin_online_status'")->fetch();
            
            // Auto-migrate schema if it's the old format or missing the is_online column
            $hasTable = $db->query("SHOW TABLES LIKE 'admin_online_status'")->fetch();
            $needsRecreation = false;
            
            if ($hasTable) {
                $col = $db->query("SHOW COLUMNS FROM admin_online_status LIKE 'last_heartbeat'")->fetch();
                if ($col && str_contains(strtolower($col['Type']), 'timestamp')) {
                    $needsRecreation = true; // Old timestamp format
                }
                
                $onlineCol = $db->query("SHOW COLUMNS FROM admin_online_status LIKE 'is_online'")->fetch();
                if (!$onlineCol) {
                    $needsRecreation = true; // Missing toggle column
                }
            } else {
                $needsRecreation = true;
            }
            
            if ($needsRecreation) {
                $db->query("DROP TABLE IF EXISTS admin_online_status");
                $db->query("
                    CREATE TABLE admin_online_status (
                        admin_id INT PRIMARY KEY,
                        last_heartbeat INT NOT NULL,
                        is_online TINYINT(1) DEFAULT 1,
                        FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
                    ) ENGINE=InnoDB;
                ");
            }
            
            $now = time();
            $db->query(
                "INSERT INTO admin_online_status (admin_id, last_heartbeat) VALUES (?, ?) ON DUPLICATE KEY UPDATE last_heartbeat = ?",
                [$id, $now, $now]
            );
        } catch (\Throwable $e) {
            // Silence exceptions to keep requests resilient
        }
    }

    /**
     * Check if any admin user has been active recently (within last 120 seconds) and is explicitly online
     */
    public static function isAdminOnline(): bool
    {
        try {
            $db = Database::getInstance();
            $hasTable = $db->query("SHOW TABLES LIKE 'admin_online_status'")->fetch();
            if (!$hasTable) {
                return false;
            }

            // Fallback for old schema if migration somehow didn't run (unlikely)
            $col = $db->query("SHOW COLUMNS FROM admin_online_status LIKE 'last_heartbeat'")->fetch();
            if ($col && str_contains(strtolower($col['Type']), 'timestamp')) {
                $row = $db->query("SELECT COUNT(*) as count FROM admin_online_status WHERE last_heartbeat >= (NOW() - INTERVAL 120 SECOND)")->fetch();
                if (!empty($row) && (int)$row['count'] > 0) return true;
            }

            $cutoff = time() - 120;
            // Ensure they are within cutoff AND is_online is true
            $row = $db->query(
                "SELECT COUNT(*) as count FROM admin_online_status WHERE last_heartbeat >= ? AND is_online = 1",
                [$cutoff]
            )->fetch();
            return !empty($row) && (int)$row['count'] > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }
}


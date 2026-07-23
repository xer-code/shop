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
}

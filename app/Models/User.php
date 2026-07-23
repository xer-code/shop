<?php
namespace App\Models;
use App\Core\Model;

class User extends Model
{
    protected static string $table = 'users';
    
    public static function findByEmail(string $email): ?array
    {
        return self::whereFirst(['email' => $email]);
    }
    
    public static function emailExists(string $email): bool
    {
        return self::count(['email' => $email]) > 0;
    }
    
    public static function updateWallet(int $userId, float $amount): void
    {
        $db = \App\Core\Database::getInstance();
        $db->query("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?", [$amount, $userId]);
    }
}

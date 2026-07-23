<?php
namespace App\Models;
use App\Core\Model;

class WalletTransaction extends Model
{
    protected static string $table = 'wallet_transactions';
    
    public static function getByUser(int $userId, int $limit = 20): array
    {
        return self::where(['user_id' => $userId], 'created_at', 'DESC', $limit);
    }
}

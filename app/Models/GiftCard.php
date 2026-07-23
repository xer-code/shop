<?php
namespace App\Models;
use App\Core\Model;

class GiftCard extends Model
{
    protected static string $table = 'gift_cards';
    
    public static function findByCode(string $code): ?array
    {
        return self::whereFirst(['code' => $code]);
    }
    
    public static function generateUniqueCode(): string
    {
        do {
            $code = 'SHOPX-' . generateCode(4) . '-' . generateCode(4);
        } while (self::findByCode($code));
        return $code;
    }
}

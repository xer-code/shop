<?php

/**
 * ShopX Global — Helper Functions
 * Available globally in all views and controllers
 */

use App\Core\Session;
use App\Core\Auth;

function url(string $path = ''): string
{
    if ($path === '' || $path === null) {
        return '';
    }
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    
    // Determine scheme (http or https)
    $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    
    // Determine host
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Determine script directory (e.g. /shop/public/index.php -> /shop/public)
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $baseDir = rtrim(dirname($scriptName), '/\\');
    $baseDir = preg_replace('#[/\\\\]public$#i', '', $baseDir);
    
    $base = $scheme . '://' . $host . $baseDir;
    $path = '/' . ltrim($path, '/');
    return $base . $path;
}

/**
 * Generate an asset URL
 */
function asset(string $path): string
{
    return url('/assets/' . ltrim($path, '/'));
}

/**
 * Generate a CSRF hidden input field
 */
function csrf_field(): string
{
    $token = Session::generateCsrf();
    return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Get the CSRF token value
 */
function csrf_token(): string
{
    return Session::generateCsrf();
}

/**
 * Get old input value (for form repopulation)
 */
function old(string $key, string $default = ''): string
{
    return htmlspecialchars(Session::oldInput($key, $default));
}

/**
 * Escape HTML output
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Format price
 */
function formatPrice(float $price): string
{
    return '$' . number_format($price, 2);
}

/**
 * Generate star rating HTML
 */
function starRating(float $rating, int $reviewCount = 0): string
{
    $html = '<div class="flex items-center gap-1">';
    for ($i = 1; $i <= 5; $i++) {
        if ($i <= $rating) {
            $html .= '<svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
        } elseif ($i - 0.5 <= $rating) {
            $html .= '<svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" opacity="0.5"/></svg>';
        } else {
            $html .= '<svg class="w-4 h-4 text-gray-600 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
        }
    }
    if ($reviewCount > 0) {
        $html .= '<span class="text-xs text-gray-400 ml-1">(' . number_format($reviewCount) . ')</span>';
    }
    $html .= '</div>';
    return $html;
}

/**
 * Time ago helper
 */
function timeAgo(string $datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date('M j, Y', $time);
}

/**
 * Truncate text
 */
function truncate(string $text, int $length = 100): string
{
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Check if current URL matches path
 */
function isActive(string $path): bool
{
    $currentUrl = $_GET['url'] ?? '';
    $path = trim($path, '/');
    return $currentUrl === $path || strpos($currentUrl, $path) === 0;
}

/**
 * Get active CSS class
 */
function activeClass(string $path, string $class = 'text-gold'): string
{
    return isActive($path) ? $class : '';
}

/**
 * Generate a random string
 */
function generateCode(int $length = 16): string
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}

/**
 * Get cart item count from session
 */
function cartCount(): int
{
    return Session::get('cart_count', 0);
}

/**
 * Get system payment gateways configuration (persistent across all sessions)
 */
function getPaymentGateways(): array
{
    $file = ROOT_PATH . '/database/ent_gateways.json';
    if (file_exists($file)) {
        $content = @file_get_contents($file);
        if ($content !== false) {
            $data = json_decode($content, true);
            if (is_array($data)) {
                Session::set('ent_gateways', $data);
                return $data;
            }
        }
    }

    $defaults = [
        ['id' => 1, 'name' => 'Bitcoin (BTC)', 'min' => 10.00, 'fee' => 0.00, 'address' => 'bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh', 'status' => 'Active', 'icon' => '₿'],
        ['id' => 2, 'name' => 'CashApp', 'min' => 5.00, 'fee' => 1.50, 'address' => '$shopxglobal', 'status' => 'Active', 'icon' => '💵'],
        ['id' => 3, 'name' => 'Ethereum (ETH)', 'min' => 10.00, 'fee' => 0.00, 'address' => '0x71C7656EC7ab88b098defB751B7401B5f6d1476B', 'status' => 'Active', 'icon' => 'Ξ'],
        ['id' => 4, 'name' => 'PayPal', 'min' => 5.00, 'fee' => 2.90, 'address' => 'paypal@shopx.com', 'status' => 'Active', 'icon' => '💳'],
        ['id' => 5, 'name' => 'Zelle', 'min' => 10.00, 'fee' => 0.00, 'address' => 'zelle@shopx.com', 'status' => 'Active', 'icon' => '⚡'],
        ['id' => 6, 'name' => 'Bank Transfer (ACH)', 'min' => 25.00, 'fee' => 5.00, 'address' => 'Routing: 123456789, Account: 987654321', 'status' => 'Active', 'icon' => '🏦']
    ];
    @file_put_contents($file, json_encode($defaults, JSON_PRETTY_PRINT));
    Session::set('ent_gateways', $defaults);
    return $defaults;
}

/**
 * Save system payment gateways configuration
 */
function savePaymentGateways(array $gateways): void
{
    $file = ROOT_PATH . '/database/ent_gateways.json';
    @file_put_contents($file, json_encode(array_values($gateways), JSON_PRETTY_PRINT));
    Session::set('ent_gateways', array_values($gateways));
}

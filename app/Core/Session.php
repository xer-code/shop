<?php

namespace App\Core;

/**
 * Session — Manages sessions, flash messages, CSRF tokens
 */
class Session
{
    /**
     * Initialize session
     */
    public static function init(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a session value
     */
    public static function set(string $key, $value): void
    {
        if ($key === 'ent_shipments' || $key === 'ent_tracking' || $key === 'ent_settings') {
            $filePath = dirname(dirname(__DIR__)) . '/database/' . $key . '.json';
            @file_put_contents($filePath, json_encode($value, JSON_PRETTY_PRINT));
        } elseif ($key === 'ent_support') {
            $filePath = dirname(dirname(__DIR__)) . '/database/ent_support.json';
            if (is_array($value)) {
                foreach ($value as &$ticket) {
                    if (isset($ticket['messages']) && is_array($ticket['messages'])) {
                        foreach ($ticket['messages'] as &$msg) {
                            if (!isset($msg['sender']) && isset($msg['from'])) {
                                $msg['sender'] = $msg['from'];
                            }
                            if (!isset($msg['from']) && isset($msg['sender'])) {
                                $msg['from'] = $msg['sender'];
                            }
                            if (!isset($msg['time'])) {
                                $msg['time'] = date('Y-m-d H:i:s');
                            }
                        }
                    }
                }
            }
            @file_put_contents($filePath, json_encode($value, JSON_PRETTY_PRINT));
        } elseif (strncmp($key, 'customer_tickets_', 17) === 0) {
            $userId = (int) str_replace('customer_tickets_', '', $key);
            $filePath = dirname(dirname(__DIR__)) . '/database/ent_support.json';
            
            $globalTickets = [];
            if (file_exists($filePath)) {
                $content = @file_get_contents($filePath);
                if ($content !== false) {
                    $decoded = json_decode($content, true);
                    if (is_array($decoded)) {
                        $globalTickets = $decoded;
                    }
                }
            }
            
            $globalById = [];
            foreach ($globalTickets as $index => $gt) {
                $globalById[(int)$gt['id']] = [
                    'index' => $index,
                    'ticket' => $gt
                ];
            }
            
            foreach ($value as $ct) {
                $ctId = (int) ($ct['id'] ?? 0);
                if (!isset($ct['user_id'])) {
                    $ct['user_id'] = $userId;
                }
                if (!isset($ct['customer'])) {
                    $ct['customer'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Customer';
                }
                if (!isset($ct['date'])) {
                    $ct['date'] = $ct['created_at'] ?? date('Y-m-d H:i:s');
                }
                
                if (isset($ct['messages']) && is_array($ct['messages'])) {
                    foreach ($ct['messages'] as &$msg) {
                        if (!isset($msg['sender']) && isset($msg['from'])) {
                            $msg['sender'] = $msg['from'];
                        }
                        if (!isset($msg['from']) && isset($msg['sender'])) {
                            $msg['from'] = $msg['sender'];
                        }
                        if (!isset($msg['time'])) {
                            $msg['time'] = date('Y-m-d H:i:s');
                        }
                    }
                }
                
                if ($ctId > 0 && isset($globalById[$ctId]) && (int)$globalById[$ctId]['ticket']['user_id'] === $userId) {
                    $idx = $globalById[$ctId]['index'];
                    $globalTickets[$idx] = $ct;
                } else {
                    $newId = 1;
                    if (!empty($globalById)) {
                        $newId = max(array_keys($globalById)) + 1;
                    }
                    $ct['id'] = $newId;
                    $globalTickets[] = $ct;
                    
                    $globalById[$newId] = [
                        'index' => count($globalTickets) - 1,
                        'ticket' => $ct
                    ];
                }
            }
            @file_put_contents($filePath, json_encode($globalTickets, JSON_PRETTY_PRINT));
        }
        $_SESSION[$key] = $value;
    }

    /**
     * Get a session value
     */
    public static function get(string $key, $default = null)
    {
        if ($key === 'ent_shipments' || $key === 'ent_tracking' || $key === 'ent_settings') {
            $filePath = dirname(dirname(__DIR__)) . '/database/' . $key . '.json';
            if (file_exists($filePath)) {
                $content = @file_get_contents($filePath);
                if ($content !== false) {
                    $decoded = json_decode($content, true);
                    if (is_array($decoded)) {
                        return $decoded;
                    }
                }
            }
        } elseif ($key === 'ent_support') {
            $filePath = dirname(dirname(__DIR__)) . '/database/ent_support.json';
            if (file_exists($filePath)) {
                $content = @file_get_contents($filePath);
                if ($content !== false) {
                    $decoded = json_decode($content, true);
                    if (is_array($decoded)) {
                        return $decoded;
                    }
                }
            }
        } elseif (strncmp($key, 'customer_tickets_', 17) === 0) {
            $userId = (int) str_replace('customer_tickets_', '', $key);
            $filePath = dirname(dirname(__DIR__)) . '/database/ent_support.json';
            if (file_exists($filePath)) {
                $content = @file_get_contents($filePath);
                if ($content !== false) {
                    $decoded = json_decode($content, true);
                    if (is_array($decoded)) {
                        $userTickets = array_filter($decoded, function($t) use ($userId) {
                            return (int)($t['user_id'] ?? 0) === $userId;
                        });
                        return array_values($userTickets);
                    }
                }
            }
            return $default;
        }
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if a session key exists
     */
    public static function has(string $key): bool
    {
        if ($key === 'ent_shipments' || $key === 'ent_tracking' || $key === 'ent_settings') {
            $filePath = dirname(dirname(__DIR__)) . '/database/' . $key . '.json';
            if (file_exists($filePath)) {
                return true;
            }
        } elseif ($key === 'ent_support' || strncmp($key, 'customer_tickets_', 17) === 0) {
            $filePath = dirname(dirname(__DIR__)) . '/database/ent_support.json';
            if (file_exists($filePath)) {
                return true;
            }
        }
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a session key
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destroy the entire session
     */
    public static function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Set a flash message (available for one request only)
     */
    public static function flash(string $key, $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    /**
     * Get a flash message (and remove it)
     */
    public static function getFlash(string $key, $default = null)
    {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    /**
     * Check if flash message exists
     */
    public static function hasFlash(string $key): bool
    {
        return isset($_SESSION['_flash'][$key]);
    }

    /**
     * Generate CSRF token
     */
    public static function generateCsrf(): string
    {
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCsrf(string $token): bool
    {
        return isset($_SESSION['_csrf_token']) && hash_equals($_SESSION['_csrf_token'], $token);
    }

    /**
     * Store old input for form repopulation
     */
    public static function flashInput(array $data): void
    {
        // Don't flash passwords
        unset($data['password'], $data['password_confirmation'], $data['_csrf_token']);
        $_SESSION['_old_input'] = $data;
    }

    /**
     * Get old input value
     */
    public static function oldInput(string $key, $default = '')
    {
        $value = $_SESSION['_old_input'][$key] ?? $default;
        return $value;
    }

    /**
     * Clear old input
     */
    public static function clearOldInput(): void
    {
        unset($_SESSION['_old_input']);
    }
}

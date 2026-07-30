<?php

namespace App\Core;

/**
 * Lightweight Pusher REST API client
 * Requires NO external dependencies or Composer
 */
class PusherService
{
    private static function getCredentials(): array
    {
        $settings = Session::get('ent_settings', []);
        return [
            'app_id' => $settings['pusher_app_id'] ?? '',
            'key' => $settings['pusher_key'] ?? '',
            'secret' => $settings['pusher_secret'] ?? '',
            'cluster' => $settings['pusher_cluster'] ?? 'mt1',
        ];
    }

    public static function isConfigured(): bool
    {
        $creds = self::getCredentials();
        return !empty($creds['app_id']) && !empty($creds['key']) && !empty($creds['secret']);
    }

    /**
     * Broadcast an event to a channel
     */
    public static function trigger(string $channel, string $event, array $data): bool
    {
        if (!self::isConfigured()) {
            return false;
        }

        $creds = self::getCredentials();
        
        $body = json_encode([
            'name' => $event,
            'channels' => [$channel],
            'data' => json_encode($data)
        ]);
        
        $path = "/apps/{$creds['app_id']}/events";
        
        $authTimestamp = time();
        $authVersion = '1.0';
        $bodyMd5 = md5($body);
        
        $authSignatureParams = [
            'auth_key' => $creds['key'],
            'auth_timestamp' => $authTimestamp,
            'auth_version' => $authVersion,
            'body_md5' => $bodyMd5
        ];
        
        ksort($authSignatureParams);
        $queryString = http_build_query($authSignatureParams);
        
        $signatureData = "POST\n$path\n$queryString";
        $authSignature = hash_hmac('sha256', $signatureData, $creds['secret']);
        
        $url = "https://api-{$creds['cluster']}.pusher.com$path?$queryString&auth_signature=$authSignature";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for Laragon SSL errors
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // DEBUG: Write to log
        @file_put_contents(dirname(__DIR__) . '/../database/pusher_debug.txt', date('Y-m-d H:i:s') . " - Code: $httpCode - Error: $error - Response: $response\n", FILE_APPEND);
        
        return $httpCode === 200;
    }

    /**
     * Authenticate private/presence channels
     */
    public static function auth(string $channelName, string $socketId, ?array $customData = null): string
    {
        $creds = self::getCredentials();
        
        if (!self::isConfigured()) {
            return json_encode(['error' => 'Pusher not configured']);
        }
        
        $signatureData = $socketId . ':' . $channelName;
        
        if ($customData) {
            $customDataJson = json_encode($customData);
            $signatureData .= ':' . $customDataJson;
        }
        
        $authSignature = hash_hmac('sha256', $signatureData, $creds['secret']);
        
        $response = [
            'auth' => $creds['key'] . ':' . $authSignature
        ];
        
        if ($customData) {
            $response['channel_data'] = $customDataJson;
        }
        
        return json_encode($response);
    }
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\PusherService;
use App\Core\Auth;

class PusherAuthController extends Controller
{
    public function auth(): void
    {
        $socketId = $this->input('socket_id');
        $channelName = $this->input('channel_name');

        if (!$socketId || !$channelName) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing socket_id or channel_name']);
            return;
        }

        $userId = Auth::id();
        $sessionId = session_id();
        
        $customData = null;

        // Presence channel needs channel_data
        if (strpos($channelName, 'presence-') === 0) {
            if (!$userId || Auth::user()['role'] !== 'admin') {
                http_response_code(403);
                echo json_encode(['error' => 'Unauthorized']);
                return;
            }
            $customData = [
                'user_id' => $userId,
                'user_info' => [
                    'name' => Auth::user()['name']
                ]
            ];
        }

        header('Content-Type: application/json');
        echo PusherService::auth($channelName, $socketId, $customData);
        exit;
    }
}

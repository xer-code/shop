<?php
require_once __DIR__ . '/app/Core/Session.php';
require_once __DIR__ . '/app/Core/PusherService.php';

$result = \App\Core\PusherService::trigger('private-admin-chat', 'new-message', ['test' => 'Hello']);
var_dump($result);

<?php
$app_id = "2181457";
$key = "633acd2de3d5e6eb4647";
$secret = "094e7da662d8ebc0e555";
$cluster = "mt1";

$path = "/apps/{$app_id}/events";
$authTimestamp = time();
$authVersion = '1.0';

$body = json_encode([
    'name' => 'new-message',
    'channels' => ['private-admin-chat'],
    'data' => json_encode(['test' => 'Hello'])
]);

$bodyMd5 = md5($body);

$authSignatureParams = [
    'auth_key' => $key,
    'auth_timestamp' => $authTimestamp,
    'auth_version' => $authVersion,
    'body_md5' => $bodyMd5
];

ksort($authSignatureParams);
$queryString = http_build_query($authSignatureParams);

$signatureData = "POST\n$path\n$queryString";
$authSignature = hash_hmac('sha256', $signatureData, $secret);

$url = "https://api-{$cluster}.pusher.com$path?$queryString&auth_signature=$authSignature";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Code: $httpCode\n";
echo "Error: $error\n";
echo "Response: $response\n";

<?php

namespace App\Core;

class Mailer
{
    private static ?array $settings = null;
    private static ?array $templates = null;

    /**
     * Get email configuration settings
     */
    public static function getSettings(): array
    {
        if (self::$settings !== null) {
            return self::$settings;
        }

        $file = ROOT_PATH . '/database/email_settings.json';
        if (file_exists($file)) {
            $content = @file_get_contents($file);
            if ($content !== false) {
                $data = json_decode($content, true);
                if (is_array($data)) {
                    self::$settings = $data;
                    return self::$settings;
                }
            }
        }

        self::$settings = [
            'enabled' => true,
            'driver' => 'mail',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'smtp_username' => 'notifications@shopxglobal.com',
            'smtp_password' => '',
            'from_email' => 'noreply@shopxglobal.com',
            'from_name' => 'ShopX Global Marketplace'
        ];

        @file_put_contents($file, json_encode(self::$settings, JSON_PRETTY_PRINT));
        return self::$settings;
    }

    /**
     * Save email configuration settings
     */
    public static function saveSettings(array $settings): void
    {
        $file = ROOT_PATH . '/database/email_settings.json';
        self::$settings = array_merge(self::getSettings(), $settings);
        @file_put_contents($file, json_encode(self::$settings, JSON_PRETTY_PRINT));
    }

    /**
     * Get email templates
     */
    public static function getTemplates(): array
    {
        if (self::$templates !== null) {
            return self::$templates;
        }

        $file = ROOT_PATH . '/database/email_templates.json';
        if (file_exists($file)) {
            $content = @file_get_contents($file);
            if ($content !== false) {
                $data = json_decode($content, true);
                if (is_array($data)) {
                    self::$templates = $data;
                    return self::$templates;
                }
            }
        }

        self::$templates = [];
        return self::$templates;
    }

    /**
     * Save email templates
     */
    public static function saveTemplates(array $templates): void
    {
        $file = ROOT_PATH . '/database/email_templates.json';
        self::$templates = $templates;
        @file_put_contents($file, json_encode(self::$templates, JSON_PRETTY_PRINT));
    }

    /**
     * Send email using active driver (SMTP or Mail)
     */
    public static function send(string $toEmail, string $subject, string $htmlContent, ?string $toName = null): array
    {
        $settings = self::getSettings();

        if (empty($settings['enabled'])) {
            return [
                'success' => false,
                'message' => 'System mailer is currently disabled in Email Settings.'
            ];
        }

        if (empty($toEmail) || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Invalid recipient email address.'
            ];
        }

        $fromEmail = $settings['from_email'] ?: 'noreply@shopxglobal.com';
        $fromName = $settings['from_name'] ?: 'ShopX Global';
        $driver = strtolower($settings['driver'] ?? 'mail');

        if ($driver === 'smtp') {
            return self::sendSmtp($toEmail, $toName, $subject, $htmlContent, $settings);
        }

        // Native PHP mail() driver fallback
        return self::sendMail($toEmail, $toName, $subject, $htmlContent, $fromEmail, $fromName);
    }

    /**
     * Render template and send trigger notification
     */
    public static function sendTriggerEmail(string $triggerKey, string $toEmail, array $variables = [], ?string $toName = null): array
    {
        $templates = self::getTemplates();
        if (!isset($templates[$triggerKey])) {
            return [
                'success' => false,
                'message' => "Email template '{$triggerKey}' not found."
            ];
        }

        $template = $templates[$triggerKey];
        $subject = $template['subject'] ?? 'ShopX Global Notification';
        $content = $template['content'] ?? '';

        // Add default global variables
        $variables['app_name'] = defined('APP_NAME') ? APP_NAME : 'ShopX Global';
        $variables['app_url'] = defined('APP_URL') ? APP_URL : url('/');

        foreach ($variables as $key => $val) {
            $placeholder = '{' . $key . '}';
            $subject = str_replace($placeholder, (string)$val, $subject);
            $content = str_replace($placeholder, (string)$val, $content);
        }

        $result = self::send($toEmail, $subject, $content, $toName);
        self::logEmailSent($triggerKey, $toEmail, $subject, $result['success'], $result['message']);

        return $result;
    }

    /**
     * Dispatch a test email for settings verification
     */
    public static function sendTestEmail(string $recipientEmail): array
    {
        $subject = "🧪 ShopX Global System Email Settings Test";
        $content = "
        <div style=\"font-family: Arial, sans-serif; background-color: #121212; color: #e0e0e0; padding: 30px; border-radius: 8px; border: 1px solid #D4A017;\">
            <div style=\"text-align: center; margin-bottom: 20px;\">
                <h1 style=\"color: #D4A017; margin: 0;\">SHOP<span style=\"color:#fff;\">X</span> GLOBAL</h1>
                <p style=\"color: #888; font-size: 12px;\">SYSTEM EMAIL CONFIGURATION TEST</p>
            </div>
            <h2 style=\"color: #10B981;\">✅ Connection & Dispatch Successful!</h2>
            <p style=\"line-height: 1.6;\">This test email confirms that your ShopX Global system email settings are configured correctly and active.</p>
            <div style=\"background: #1e1e1e; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #10B981;\">
                <p style=\"margin: 5px 0;\"><strong>Timestamp:</strong> " . date('Y-m-d H:i:s T') . "</p>
                <p style=\"margin: 5px 0;\"><strong>Recipient:</strong> " . htmlspecialchars($recipientEmail) . "</p>
            </div>
        </div>";

        return self::send($recipientEmail, $subject, $content);
    }

    /**
     * Send email via native mail() function
     */
    private static function sendMail(string $toEmail, ?string $toName, string $subject, string $htmlContent, string $fromEmail, string $fromName): array
    {
        $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        
        $toRecipient = $toEmail;
        if (!empty($toName)) {
            $toRecipient = '=?UTF-8?B?' . base64_encode($toName) . '?= <' . $toEmail . '>';
        }

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: {$encodedFromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Attempt sending via mail()
        $success = @mail($toRecipient, $encodedSubject, $htmlContent, $headers);

        if ($success) {
            return ['success' => true, 'message' => 'Email sent successfully via PHP mail().'];
        }

        return ['success' => false, 'message' => 'Failed to send email via native PHP mail(). Check server mail configuration or switch to SMTP driver.'];
    }

    /**
     * Send email via SMTP Socket connection
     */
    private static function sendSmtp(string $toEmail, ?string $toName, string $subject, string $htmlContent, array $settings): array
    {
        $host = $settings['smtp_host'] ?? '';
        $port = (int) ($settings['smtp_port'] ?? 587);
        $encryption = strtolower($settings['smtp_encryption'] ?? 'tls');
        $username = $settings['smtp_username'] ?? '';
        $password = $settings['smtp_password'] ?? '';
        $fromEmail = $settings['from_email'] ?? 'noreply@shopxglobal.com';
        $fromName = $settings['from_name'] ?? 'ShopX Global';

        if (empty($host)) {
            return ['success' => false, 'message' => 'SMTP Host is missing in Email Settings.'];
        }

        $socketHost = ($encryption === 'ssl') ? "ssl://{$host}" : $host;
        $timeout = 10;

        $fp = @fsockopen($socketHost, $port, $errno, $errstr, $timeout);
        if (!$fp) {
            return ['success' => false, 'message' => "Could not connect to SMTP host {$host}:{$port} ({$errstr})"];
        }

        stream_set_timeout($fp, $timeout);

        $getResponse = function() use ($fp) {
            $response = '';
            while ($str = fgets($fp, 512)) {
                $response .= $str;
                if (substr($str, 3, 1) === ' ') break;
            }
            return $response;
        };

        $sendCommand = function($cmd) use ($fp, $getResponse) {
            fputs($fp, $cmd . "\r\n");
            return $getResponse();
        };

        $res = $getResponse();
        if (substr($res, 0, 3) !== '220') {
            fclose($fp);
            return ['success' => false, 'message' => "SMTP greeting error: " . trim($res)];
        }

        $res = $sendCommand("EHLO " . gethostname());

        if ($encryption === 'tls') {
            $res = $sendCommand("STARTTLS");
            if (substr($res, 0, 3) !== '220') {
                fclose($fp);
                return ['success' => false, 'message' => "STARTTLS failed: " . trim($res)];
            }
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
                fclose($fp);
                return ['success' => false, 'message' => "TLS encryption handshake failed."];
            }
            $res = $sendCommand("EHLO " . gethostname());
        }

        if (!empty($username) && !empty($password)) {
            $res = $sendCommand("AUTH LOGIN");
            if (substr($res, 0, 3) !== '334') {
                fclose($fp);
                return ['success' => false, 'message' => "AUTH LOGIN command failed: " . trim($res)];
            }
            $res = $sendCommand(base64_encode($username));
            if (substr($res, 0, 3) !== '334') {
                fclose($fp);
                return ['success' => false, 'message' => "Username authentication failed: " . trim($res)];
            }
            $res = $sendCommand(base64_encode($password));
            if (substr($res, 0, 3) !== '235') {
                fclose($fp);
                return ['success' => false, 'message' => "SMTP Password authentication failed: " . trim($res)];
            }
        }

        $res = $sendCommand("MAIL FROM: <{$fromEmail}>");
        if (substr($res, 0, 3) !== '250') {
            fclose($fp);
            return ['success' => false, 'message' => "MAIL FROM command failed: " . trim($res)];
        }

        $res = $sendCommand("RCPT TO: <{$toEmail}>");
        if (substr($res, 0, 3) !== '250') {
            fclose($fp);
            return ['success' => false, 'message' => "RCPT TO command failed: " . trim($res)];
        }

        $res = $sendCommand("DATA");
        if (substr($res, 0, 3) !== '354') {
            fclose($fp);
            return ['success' => false, 'message' => "DATA command failed: " . trim($res)];
        }

        $encodedFromName = '=?UTF-8?B?' . base64_encode($fromName) . '?=';
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $data  = "MIME-Version: 1.0\r\n";
        $data .= "Content-type: text/html; charset=UTF-8\r\n";
        $data .= "From: {$encodedFromName} <{$fromEmail}>\r\n";
        $data .= "To: <{$toEmail}>\r\n";
        $data .= "Subject: {$encodedSubject}\r\n";
        $data .= "Date: " . date('r') . "\r\n";
        $data .= "\r\n";
        $data .= $htmlContent;
        $data .= "\r\n.";

        $res = $sendCommand($data);
        $sendCommand("QUIT");
        fclose($fp);

        if (substr($res, 0, 3) === '250') {
            return ['success' => true, 'message' => 'Email sent successfully via SMTP!'];
        }

        return ['success' => false, 'message' => 'SMTP Data dispatch failed: ' . trim($res)];
    }

    /**
     * Log email event for diagnostic auditing
     */
    private static function logEmailSent(string $triggerKey, string $toEmail, string $subject, bool $success, string $message): void
    {
        $logFile = ROOT_PATH . '/database/email_logs.json';
        $logs = [];
        if (file_exists($logFile)) {
            $content = @file_get_contents($logFile);
            if ($content !== false) {
                $decoded = json_decode($content, true);
                if (is_array($decoded)) {
                    $logs = $decoded;
                }
            }
        }

        $newLog = [
            'id' => count($logs) + 1,
            'trigger' => $triggerKey,
            'recipient' => $toEmail,
            'subject' => $subject,
            'status' => $success ? 'Sent' : 'Failed',
            'details' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        array_unshift($logs, $newLog);
        $logs = array_slice($logs, 0, 50); // Keep last 50 logs
        @file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
    }
}

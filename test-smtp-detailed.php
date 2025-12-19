<?php
/**
 * Detailed SMTP Diagnostic
 */
require_once 'config.php';

echo "=== SMTP Configuration ===\n";
echo "SMTP_HOST: " . getenv('SMTP_HOST') . "\n";
echo "SMTP_PORT: " . getenv('SMTP_PORT') . "\n";
echo "SMTP_USER: " . getenv('SMTP_USER') . "\n";
echo "SMTP_PASS: " . (getenv('SMTP_PASS') ? '****' : 'NOT SET') . "\n";

echo "\n=== PHP Environment ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "OpenSSL: " . (extension_loaded('openssl') ? 'YES' : 'NO') . "\n";
echo "Sockets: " . (extension_loaded('sockets') ? 'YES' : 'NO') . "\n";
echo "cURL: " . (extension_loaded('curl') ? 'YES' : 'NO') . "\n";

echo "\n=== PHPMailer Check ===\n";
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "PHPMailer: AVAILABLE\n";
} else {
    echo "PHPMailer: NOT FOUND (will use native PHP mail())\n";
}

echo "\n=== Testing Connection ===\n";

// Test SMTP connection with fsockopen
$host = getenv('SMTP_HOST');
$port = getenv('SMTP_PORT');

if ($host && $port) {
    echo "Attempting to connect to {$host}:{$port}...\n";
    
    $fp = @fsockopen($host, $port, $errno, $errstr, 5);
    if ($fp) {
        echo "✓ Connection successful!\n";
        fclose($fp);
    } else {
        echo "✗ Connection failed: {$errstr} (Error: {$errno})\n";
    }
} else {
    echo "✗ SMTP_HOST or SMTP_PORT not set\n";
}

echo "\n=== Testing MailingService ===\n";

require_once 'Service/MailingService.php';

// Try to send test email
$mailer = new MailingService();
$testCourse = [
    'id' => 1,
    'title' => 'Test Course - ' . date('Y-m-d H:i:s'),
    'description' => 'This is a test to verify SMTP configuration',
    'created_at' => date('Y-m-d H:i:s')
];

echo "Sending test email...\n";
$result = $mailer->sendNewCourseEmail($testCourse, 'ammenezzi@gmail.com');

if ($result) {
    echo "✓ Email sent successfully!\n";
} else {
    echo "✗ Email failed to send\n";
    echo "Error: " . $mailer->getLastError() . "\n";
    // Native mail settings diagnostics if available
    if (method_exists($mailer, 'getNativeMailSettings')) {
        $settings = $mailer->getNativeMailSettings();
        echo "\nNative PHP mail settings:\n";
        foreach ($settings as $k => $v) {
            echo "  {$k}: {$v}\n";
        }
    }
}

echo "\n=== Recommendations ===\n";

if (!extension_loaded('openssl')) {
    echo "⚠ OpenSSL is not loaded. Enable it in php.ini\n";
}

if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo "✓ PHPMailer is available - good!\n";
} else {
    echo "⚠ PHPMailer not found - using native mail() which may not work with SMTP\n";
    echo "  To install: composer require phpmailer/phpmailer\n";
}

if (!$result) {
    echo "\n✓ Check your Gmail App Password:\n";
    echo "  1. Go to https://myaccount.google.com/apppasswords\n";
    echo "  2. Make sure 2FA is enabled\n";
    echo "  3. Generate a new App Password\n";
    echo "  4. Update SMTP_PASS in .env\n";
}
?>

<?php
/**
 * Test script to verify SMTP configuration
 */
require_once 'config.php';

echo "=== SMTP Configuration Test ===\n";
echo "SMTP_HOST: " . (getenv('SMTP_HOST') ?: 'NOT SET') . "\n";
echo "SMTP_PORT: " . (getenv('SMTP_PORT') ?: 'NOT SET') . "\n";
echo "SMTP_USER: " . (getenv('SMTP_USER') ?: 'NOT SET') . "\n";
echo "SMTP_PASS: " . (getenv('SMTP_PASS') ? '****' : 'NOT SET') . "\n";
echo "FROM_EMAIL: " . (getenv('FROM_EMAIL') ?: 'NOT SET') . "\n";

echo "\n=== Constants ===\n";
echo "SMTP_HOST (constant): " . (defined('SMTP_HOST') ? constant('SMTP_HOST') : 'NOT DEFINED') . "\n";
echo "SMTP_USER (constant): " . (defined('SMTP_USER') ? constant('SMTP_USER') : 'NOT DEFINED') . "\n";
echo "SMTP_PASS (constant): " . (defined('SMTP_PASS') ? '****' : 'NOT DEFINED') . "\n";

echo "\n=== MailingService Test ===\n";
require_once 'Service/MailingService.php';

$mailer = new MailingService();
$courseData = [
    'id' => 1,
    'title' => 'Test Course',
    'description' => 'This is a test course',
    'created_at' => date('Y-m-d H:i:s')
];

$result = $mailer->sendNewCourseEmail($courseData, 'ammenezzi@gmail.com');
echo "Email sent: " . ($result ? 'YES' : 'NO') . "\n";
if (!$result) {
    echo "Error: " . $mailer->getLastError() . "\n";
} else {
    echo "Success! Email sent to ammenezzi@gmail.com\n";
}
?>

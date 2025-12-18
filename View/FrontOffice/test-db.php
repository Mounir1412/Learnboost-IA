<?php
// Quick DB connectivity test (do not commit with credentials)
require_once __DIR__ . '/../../config.php';

header('Content-Type: text/plain; charset=utf-8');
echo "Testing DB connection...\n";
try {
    $db = config::getConnexion();
    $stmt = $db->query('SELECT DATABASE() as db');
    $row = $stmt->fetch();
    echo "Connected to DB: " . ($row['db'] ?? 'unknown') . "\n";
    echo "PDO driver: " . $db->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
    echo "OK";
} catch (Exception $e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage();
}

?>

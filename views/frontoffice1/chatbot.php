<?php
// Chatbot API removed - this endpoint is no longer available.
header('Content-Type: application/json; charset=utf-8');
http_response_code(410);
echo json_encode(['error' => 'Chatbot service has been removed']);
exit;
?>
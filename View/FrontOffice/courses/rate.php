<?php
// Minimal endpoint to receive rating POST and redirect back
require_once(__DIR__ . '/../../../Controller/RatingController.php');

$controller = new RatingController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'course_id' => $_POST['course_id'] ?? 0,
        'user_name' => trim($_POST['user_name'] ?? ''),
        'rating' => isset($_POST['rating']) ? (int)$_POST['rating'] : 0,
        'comment' => trim($_POST['comment'] ?? ''),
    ];

    $result = $controller->saveRating($data);
    // Redirect back to courseDetail with status message
    $course_id = (int)$data['course_id'];
    $msg = $result['message'] ?? '';
    $ok = $result['success'] ? 1 : 0;
    $location = "courseDetail.php?id={$course_id}&rating_ok={$ok}&rating_msg=" . urlencode($msg);
    header('Location: ' . $location);
    exit;
}

// If not POST, redirect to course list
header('Location: courseList.php');
exit;

<?php
require_once(dirname(__DIR__,3) . '/Controller/RatingController.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: ratingsList.php');
    exit;
}

$rc = new RatingController();
$ok = $rc->deleteRating((int)$id);
header('Location: ratingsList.php');
exit;

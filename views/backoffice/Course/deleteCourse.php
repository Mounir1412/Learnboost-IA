<?php
require_once __DIR__ . '/../../../controllers/CourseController.php';

$courseC = new CourseController();

// Vérifier que l'ID est fourni
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header('Location: courseList.php?error=no_id');
    exit();
}

$id = (int)$_GET["id"];

// Vérifier que le cours existe avant de le supprimer
$course = $courseC->showCourse($id);
if (!$course) {
    header('Location: courseList.php?error=not_found');
    exit();
}

// Supprimer le cours
$result = $courseC->deleteCourse($id);

if ($result) {
    header('Location: courseList.php?success=deleted');
} else {
    header('Location: courseList.php?error=delete_failed');
}
exit();
?>

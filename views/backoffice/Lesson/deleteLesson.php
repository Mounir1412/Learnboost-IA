<?php
require_once __DIR__ . '/../../../Controller/LessonController.php';

$lessonC = new LessonController();

// Vérifier que l'ID est fourni
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header('Location: lessonList.php?error=no_id');
    exit();
}

$id = (int)$_GET["id"];

// Vérifier que la leçon existe avant de la supprimer
$lesson = $lessonC->showLesson($id);
if (!$lesson) {
    header('Location: lessonList.php?error=not_found');
    exit();
}

// Supprimer la leçon
$result = $lessonC->deleteLesson($id);

if ($result) {
    header('Location: lessonList.php?success=deleted');
} else {
    header('Location: lessonList.php?error=delete_failed');
}
exit();
?>

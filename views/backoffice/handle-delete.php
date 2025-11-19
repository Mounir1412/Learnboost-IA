<?php
require "../../controllers/QuizController.php";
// Vérification que l'ID est bien présent et numérique
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID du quiz manquant ou invalide.");
}

$id = (int)$_GET['id'];

$controller = new QuizController();
$controller->deleteQuiz($id);

// Message de succès optionnel (via session ou GET)
$_SESSION['message'] = "Quiz supprimé avec succès !"; // Si tu utilises les sessions

header("Location: handle-list.php");
exit;
?>
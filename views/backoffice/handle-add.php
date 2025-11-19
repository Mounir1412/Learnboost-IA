<?php
require "../../controllers/QuizController.php";
// Traitement du formulaire
if (isset($_POST['ajouter'])) {
    $quiz = new Quiz(
        title:            $_POST['title'],
        time_in_minutes:  (int)$_POST['time_in_minutes'],
        description:      $_POST['description']
        // created_at est géré automatiquement dans le constructeur
    );

    $controller = new QuizController();
    $controller->addQuiz($quiz);

    header("Location: handle-list.php"); // Change le nom selon ta page de liste
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Quiz</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        label { display: inline-block; width: 180px; }
        input, textarea { width: 300px; padding: 8px; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>

<h1>Ajouter un nouveau Quiz</h1>

<form method="POST">

    <label>Titre du quiz :</label>
    <input type="text" name="title" required><br><br>

    <label>Temps autorisé (en minutes) :</label>
    <input type="number" name="time_in_minutes" min="1" max="300" value="30" required><br><br>

    <label>Description :</label><br>
    <textarea name="description" rows="5" required></textarea><br><br>

    <button type="submit" name="ajouter">Ajouter le Quiz</button>
</form>

<br>
<a href="handle-list.php">Retour à la liste des quizzes</a>

</body>
</html>
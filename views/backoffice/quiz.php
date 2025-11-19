<?php
session_start();

require "../../Controller/QuizController.php";
require "../../models/quiz.php";

$controller = new QuizController();
$errors = [];
$success = "";

// --- AJOUT QUIZ ---
if (isset($_POST['add'])) {

    // Validation via controller
    $errors = $controller->validateAdd($_POST);

    if (empty($errors)) {

        $quiz = new Quiz(
            $_POST['title'],
            (int)$_POST['time_in_minutes'],
            $_POST['description']
        );

        if ($controller->addQuiz($quiz)) {
            header("Location: quiz-back.php?added=1");
            exit();
        } else {
            $errors[] = "Erreur lors de l’ajout du quiz.";
        }
    }
}

// --- SUPPRESSION ---
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    if ($controller->deleteQuiz($id)) {
        header("Location: quiz-back.php?deleted=1");
        exit();
    } else {
        $errors[] = "Erreur lors de la suppression.";
    }
}

//$quizzes = $controller->getAllQuizzes();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Quizzes</title>

    <!-- ====== TON ANCIEN DESIGN (OPTION 1) ====== -->
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        input, textarea, button { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        textarea { resize: vertical; min-height: 100px; }
        button { background: #007bff; color: white; font-size: 16px; cursor: pointer; }
        button:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
        .success { color: #28a745; font-weight: bold; }
        .error   { color: #dc3545; font-weight: bold; }
        .actions a { color: #dc3545; text-decoration: none; }
        .actions a:hover { text-decoration: underline; }
    </style>
</head>

<body>

<div class="container">

    <h2>Gestion des Quizzes</h2>

    <!-- Messages -->
    <?php 
    if (!empty($errors)) {
        echo "<p class='error'>";
        foreach ($errors as $e) echo "- $e<br>";
        echo "</p>";
    }

    if (isset($_GET['added'])) {
        echo "<p class='success'>Quiz ajouté avec succès.</p>";
    }

    if (isset($_GET['deleted'])) {
        echo "<p class='success'>Quiz supprimé avec succès.</p>";
    }
    ?>

    <!-- FORMULAIRE AJOUT QUIZ -->
    <form method="POST" onsubmit="return validateQuizForm()">

        <input type="text" id="title" name="title" placeholder="Titre du quiz">

        <input type="number" id="time_in_minutes" name="time_in_minutes" placeholder="Temps (minutes)">

        <textarea id="description" name="description" placeholder="Description du quiz"></textarea>

        <button type="submit" name="add">Ajouter le Quiz</button>

    </form>

    <hr>

    <!-- LISTE DES QUIZZES -->
    <h2>Liste des Quizzes (<?= count($quizzes) ?>)</h2>

    <?php if (empty($quizzes)): ?>
        <p>Aucun quiz pour le moment.</p>

    <?php else: ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Temps</th>
                <th>Description</th>
                <th>Date création</th>
                <th>Action</th>
            </tr>

            <?php foreach ($quizzes as $q): ?>
            <tr>
                <td><?= $q->getId() ?></td>
                <td><strong><?= htmlspecialchars($q->getTitle()) ?></strong></td>
                <td><?= $q->getTimeInMinutes() ?> min</td>
                <td><?= htmlspecialchars(substr($q->getDescription(), 0, 60)) ?>...</td>
                <td><?= date('d/m/Y H:i', strtotime($q->getCreatedAt())) ?></td>
                <td class="actions">
                    <a href="?delete=<?= $q->getId() ?>" onclick="return confirm('Supprimer ce quiz ?');">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>

    <?php endif; ?>

</div>

<script src="validation.js"></script>

</body>
</html>
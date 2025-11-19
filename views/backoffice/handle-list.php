<?php
require "../../controllers/QuizController.php";
$controller = new QuizController();
$quizzes = $controller->getAllQuizzes(); // Doit retourner un tableau d'objets Quiz ou un tableau associatif
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Quizzes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f1f1f1; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .actions a { margin-right: 15px; }
        .btn-add {
            display: inline-block; margin-top: 20px; padding: 12px 24px;
            background: #28a745; color: white; border-radius: 5px; font-weight: bold;
        }
        .btn-add:hover { background: #218838; }
        .no-quiz { text-align: center; color: #666; padding: 40px; font-size: 1.2em; }
    </style>
</head>
<body>

<h1>Liste des Quizzes</h1>

<?php if (empty($quizzes)): ?>
    <p class="no-quiz">Aucun quiz disponible pour le moment.</p>
<?php else: ?>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Titre</th>
        <th>Temps (minutes)</th>
        <th>Description</th>
        <th>Date de création</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($quizzes as $quiz): ?>
        <tr>
            <td><?= htmlspecialchars($quiz['id'] ?? $quiz->getId()) ?></td>
            <td><strong><?= htmlspecialchars($quiz['title'] ?? $quiz->getTitle()) ?></strong></td>
            <td><?= htmlspecialchars($quiz['time_in_minutes'] ?? $quiz->getTimeInMinutes()) ?> min</td>
            <td><?= htmlspecialchars($quiz['description'] ?? $quiz->getDescription()) ?></td>
            <td><?= htmlspecialchars($quiz['created_at'] ?? $quiz->getCreatedAt()) ?></td>
            <td class="actions">
                <a href="updateQuiz.php?id=<?= $quiz['id'] ?? $quiz->getId() ?>">Modifier</a>
                <a href="deleteQuiz.php?id=<?= $quiz['id'] ?? $quiz->getId() ?>"
                   onclick="return confirm('Voulez-vous vraiment supprimer ce quiz ?');"
                   style="color: #dc3545;">
                    Supprimer
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php endif; ?>

<br>
<a href="handle-add.php" class="btn-add">Ajouter un nouveau quiz</a>

</body>
</html>
<?php
require "../../controllers/QuizController.php";

$controller = new QuizController();

// Récupération sécurisée de l'ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID du quiz manquant ou invalide.");
}

$id = (int)$_GET['id'];

// Récupération du quiz à modifier
$quiz = $controller->getQuizById($id);

if (!$quiz) {
    die("Quiz non trouvé.");
}

// Traitement du formulaire de modification
if (isset($_POST['modifier'])) {
    // Mise à jour des données (on ne touche pas à created_at)
    $quiz->setTitle($_POST['title']);
    $quiz->setTimeInMinutes((int)$_POST['time_in_minutes']);
    $quiz->setDescription($_POST['description']);

    // Optionnel : mise à jour du mot de passe seulement s'il est saisi
    // (ici on ne gère pas de mot de passe pour un quiz, donc pas nécessaire)

    if ($controller->updateQuiz($quiz,$id)) {
        $_SESSION['message'] = "Quiz modifié avec succès !";
        header("Location: listQuizzes.php");
        exit;
    } else {
        $error = "Erreur lors de la modification.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Quiz</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f8f9fa; }
        h1 { color: #333; }
        form { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); max-width: 600px; }
        label { display: block; margin: 15px 0 5px; font-weight: bold; color: #555; }
        input[type="text"], input[type="number"], textarea {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;
        }
        textarea { height: 100px; resize: vertical; }
        button {
            margin-top: 20px; padding: 12px 30px; background: #007bff; color: white;
            border: none; border-radius: 4px; font-size: 16px; cursor: pointer;
        }
        button:hover { background: #0056b3; }
        .back-link { margin-top: 20px; display: inline-block; color: #666; }
        .error { color: #dc3545; font-weight: bold; margin: 10px 0; }
    </style>
</head>
<body>

<h1>Modifier le Quiz</h1>

<?php if (isset($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Titre du quiz :</label>
    <input type="text" name="title" value="<?= htmlspecialchars($quiz->getTitle()) ?>" required>

    <label>Temps autorisé (en minutes) :</label>
    <input type="number" name="time_in_minutes" min="1" max="300"
           value="<?= htmlspecialchars($quiz->getTimeInMinutes()) ?>" required>

    <label>Description :</label>
    <textarea name="description" required><?= htmlspecialchars($quiz->getDescription()) ?></textarea>

    <button type="submit" name="modifier">Enregistrer les modifications</button>
</form>

<br>
<a href="handle-list.php" class="back-link">Retour à la liste des quizzes</a>

</body>
</html>
<?php
// Use dirname-based includes so paths are correct regardless of working dir.
$base = dirname(__DIR__, 2); // goes from View/BackOffice -> View -> learnboostai (project inner folder)

// Model and Controller includes
if (file_exists($base . '/Model/Course.php')) {
    require_once $base . '/Model/Course.php';
} else {
    trigger_error('Missing Model: ' . $base . '/Model/Course.php', E_USER_WARNING);
}

if (file_exists($base . '/Controller/CourseController.php')) {
    require_once $base . '/Controller/CourseController.php';
} else {
    trigger_error('Missing Controller: ' . $base . '/Controller/CourseController.php', E_USER_WARNING);
}

// Include header (template). Use safe path and fallback.
$assetsPath = __DIR__ . '/assets';
if (file_exists(__DIR__ . '/assets/header.php')) {
    require_once __DIR__ . '/assets/header.php';
} else {
    // Minimal fallback header to avoid fatal errors
    echo "<!doctype html><html><head><meta charset=\"utf-8\"><title>BackOffice - Verification</title></head><body><main class=\"container\">";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'Non Terminé';

    // Créer un nouvel objet Course (constructor may accept id first or not)
    // Try both constructor signatures safely.
    try {
        $course1 = new Course(null, $title, $description, $status);
    } catch (ArgumentCountError $e) {
        // Fallback: try (title, description, status)
        $course1 = new Course($title, $description, $status);
    }

    echo "<h2>Données du cours créé :</h2>";
    echo "<p><strong>Titre :</strong> " . htmlspecialchars($course1->getTitle()) . "</p>";
    echo "<p><strong>Description :</strong> " . htmlspecialchars($course1->getDescription()) . "</p>";
    echo "<p><strong>Statut :</strong> " . htmlspecialchars($course1->getStatus()) . "</p>";

    echo "<h2>Affichage avec var_dump :</h2>";

    // Ajouter le cours à la base de données
    $controller = new CourseController();
    $newId = $controller->addCourse($course1);
    
    if ($newId) {
        echo "<p style='color: green;'>Cours ajouté avec succès! ID: " . $newId . "</p>";
        
        // Afficher le cours depuis la base de données
        $courseFromDB = $controller->showCourse($newId);
        echo "<h2>Cours depuis la base de données :</h2>";
        if ($courseFromDB) {
            echo "<pre>";
            print_r($courseFromDB);
            echo "</pre>";
        }
    } else {
        echo "<p style='color: red;'>Erreur lors de l'ajout du cours</p>";
    }
} else {
    echo "<p>Aucune donnée reçue. Veuillez soumettre le formulaire.</p>";
}
?>
<?php require_once __DIR__ . '/assets/footer.php'; ?>
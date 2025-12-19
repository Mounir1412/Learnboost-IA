<?php
// Script de diagnostic pour vérifier les chemins

echo "<h2>Diagnostic des chemins d'accès</h2>";
echo "<p><strong>__DIR__ (Verification.php):</strong> " . __DIR__ . "</p>";
echo "<p><strong>Fichier courant:</strong> " . __FILE__ . "</p>";

// Test des chemins
$paths = [
    "CourseController" => __DIR__ . "/../../../Controller/CourseController.php",
    "ModuleController" => __DIR__ . "/../../../Controller/ModuleController.php",
    "LessonController" => __DIR__ . "/../../../Controller/LessonController.php",
    "Course Model" => __DIR__ . "/../../../Model/Course.php",
    "Module Model" => __DIR__ . "/../../../Model/Module.php",
    "Lesson Model" => __DIR__ . "/../../../Model/Lesson.php",
];

echo "<h3>Vérification des fichiers :</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Fichier</th><th>Chemin calculé</th><th>Existe ?</th></tr>";

foreach ($paths as $name => $path) {
    $exists = file_exists($path) ? "✅ OUI" : "❌ NON";
    echo "<tr>";
    echo "<td>" . htmlspecialchars($name) . "</td>";
    echo "<td><small>" . htmlspecialchars($path) . "</small></td>";
    echo "<td>" . $exists . "</td>";
    echo "</tr>";
}

echo "</table>";

// Essayer les includes
echo "<h3>Test d'inclusion :</h3>";
try {
    require_once __DIR__ . "/../../../Model/Course.php";
    echo "<p>✅ Course.php inclus avec succès</p>";
} catch (Exception $e) {
    echo "<p>❌ Erreur: " . $e->getMessage() . "</p>";
}

try {
    require_once __DIR__ . "/../../../Controller/CourseController.php";
    echo "<p>✅ CourseController.php inclus avec succès</p>";
} catch (Exception $e) {
    echo "<p>❌ Erreur: " . $e->getMessage() . "</p>";
}
?>

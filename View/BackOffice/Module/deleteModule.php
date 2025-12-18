<?php
// Delete a module by ID and redirect back to the module list
require_once __DIR__ . '/../../../Controller/ModuleController.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$moduleC = new ModuleController();

if ($id > 0) {
    $ok = $moduleC->deleteModule($id);
    if ($ok) {
        header('Location: moduleList.php?deleted=1');
        exit;
    } else {
        // On failure, show an error message and controller debug info (local only)
        $err = method_exists($moduleC, 'getLastError') ? $moduleC->getLastError() : '';
        echo "<p>Erreur lors de la suppression du module.</p>";
        if (!empty($err)) {
            echo "<div style=\"background:#fee;border:1px solid #f99;padding:8px;margin:8px 0;\"><strong>Détail:</strong> " . htmlspecialchars($err) . "</div>";
        }
        echo "<p><a href=\"moduleList.php\">Retour à la liste</a></p>";
        exit;
    }
} else {
    header('Location: moduleList.php');
    exit;
}


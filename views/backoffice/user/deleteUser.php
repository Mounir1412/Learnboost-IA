<?php
require_once '../../../controllers/UserController.php';

if (isset($_GET['id'])) {
    $userC = new UserController();
    $userC->deleteUser($_GET['id']);
}

// Après suppression → retour vers la liste
$root = '/validf1';
header('Location: ' . $root . '/views/backoffice/index1.php');
exit;

?>

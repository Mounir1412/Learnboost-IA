<?php
require_once '../../../controller/UserController.php';

if (isset($_GET['id'])) {
    $userC = new UserController();
    $userC->deleteUser($_GET['id']);
}

// Après suppression → retour vers la liste
header('Location: listUser.php');
exit();
?>

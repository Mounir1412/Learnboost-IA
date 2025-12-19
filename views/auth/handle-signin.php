<?php
session_start();
require_once __DIR__ . '/../../controllers/UserController.php';

$controller = new UserController();

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = $controller->getUserByEmail($email);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['authenticated'] = true;

            header('Location: ../../views/frontoffice');
            exit();
        } else {
            echo 'Incorrect password';
        }
    } else {
        echo 'Account not found';
    }
} else {
    echo 'Please provide email and password';
}

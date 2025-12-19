<?php
session_start();
require_once '../../../controller/UserController.php';

$controller = new UserController();
$message = "";
$message_type = "";

if (!isset($_GET['token'])) {
    die("Token manquant.");
}

$token = $_GET['token'];
$user = $controller->getUserByToken($token);

if (!$user || strtotime($user['reset_expires']) < time()) {
    die("Lien expiré ou invalide.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if ($password !== $confirm) {
        $message = "Les mots de passe ne correspondent pas.";
        $message_type = "error";
    } else {
        $controller->updatePassword($user['id'], $password);
        $controller->clearResetToken($user['id']);

        $message = "Mot de passe réinitialisé avec succès. Vous pouvez vous connecter.";
        $message_type = "success";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser mot de passe - LearnBoost IA</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            min-height: 100vh;
            background: url('assets/images/ai-education-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Overlay flou */
        body::before {
            content: "";
            position: absolute;
            top:0; left:0;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.5);
            backdrop-filter: blur(5px);
            z-index: 0;
        }

        header {
            position: relative;
            z-index: 2;
            background: rgba(26,115,232,0.9);
            padding: 15px 50px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .logo {
            font-weight: bold;
            font-size: 20px;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin-left: 25px;
            font-weight: 500;
        }

        header nav a:hover {
            text-decoration: underline;
        }

        .container {
            position: relative;
            z-index: 2;
            max-width: 400px;
            margin: 80px auto;
            background: rgba(255,255,255,0.95);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #1A73E8;
            margin-bottom: 30px;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #1A73E8;
        }

        input[type="password"]:focus {
            border-color: #1A73E8;
            outline: none;
            box-shadow: 0 0 8px rgba(26,115,232,0.5);
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #1A73E8;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background: #0d47a1;
        }

        .message {
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .message.success {
            background: #D0E9FF;
            color: #004d40;
        }

        .message.error {
            background: #FFCDD2;
            color: #c0392b;
        }

        .link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #1A73E8;
            text-decoration: none;
            font-weight: bold;
        }

        .link:hover {
            color: #0d47a1;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">LearnBoost IA</div>
    <nav>
        <a href="#">Accueil</a>
        <a href="#">A propos</a>
        <a href="#">Contact</a>
        <a href="#">Mon compte</a>
    </nav>
</header>

<div class="container">
    <h2>Réinitialiser votre mot de passe</h2>

    <?php if (!empty($message)): ?>
        <div class="message <?= $message_type ?>"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="password" name="password" placeholder="Nouveau mot de passe" required>
        <input type="password" name="confirm" placeholder="Confirmer mot de passe" required>
        <button type="submit">Réinitialiser</button>
    </form>

    <a class="link" href="login.php">Retour à la connexion</a>
</div>

</body>
</html>

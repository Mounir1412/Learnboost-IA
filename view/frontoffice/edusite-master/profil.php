<?php
session_start();
require_once '../../../model/config.php';

$pdo = config::getConnexion();
$message = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        // Vérifier l'utilisateur dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Connexion réussie : initialiser les variables de session
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_nom']   = $user['nom'];
            $_SESSION['user_prenom']= $user['prenom'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role']  = $user['role'];

            header("Location: profil.php");
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <style>
        body { font-family: Arial; background:#f5f5f5; }
        .login-container { width: 300px; margin: 100px auto; padding: 20px; background:white; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);}
        input { width:100%; padding:8px; margin:5px 0; border:1px solid #ccc; border-radius:4px;}
        button { width:100%; padding:8px; background:#1abc9c; color:white; border:none; border-radius:4px; cursor:pointer;}
        .message { color:red; margin-bottom:10px; text-align:center;}
    </style>
</head>
<body>

<div class="login-container">
    <h2>Connexion</h2>
    <?php if($message): ?>
        <div class="message"><?= htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit" name="login">Se connecter</button>
    </form>
</div>

</body>
</html>

<?php
session_start();
require_once '../../../model/config.php';

$pdo = config::getConnexion();

$erreur = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {

        $sql = "SELECT * FROM user WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);

        try {
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_nom"] = $user["nom"];
                $_SESSION["user_prenom"] = $user["prenom"];
                $_SESSION["user_email"] = $user["email"];
                $_SESSION["user_role"] = $user["role"];

                header("Location: ./profil.php");
                exit;

            } else {
                $erreur = "Email ou mot de passe incorrect.";
            }

        } catch (PDOException $e) {
            $erreur = "Erreur serveur.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion - LearnBoost IA</title>
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

    .form-container {
        position: relative;
        z-index: 2;
        width: 380px;
        margin: 80px auto;
        background: rgba(255,255,255,0.95);
        border-radius: 15px;
        padding: 40px 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    h2 {
        text-align: center;
        color: #1A73E8;
        margin-bottom: 30px;
    }

    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        border: 1px solid #1A73E8;
    }

    input[type="text"]:focus, input[type="password"]:focus {
        border-color: #1A73E8;
        outline: none;
        box-shadow: 0 0 8px rgba(26,115,232,0.5);
    }

    .btn-submit {
        background: #1A73E8;
        border: none;
        padding: 12px;
        border-radius: 10px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        margin-top: 10px;
        font-size: 16px;
        transition: 0.3s;
    }

    .btn-submit:hover {
        background: #0d47a1;
    }

    .forgot-link {
        display: block;
        text-align: right;
        margin-top: -15px;
        margin-bottom: 15px;
        color: #1A73E8;
        text-decoration: none;
        font-size: 14px;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    .error-message {
        text-align: center;
        color: #c0392b;
        margin-bottom: 15px;
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

<div class="form-container">
<h2>Connexion</h2>

<?php if (!empty($erreur)) echo "<div class='error-message'>$erreur</div>"; ?>

<form method="post">
    <label>Email :</label>
    <input type="text" name="email" value="<?= htmlspecialchars($email) ?>" required>

    <label>Mot de passe :</label>
    <input type="password" name="password" required>

    <a class="forgot-link" href="forgot-password.php">Mot de passe oublié ?</a>

    <input type="submit" value="Se connecter" class="btn-submit">
</form>
</div>

</body>
</html>

<?php
session_start();
require_once '../../../model/config.php';

$pdo = config::getConnexion();

$erreur = "";
$email = "";
$password = "";

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

                // Redirection vers profil exact
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
<title>Connexion</title>
<style>
    body {
        background-color: #f5f8fa;
        font-family: Arial, sans-serif;
    }
    .top-section {
        height: 250px;
        background: #1ECBE1;
        border-bottom-left-radius: 120px;
    }
    .form-container {
        width: 380px;
        margin: -150px auto;
        background: white;
        border-radius: 20px;
        padding: 35px;
        border: 1px solid #ccc;
    }
    .form-container input {
        width: 100%;
        padding: 12px;
        margin-top: 10px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
    }
    .btn-submit {
        background: #1ECBE1;
        border: none;
        padding: 12px;
        border-radius: 10px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        width: 100%;
        margin-top: 20px;
        font-size: 17px;
    }
</style>
</head>

<body>
<div class="top-section"></div>

<div class="form-container">

<h2 style="text-align:center;">Connexion</h2>

<?php if (!empty($erreur)) echo "<p style='color:red; text-align:center;'>$erreur</p>"; ?>

<form method="post">
    <label>Email :</label>
    <input type="text" name="email" value="<?= htmlspecialchars($email) ?>">

    <label>Mot de passe :</label>
    <input type="password" name="password">

    <input type="submit" value="Se connecter" class="btn-submit">
</form>

</div>
</body>
</html>

<?php
session_start();
require_once '../../models/config1.php';

$pdo = config::getConnexion();

$erreur = "";
$nom = "";
$prenom = "";
$email = "";
$password = "";
$role = "etudiant";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $erreur = "Tous les champs sont obligatoires.";
    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (nom, prenom, email, password, role)
                VALUES (:nom, :prenom, :email, :password, :role)";
        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashed);
        $stmt->bindValue(':role', $role);

        try {
    $stmt->execute();
    
    // Redirection automatique vers login après inscription réussie
    header("Location: login.php?success=1");
    exit;
    
} catch (PDOException $e) {
    $erreur = "Erreur : " . $e->getMessage();
}
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>S'inscrire</title>

    <style>
        body {
    margin: 0;
    font-family: Arial, sans-serif;

    /* VRAI GRADIENT TURQUOISE + BLEU CLAIR */
    body {
    background-color: #f5f8fa; /* gris/blanc comme sur l’image */
}


    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-container {
    width: 380px;
    margin: -150px auto 40px auto;  /* remonte la carte */
    background: white;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}



.left {
    width: 45%;

    /* MÊME BLEU/TURQUOISE que ton image */
    background: linear-gradient(135deg, #37E4E0, #2CA8FF);

    padding: 40px;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.left h1 {
    margin-bottom: 20px;
}

.right {
    width: 55%;
    padding: 40px;
}

.title {
    font-size: 28px;
    margin-bottom: 20px;
    color: #333;
}

input, select {
    width: 100%;
    padding: 12px;
    border-radius: 5px;
     margin: 5px 0;
    border: 1px solid #ccc;
    font-size: 15px;
}

button {
    width: 100%;
    padding: 12px;
    margin: 10px 0;

    /* bouton turquoise */
    background: #2CA8FF;

    border: none;
    color: white;
    font-size: 18px;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background: #1998F0;
}

.error {
    color: red;
    font-size: 15px;
}
.top-section {
    height: 250px;
    background: linear-gradient(to right, #1ECBE1, #1757A6); /* turquoise → bleu */
    border-bottom-left-radius: 120px;
}


    </style>
</head>

<body>
    

<div class="top-section"></div>

<div class="container">

    <div class="left">
        <h1>Create Account</h1>
        <p>Dare to dream about the technology we could create together.</p>
    </div>

    <div class="right">

        <div class="title">Inscription</div>

        <?php 
        if (!empty($erreur)) {
            echo "<p class='error'>$erreur</p>";
        }
        ?>

        <form method="post">

            <label>Nom :</label>
            <input type="text" name="nom" value="<?php echo $nom; ?>">

            <label>Prénom :</label>
            <input type="text" name="prenom" value="<?php echo $prenom; ?>">

            <label>Email :</label>
            <input type="text" name="email" value="<?php echo $email; ?>">

            <label>Mot de passe :</label>
            <input type="password" name="password">

            <label>Rôle :</label>
            <select name="role">
                <option value="etudiant">Étudiant</option>
                <option value="enseignant">Enseignant</option>
            </select>

            <button type="submit">S'inscrire</button>

        </form>
    </div>
</div>

</body>
</html>

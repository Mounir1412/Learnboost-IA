<?php
require_once __DIR__ . '/../../../Controller/UserController.php';

$userC = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password'], $_POST['role'])
    ) {
        if (
            !empty($_POST['nom']) && !empty($_POST['prenom']) &&
            !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['role'])
        ) {
            // Créer un nouvel utilisateur
            $user = new User(
                null,               // id = null pour auto-increment
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['email'],
                $_POST['password'],
                $_POST['role']
            );

            // Ajouter l'utilisateur
            $userC->addUser($user);

            // Rediriger vers la liste
            header('Location: listUser.php');
            exit();
        } else {
            $error = 'Veuillez remplir tous les champs';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1abc9c, #16a3c7);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
            width: 400px;
        }

        h2 {
            text-align: center;
            color: #0b6e7c;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #0b6e7c;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 10px 12px;
            margin-top: 5px;
            border: 1px solid #b2e0de;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        input:focus,
        select:focus {
            border-color: #16a3c7;
            box-shadow: 0 0 5px rgba(22,163,199,0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 25px;
            background: #16a3c7;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            background: #138aa8;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Ajouter un utilisateur</h2>

        <?php if (!empty($error)) echo '<p class="error">' . $error . '</p>'; ?>

        <form method="POST">
            <label>Nom :</label>
            <input type="text" name="nom" required>

            <label>Prénom :</label>
            <input type="text" name="prenom" required>

            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Mot de passe :</label>
            <input type="password" name="password" required>

            <label>Rôle :</label>
            <select name="role" required>
                <option value="etudiant">Étudiant</option>
                <option value="enseignant">Enseignant</option>
            </select>

            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
</html>

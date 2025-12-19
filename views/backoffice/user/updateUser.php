<?php
require_once __DIR__ . '/../../../Controllers/UserController.php';

$userC = new UserController();
$error = '';
$user = null;

// Récupérer l'ID depuis GET ou POST
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    echo "Aucun ID fourni.";
    exit();
}

// Récupérer les informations de l'utilisateur
$user = $userC->showUser($id);

// Vérifier si le formulaire est soumis
if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'])) {
    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email'])) {

        // Récupérer les valeurs actuelles de password et role depuis $user
        $password = $user['password']; // ou $user->getPassword() si $user est un objet
        $role = $user['role'];         // ou $user->getRole()

        // Créer l'objet User complet pour la mise à jour
        $u = new User(
            $id,
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $password,
            $role
        );

        // Appeler la fonction updateUser
        $userC->updateUser($u, $id);

        // Rediriger vers la liste après mise à jour
        header('Location: listUser.php');
        exit();
    } else {
        $error = 'Veuillez remplir tous les champs';
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
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
        input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 5px;
            border: 1px solid #b2e0de;
            border-radius: 8px;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
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
        <h2>Modifier un utilisateur</h2>

        <?php if ($error) echo '<p class="error">' . $error . '</p>'; ?>

        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label>Nom :</label>
            <input type="text" name="nom" value="<?php echo $user['nom']; ?>" required>

            <label>Prénom :</label>
            <input type="text" name="prenom" value="<?php echo $user['prenom']; ?>" required>

            <label>Email :</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</body>
</html>

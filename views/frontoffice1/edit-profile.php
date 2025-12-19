<?php
session_start();
require_once '../../../model/config.php';

$pdo = config::getConnexion();

// Vérifier connexion
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// Lors de l'upload
if (isset($_POST['save'])) {

    if (!empty($_FILES['photo']['name'])) {

        $fileName = time() . "_" . basename($_FILES['photo']['name']);
        $targetPath = "uploads/" . $fileName;

        // Vérifier image valide
        $allowed = ['jpg','jpeg','png','gif'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $message = "Format non valide. Formats autorisés : JPG, PNG, GIF.";
        } else {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetPath)) {

                // Mise à jour dans la base
                $stmt = $pdo->prepare("UPDATE user SET photo=? WHERE id=?");
                $stmt->execute([$fileName, $_SESSION['user_id']]);

                $message = "Photo mise à jour avec succès ✔️";
            } else {
                $message = "Erreur lors du téléchargement.";
            }
        }
    } else {
        $message = "Veuillez choisir une photo.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la photo de profil</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .banner {
            background: linear-gradient(120deg, #40E0D0, #00CED1, #20B2AA);
        }
    </style>
</head>

<body class="bg-gray-100 py-10">

<div class="max-w-md mx-auto bg-white shadow-xl rounded-xl overflow-hidden">

    <div class="banner h-32"></div>

    <div class="p-8">

        <h2 class="text-2xl font-bold mb-6 text-center">Changer la photo de profil 📸</h2>

        <?php if ($message): ?>
            <p class="text-center text-teal-600 font-semibold mb-4"><?= $message ?></p>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">

            <label class="block font-semibold mb-2">Nouvelle photo :</label>

            <input type="file" name="photo" accept="image/*"
                   class="w-full bg-gray-50 border p-3 rounded mb-6 cursor-pointer">

            <button type="submit" name="save"
                    class="w-full bg-teal-500 text-white py-2 rounded-lg hover:bg-teal-600">
                Enregistrer ✔️
            </button>

            <a href="profil.php"
               class="block text-center mt-4 text-gray-500 hover:underline">
                Retour au profil
            </a>

        </form>
    </div>

</div>

</body>
</html>

<?php 

session_start();
require_once '../../../model/config.php';

$pdo = config::getConnexion();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "<p style='text-align:center; margin-top:50px; font-size:18px;'>Veuillez vous connecter pour voir votre profil.</p>";
    exit();
}

// Récupérer les informations de l'utilisateur depuis la table "user"
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT nom, prenom, role, email, password FROM user WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<p style='text-align:center; margin-top:50px; font-size:18px;'>Utilisateur non trouvé.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .header-bg {
            background: linear-gradient(135deg, #40E0D0, #00CED1, #20B2AA);
        }
        .btn-turquoise {
            background-color: #40E0D0;
            color: white;
        }
        .btn-turquoise:hover {
            background-color: #00CED1;
        }
    </style>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="w-full max-w-4xl bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="header-bg h-40 relative">
        <div class="absolute -bottom-12 left-10">
            <img src="avatar.png" alt="Avatar" class="w-24 h-24 rounded-full border-4 border-white">
        </div>
    </div>
    <div class="p-10 pt-16">
        <h1 class="text-3xl font-bold"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h1>
        <p class="text-gray-600 text-lg"><?= htmlspecialchars(ucfirst($user['role'])) ?></p>
        <p class="text-gray-500"><?= htmlspecialchars($user['email']) ?></p>

        <div class="mt-6 flex gap-3">
            <button class="btn-turquoise px-4 py-2 rounded-lg">Modifier le profil</button>
            <button class="border border-gray-300 px-4 py-2 rounded-lg">Paramètres</button>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4 text-center">
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Mot de passe</h2>
                <p>••••••••</p>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Rôle</h2>
                <p><?= htmlspecialchars(ucfirst($user['role'])) ?></p>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Nom</h2>
                <p><?= htmlspecialchars($user['nom']) ?></p>
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <h2 class="font-semibold mb-2">Prénom</h2>
                <p><?= htmlspecialchars($user['prenom']) ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>

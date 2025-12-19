<?php 
session_start();
require_once '../../../model/config.php';

$pdo = config::getConnexion();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "<p style='text-align:center; margin-top:50px; font-size:18px;'>Veuillez vous connecter pour voir votre profil.</p>";
    exit();
}

// Récupérer les infos
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT nom, prenom, role, email, photo FROM user WHERE id = ?");

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
    <title>Profil | <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Bannière turquoise simple */
        .banner {
            background: linear-gradient(120deg, #40E0D0, #00CED1, #20B2AA);
        }
    </style>
</head>

<body class="bg-gray-100 flex justify-center py-10">

<div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden">

    <!-- Bande turquoise -->
    <div class="banner h-40 relative">
        <div class="absolute -bottom-12 left-10">
            <?php
$photo = !empty($user['photo']) ? "uploads/" . $user['photo'] : "avatar.png";
?>

<img src="<?= $photo ?>" class="w-24 h-24 rounded-full border-4 border-white shadow-md">

        </div>
    </div>

    <!-- Contenu du profil -->
    <div class="p-10 pt-16">

        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold">
                    <?= htmlspecialchars($user['prenom'] . " " . $user['nom']) ?>
                </h1>
                <p class="text-gray-600 text-lg"><?= htmlspecialchars(ucfirst($user['role'])) ?></p>
                <p class="text-gray-500 text-sm"><?= htmlspecialchars($user['email']) ?></p>
            </div>

            <div>
                <a href="edit-profile.php" 
   class="bg-white border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-100">
    Modifier ✏️
</a>

            </div>
        </div>

        <!-- Informations -->
        <div class="mt-10 grid grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg">
                <h2 class="font-semibold mb-1 text-sm">Nom</h2>
                <p><?= htmlspecialchars($user['nom']) ?></p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
                <h2 class="font-semibold mb-1 text-sm">Prénom</h2>
                <p><?= htmlspecialchars($user['prenom']) ?></p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
                <h2 class="font-semibold mb-1 text-sm">Email</h2>
                <p><?= htmlspecialchars($user['email']) ?></p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg">
                <h2 class="font-semibold mb-1 text-sm">Mot de passe</h2>
                <p>••••••••</p>
            </div>
        </div>

    </div>
</div>

</body>
</html>

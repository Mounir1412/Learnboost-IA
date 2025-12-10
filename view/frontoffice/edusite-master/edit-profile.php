<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once '../../../model/config.php';
$pdo = config::getConnexion();

$message = "";
$success = "";

// Récupérer les informations actuelles de l'utilisateur
$sql = "SELECT nom, prenom, email FROM user WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_SESSION["user_id"]);
$stmt->execute();
$user = $stmt->fetch();

// Dossier pour stocker les photos
$uploadDir = 'uploads/';

// Créer le dossier s'il n'existe pas
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Chemin de la photo actuelle
$currentPhoto = $uploadDir . 'user_' . $_SESSION["user_id"] . '.jpg';
$userHasPhoto = file_exists($currentPhoto);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    
    // Gestion de l'upload de photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileName = 'user_' . $_SESSION["user_id"] . '.jpg';
        $uploadFile = $uploadDir . $fileName;
        
        // Vérifier le type de fichier
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['photo']['tmp_name']);
        
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
                $success = "Photo de profil mise à jour avec succès!";
                $userHasPhoto = true;
            } else {
                $message = "Erreur lors de l'upload de la photo.";
            }
        } else {
            $message = "Type de fichier non autorisé. Formats acceptés: JPEG, PNG, GIF.";
        }
    }
    
    // Option pour supprimer la photo
    if (isset($_POST['delete_photo'])) {
        if (file_exists($currentPhoto)) {
            unlink($currentPhoto);
            $success = "Photo de profil supprimée avec succès!";
            $userHasPhoto = false;
        }
    }
    
    // Mettre à jour les informations dans la base de données
    $sql_update = "UPDATE user SET nom = :nom, prenom = :prenom, email = :email WHERE id = :id";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindValue(':nom', $nom);
    $stmt_update->bindValue(':prenom', $prenom);
    $stmt_update->bindValue(':email', $email);
    $stmt_update->bindValue(':id', $_SESSION["user_id"]);
    
    try {
        $stmt_update->execute();
        
        // Mettre à jour la session
        $_SESSION["user_nom"] = $nom;
        
        if (empty($message) && empty($success)) {
            $success = "Profil mis à jour avec succès!";
        }
        
        // Recharger les données utilisateur
        $stmt->execute();
        $user = $stmt->fetch();
        
    } catch (PDOException $e) {
        $message = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Modifier le Profil</title>
    <style type="text/css">
        body {
            background-color: #f5f8fa;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .top-section {
            height: 250px;
            background: #1ECBE1;
            border-bottom-left-radius: 120px;
        }

        .profile-container {
            width: 500px;
            margin: -150px auto 40px auto;
            background: white;
            border-radius: 20px;
            padding: 35px;
            border: 1px solid #ccc;
        }

        h2 {
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .user-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            border: 3px solid #1ECBE1;
            overflow: hidden;
            background-color: #ddd;
        }

        .user-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #1757A6;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .btn:hover {
            opacity: 0.85;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-danger {
            background: #dc3545;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .actions {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="top-section"></div>

<div class="profile-container">

    <h2>Modifier votre profil</h2>

    <!-- Affichage de la photo de profil actuelle -->
    <div class="user-photo">
        <?php if ($userHasPhoto): ?>
            <img src="<?php echo $currentPhoto . '?t=' . time(); ?>" alt="Photo de profil">
        <?php else: ?>
            <div style="width:100%; height:100%; background:#ddd; display:flex; align-items:center; justify-content:center; color:#666;">
                <span>Aucune photo</span>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($message)): ?>
        <div class="message error"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label for="photo">Changer la photo de profil :</label>
            <input type="file" name="photo" id="photo" accept="image/jpeg,image/png,image/gif">
            <small>Formats acceptés: JPEG, PNG, GIF</small>
        </div>

        <?php if ($userHasPhoto): ?>
        <div style="text-align:center; margin:10px 0;">
            <button type="submit" name="delete_photo" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre photo de profil ?')">
                Supprimer la photo
            </button>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="actions">
            <button type="submit" class="btn">Enregistrer les modifications</button>
            <a href="profil.php" class="btn btn-secondary">Retour au profil</a>
        </div>
    </form>

</div>

</body>
</html>
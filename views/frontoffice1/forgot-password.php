<?php
session_start();
require_once "../../Controllers/UserController.php";

// PHPMailer
require_once "../../../PHPMailer/src/PHPMailer.php";
require_once "../../../PHPMailer/src/SMTP.php";
require_once "../../../PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$controller = new UserController();
$message = "";

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $user = $controller->getUserByEmail($email);

    if ($user) {
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));
        $controller->saveResetToken($user['id'], $token, $expires);

        $reset_link = "http://localhost/user/view/frontoffice/edusite-master/reset-password.php?token=" . $token;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'barab0175@gmail.com';  
            $mail->Password   = 'xaaq lyma bzhz yovp';         
            $mail->Port       = 587;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('no-reply@LearnBoostIA.com', 'LearnBoost IA');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe - LearnBoost IA';
            $mail->Body = "
                <div style='font-family:Arial,sans-serif; max-width:600px; margin:auto; padding:20px; border:1px solid #ddd; border-radius:10px; background:#f9f9f9;'>
                    <h2 style='color:#1A73E8;'>Bonjour {$user['prenom']},</h2>
                    <p>Vous avez demandé une réinitialisation de mot de passe sur <strong>LearnBoost IA</strong>.</p>
                    <p style='text-align:center; margin:30px 0;'>
                        <a href='$reset_link' style='background:#1A73E8; color:white; padding:15px 35px; text-decoration:none; border-radius:50px; font-weight:bold; font-size:16px;'>
                            Réinitialiser mon mot de passe
                        </a>
                    </p>
                    <p>Ce lien expire dans <strong>1 heure</strong>.</p>
                    <hr>
                    <small style='color:#666;'>Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.</small>
                </div>
            ";

            $mail->send();
            $message = "<strong>Email envoyé avec succès !</strong><br>Vérifiez votre boîte Mail";

        } catch (Exception $e) {
            $message = "Erreur d'envoi : " . $mail->ErrorInfo;
        }
    } else {
        $message = "Si cet email existe, un lien vous a été envoyé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié - LearnBoost IA</title>
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
            top: 0; left: 0;
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

        .container {
            position: relative;
            z-index: 2;
            max-width: 400px;
            margin: 80px auto;
            background: rgba(255,255,255,0.95);
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #1A73E8;
            margin-bottom: 30px;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #1A73E8;
        }

        input[type="email"]:focus {
            border-color: #1A73E8;
            outline: none;
            box-shadow: 0 0 8px rgba(26,115,232,0.5);
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #1A73E8;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        button:hover {
            background: #0d47a1;
        }

        .message {
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .message.success {
            background: #D0E9FF;
            color: #004d40;
        }

        .message.error {
            background: #F5B7B1;
            color: #c0392b;
        }

        .link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #1A73E8;
            text-decoration: none;
            font-weight: bold;
        }

        .link:hover {
            color: #0d47a1;
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

<div class="container">
    <h2>Mot de passe oublié ?</h2>

    <?php if ($message): ?>
        <div class="message <?= strpos($message, 'succès') !== false ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Entrez votre email" required
        value="<?= htmlspecialchars($email ?? '') ?>">
        <button type="submit" name="submit">Envoyer le lien de réinitialisation</button>
    </form>

    <a class="link" href="login.php">Retour à la connexion</a>
</div>

</body>
</html>

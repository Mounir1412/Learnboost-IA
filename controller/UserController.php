<?php

require_once 'model/UserModel.php';

class UserController {

    // -------------------------
    // LOGIN (connexion)
    // -------------------------
    public function login() {

        // Si le formulaire n'a pas été envoyé, on affiche juste la page login
        if (!isset($_POST['email'])) {
            require 'views/frontoffice/user/login.php';
            return;
        }

        // 1) Récupérer les données du formulaire
        $email = $_POST['email'];
        $password = $_POST['password'];

        // 2) Appeler le modèle
        $model = new UserModel();
        $user = $model->login($email, $password);

        // 3) Vérifier si l'utilisateur existe
        if ($user) {
            session_start();
            $_SESSION['user'] = [
                'nom' => $user->getNom(),
                'email' => $user->getEmail(),
                'role' => $user->getRole(),
                'niveau' => $user->getNiveau()
            ];

            // Aller au profil
            require 'views/frontoffice/user/profile.php';

        } else {
            // Erreur → retour login
            $error = "Email ou mot de passe incorrect";
            require 'views/frontoffice/user/login.php';
        }
    }

    // -------------------------
    // REGISTER (inscription)
    // -------------------------
    public function register() {

        // si le formulaire n'est pas envoyé → afficher la page inscription
        if (!isset($_POST['email'])) {
            require 'views/frontoffice/user/register.php';
            return;
        }

        // récupérer données
        $nom = $_POST['nom'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // créer un objet User
        $user = new User($nom, $email, $password);

        // appeler le modèle
        $model = new UserModel();
        $model->addUser($user);

        // retour au login
        $success = "Compte créé avec succès ! Connectez-vous.";
        require 'views/frontoffice/user/login.php';
    }


    // -------------------------
    // PROFIL
    // -------------------------
    public function profile() {
        session_start();

        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        require 'views/frontoffice/user/profile.php';
    }


    // -------------------------
    // LOGOUT
    // -------------------------
    public function logout() {
        session_start();
        session_destroy();

        header("Location: index.php?page=login");
        exit;
    }
}


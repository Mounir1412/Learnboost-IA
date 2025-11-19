<?php
require_once 'Database.php';
require_once 'User.php';

class UserModel {
    public function addUser(User $user) {
        $db = Database::getConnexion();
        $sql = "INSERT INTO users (nom, email, password, role, niveau)
        VALUES (:nom, :email, :password, :role, :niveau)";
        $query = $db->prepare($sql);
        $query->bindValue(':nom', $user->getNom());
        $query->bindValue(':email', $user->getEmail());
        $query->bindValue(':password', $user->getPassword());
        $query->bindValue(':role', $user->getRole());
        $query->bindValue(':niveau', $user->getNiveau());

        try {
    $query->execute();
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}


    
}


}

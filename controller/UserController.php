<?php
require __DIR__ . "/../model/config.php";
require __DIR__ . "/../model/user.php";

class UserController {

    // 🔥 Tendance des inscriptions
    public function getRegistrationTrend($days = 30) {
        return User::getAccountRegistrationTrend($days);
    }

    // Afficher tous les utilisateurs
    function getAllUsers() {
        $sql = "SELECT * FROM user"; 
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            echo ("Erreur : " . $e->getMessage());
        }
    }

    // Ajouter un utilisateur
    function addUser($user) {
        $sql = "INSERT INTO user (id, nom, prenom, email, password, role) 
                VALUES (NULL, :nom, :prenom, :email, :password, :role)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);

            $query->bindValue(':nom', $user->getNom());
            $query->bindValue(':prenom', $user->getPrenom());
            $query->bindValue(':email', $user->getEmail());
            $query->bindValue(':password', $user->getPassword());
            $query->bindValue(':role', $user->getRole());

            $query->execute();
        } catch (Exception $e) {
            echo ("Erreur : " . $e->getMessage());
        }
    }

    // Modifier utilisateur
    public function updateUser($user, $id) {
        $sql = "UPDATE user 
                SET nom = :nom, prenom = :prenom, email = :email 
                WHERE id = :id";

        $db = config::getConnexion();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':nom', $user->getNom());
        $stmt->bindValue(':prenom', $user->getPrenom());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':id', $id);

        try {
            $stmt->execute();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Supprimer
    public function deleteUser($id) {
        $sql = "DELETE FROM user WHERE id = :id";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        try {
            $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    // Récupérer par ID
    function showUser($id) {
        $sql = "SELECT * FROM user WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute(['id' => $id]);
            return $query->fetch();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    // -----------------------------
// Dashboard Counters
// -----------------------------
public function countUsers() {
    $sql = "SELECT COUNT(*) AS total FROM user";
    $db = config::getConnexion();
    return $db->query($sql)->fetch()['total'];
}

public function countTeachers() {
    $sql = "SELECT COUNT(*) AS total FROM user WHERE role = 'enseignant'";
    $db = config::getConnexion();
    return $db->query($sql)->fetch()['total'];
}

public function countStudents() {
    $sql = "SELECT COUNT(*) AS total FROM user WHERE role = 'etudiant'";
    $db = config::getConnexion();
    return $db->query($sql)->fetch()['total'];
}

}

?>

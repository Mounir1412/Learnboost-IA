<?php
require __DIR__ . "/../models/config1.php";
require __DIR__ . "/../models/user.php";

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

public function getUserByEmail($email) {
    $sql = "SELECT * FROM user WHERE email = :email";
    $db = config::getConnexion();
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // assure-toi d’avoir un array associatif
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
        return false;
    }
}


public function saveResetToken($id_user, $token, $expires) {
    $sql = "UPDATE user SET reset_token = :token, reset_expires = :expires WHERE id = :id";
    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':token', $token);
    $stmt->bindValue(':expires', $expires);
    $stmt->bindValue(':id', $id_user);
    $stmt->execute();
}


public function getUserByToken($token) {
    $sql = "SELECT * FROM user WHERE reset_token = :token";
    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':token', $token);
    $stmt->execute();
    return $stmt->fetch();
}


public function updatePassword($id, $password) {
    $sql = "UPDATE user SET password = :password WHERE id = :id";
    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}

public function clearResetToken($id) {
    $sql = "UPDATE user SET reset_token = NULL, reset_expires = NULL WHERE id = :id";
    $db = config::getConnexion();
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
}




}

?>

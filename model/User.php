<?php

class User {
    private ?int $id;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $role;  // admin, etudiant, enseignant

    // -------------------------
    // 🔥 AJOUT DE LA METHODE STATIQUE ICI
    // -------------------------
    public static function getAccountRegistrationTrend($days = 30) {
        $db = config::getConnexion();
        try {
            $query = $db->prepare("
                SELECT DATE(creation_date) as date, COUNT(*) as count 
                FROM ACCOUNTS 
                WHERE creation_date >= DATE_SUB(CURRENT_DATE(), INTERVAL :days DAY)
                GROUP BY DATE(creation_date)
                ORDER BY date
            ");
            $query->bindValue(':days', $days, PDO::PARAM_INT);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting registration trend: " . $e->getMessage());
            return [];
        }
    }
    // -------------------------

    // Constructeur
    function __construct($id, $nom, $prenom, $email, $password, $role) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    // Getters
    function getId() { return $this->id; }
    function getNom() { return $this->nom; }
    function getPrenom() { return $this->prenom; }
    function getEmail() { return $this->email; }
    function getPassword() { return $this->password; }
    function getRole() { return $this->role; }

    // Setters
    function setId($id) { $this->id = $id; }
    function setNom($nom) { $this->nom = $nom; }
    function setPrenom($prenom) { $this->prenom = $prenom; }
    function setEmail($email) { $this->email = $email; }
    function setPassword($password) { $this->password = $password; }
    function setRole($role) { $this->role = $role; }

    // méthode saisir
    function saisir($nom, $prenom, $email, $password, $role) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    // affichage
    function afficher() {
        echo "Nom : $this->nom<br>"
           . "Prénom : $this->prenom<br>"
           . "Email : $this->email<br>"
           . "Role : $this->role<br>";
    }
}

?>

<?php

class Database {

    // Attribut static (comme dans ton cours "config")
    private static $pdo = null;

    // Attributs pour la connexion (privés → encapsulation)
    private string $servername = "localhost";
    private string $username = "root";
    private string $password = "";
    private string $dbname = "learnboost";

    // Constructeur (COURS POO)
    public function __construct() {
        // Rien ici (connexion se fait dans getConnexion(), comme ton cours)
    }

    // Méthode static pour obtenir la connexion (COURS PDO)
    public static function getConnexion() {

        // Si la connexion n’existe pas encore → on la crée
        if (!isset(self::$pdo)) {

            // Variables locales comme dans ton cours PDO
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "learnboost";

            try {
                // Création de l’objet PDO (COURS PDO)
                self::$pdo = new PDO(
                    "mysql:host=$servername;dbname=$dbname",
                    $username,
                    $password,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,       // gestion erreur
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC    // fetch assoc
                    )
                );

            } catch (Exception $e) {
                // Gestion de l’erreur exactement comme TON COURS
                die('Erreur : ' . $e->getMessage());
            }
        }

        // Retourne l’objet PDO
        return self::$pdo;
    }

    // Destructeur (COURS POO)
    public function __destruct() {
        // Fermer la connexion (COURS PDO)
        self::$pdo = null;
    }
}

?>

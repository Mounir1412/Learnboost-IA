<?php
require_once __DIR__ . '/../models/config.php';
require_once __DIR__ . '/../models/quiz.php';

class QuizController {

    // Validation pour l'ajout
    public function validateAdd($data) {
        $errors = [];

        if (empty(trim($data['title'])) || strlen(trim($data['title'])) < 3) {
            $errors[] = "Le titre doit contenir au moins 3 caractères.";
        }

        if (empty($data['time_in_minutes']) || !is_numeric($data['time_in_minutes']) || $data['time_in_minutes'] < 1 || $data['time_in_minutes'] > 300) {
            $errors[] = "Le temps doit être un nombre entre 1 et 300 minutes.";
        }

        if (empty(trim($data['description'])) || strlen(trim($data['description'])) < 10) {
            $errors[] = "La description doit contenir au moins 10 caractères.";
        }

        return $errors;
    }

    // Validation pour la modification (même règles)
    public function validateEdit($data) {
        return $this->validateAdd($data); // Identique pour l'instant
    }

    // Récupérer tous les quizzes
    public function getAllQuiz() {
        $sql = "SELECT * FROM quiz ORDER BY id DESC";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);

            // Convertir chaque ligne en objet Quiz
            $quizzes = [];
            foreach ($result as $row) {
                $quiz = new quiz(
                    $row['title'],
                    (int)$row['time_in_minutes'],
                    $row['description']
                );
                $quiz->setId((int)$row['id']);
                $quiz->setCreatedAt($row['created_at'] ?? null);
                $quizzes[] = $quiz;
            }
            return $quizzes;

        } catch (Exception $e) {
            error_log("Erreur getAllQuiz : " . $e->getMessage());
            return [];
        }
    }

    // Ajouter un quiz
    public function addQuiz($quiz) {
        $sql = "INSERT INTO quiz (title, time_in_minutes, description) 
                VALUES (:title, :time, :description)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $result = $query->execute([
                ':title'       => $quiz->getTitle(),
                ':time'        => $quiz->getTimeInMinutes(),
                ':description' => $quiz->getDescription()
            ]);

            return $result ? $db->lastInsertId() : false;

        } catch (Exception $e) {
            error_log("Erreur addQuiz : " . $e->getMessage());
            return false;
        }
    }

    // Mettre à jour un quiz
    public function updateQuiz($quiz) {
        $sql = "UPDATE quiz 
                SET title = :title, 
                    time_in_minutes = :time, 
                    description = :description 
                WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            return $query->execute([
                ':title'       => $quiz->getTitle(),
                ':time'        => $quiz->getTimeInMinutes(),
                ':description' => $quiz->getDescription(),
                ':id'          => $quiz->getId()
            ]);

        } catch (Exception $e) {
            error_log("Erreur updateQuiz : " . $e->getMessage());
            return false;
        }
    }

    // Récupérer un quiz par ID
    public function getQuizById($id) {
        $db = config::getConnexion();

        try {
            $query = $db->prepare("SELECT * FROM quiz WHERE id = :id");
            $query->execute([':id' => $id]);
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if (!$row) return null;

            $quiz = new quiz(
                $row['title'],
                (int)$row['time_in_minutes'],
                $row['description']
            );
            $quiz->setId((int)$row['id']);
            $quiz->setCreatedAt($row['created_at'] ?? null);

            return $quiz;

        } catch (Exception $e) {
            error_log("Erreur getQuizById : " . $e->getMessage());
            return null;
        }
    }

    // Supprimer un quiz
    public function delete($id) {
        $sql = "DELETE FROM quiz WHERE id = :id";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            return $query->execute([':id' => $id]);

        } catch (Exception $e) {
            error_log("Erreur delete quiz : " . $e->getMessage());
            return false;
        }
    }
}
?>
<?php
require_once(dirname(__DIR__) . '/config.php');
require_once(dirname(__DIR__) . '/Model/Rating.php');

class RatingController {

    /**
     * Save a rating with simple anti-spam checks (limit per IP per course per hour)
     */
    public function saveRating(array $data): array {
        $db = config::getConnexion();

        $course_id = isset($data['course_id']) ? (int)$data['course_id'] : 0;
        $user_name = isset($data['user_name']) ? trim($data['user_name']) : null;
        $rating = isset($data['rating']) ? (int)$data['rating'] : 0;
        $comment = isset($data['comment']) ? trim($data['comment']) : null;
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;

        // Basic validation
        if ($course_id <= 0 || $rating < 1 || $rating > 5) {
            return ['success' => false, 'message' => 'Données invalides'];
        }

        // Anti-spam: limit one rating per IP per course per hour
        try {
            $stmt = $db->prepare('SELECT COUNT(*) AS cnt FROM ratings WHERE course_id = :course_id AND ip = :ip AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)');
            $stmt->execute([':course_id' => $course_id, ':ip' => $ip]);
            $row = $stmt->fetch();
            if ($row && isset($row['cnt']) && (int)$row['cnt'] > 0) {
                return ['success' => false, 'message' => 'Vous avez déjà évalué ce cours récemment'];
            }
        } catch (Exception $e) {
            error_log('Error checking anti-spam: ' . $e->getMessage());
        }

        // Minimal spam protection: require comment length >=3 when rating <=2 (negative feedback)
        if ($rating <= 2 && (!is_string($comment) || mb_strlen($comment) < 3)) {
            return ['success' => false, 'message' => 'Veuillez fournir un commentaire pour une note faible'];
        }

        // Persist using model
        $ratingModel = new Rating(null, $course_id, $user_name, $rating, $comment, $ip);
        $saved = $ratingModel->save();
        if ($saved) {
            return ['success' => true, 'message' => 'Merci pour votre avis'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'enregistrement'];
    }

    public function getRatingsByCourse(int $course_id, int $limit = 50): array {
        return Rating::listByCourse($course_id, $limit);
    }

    public function getAverage(int $course_id): float {
        return Rating::avgRatingByCourse($course_id);
    }

    public function deleteRating(int $id): bool {
        return Rating::deleteById($id);
    }
}

?>

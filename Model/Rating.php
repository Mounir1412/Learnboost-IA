<?php
require_once(dirname(__DIR__) . '/config.php');

class Rating {
    private ?int $id;
    private int $course_id;
    private ?string $user_name;
    private int $rating;
    private ?string $comment;
    private ?string $ip;
    private ?string $created_at;

    public function __construct(?int $id = null, int $course_id = 0, ?string $user_name = null, int $rating = 1, ?string $comment = null, ?string $ip = null, ?string $created_at = null) {
        $this->id = $id;
        $this->course_id = $course_id;
        $this->user_name = $user_name;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->ip = $ip;
        $this->created_at = $created_at;
    }

    // Save rating to DB
    public function save(): bool {
        $db = config::getConnexion();
        $sql = "INSERT INTO ratings (course_id, user_name, rating, comment, ip) VALUES (:course_id, :user_name, :rating, :comment, :ip)";
        $stmt = $db->prepare($sql);
        try {
            return $stmt->execute([
                ':course_id' => $this->course_id,
                ':user_name' => $this->user_name,
                ':rating' => $this->rating,
                ':comment' => $this->comment,
                ':ip' => $this->ip,
            ]);
        } catch (Exception $e) {
            error_log('Error saving rating: ' . $e->getMessage());
            return false;
        }
    }

    // Static helpers
    public static function listByCourse(int $course_id, int $limit = 50): array {
        $db = config::getConnexion();
        $sql = "SELECT * FROM ratings WHERE course_id = :course_id ORDER BY created_at DESC LIMIT :limit";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        try {
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log('Error fetching ratings: ' . $e->getMessage());
            return [];
        }
    }

    public static function avgRatingByCourse(int $course_id): float {
        $db = config::getConnexion();
        $sql = "SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt FROM ratings WHERE course_id = :course_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $row = $stmt->fetch();
            if ($row && isset($row['avg_rating'])) {
                return (float) round((float)$row['avg_rating'], 2);
            }
        } catch (Exception $e) {
            error_log('Error computing avg rating: ' . $e->getMessage());
        }
        return 0.0;
    }

    public static function countByCourse(int $course_id): int {
        $db = config::getConnexion();
        $sql = "SELECT COUNT(*) AS cnt FROM ratings WHERE course_id = :course_id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':course_id', $course_id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $row = $stmt->fetch();
            return isset($row['cnt']) ? (int)$row['cnt'] : 0;
        } catch (Exception $e) {
            error_log('Error counting ratings: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Return averages and counts grouped by course_id.
     *
     * Returns array like: [course_id => ['avg' => 4.25, 'count' => 10], ...]
     */
    public static function avgRatingsGroupedByCourse(): array {
        $db = config::getConnexion();
        $sql = "SELECT course_id, AVG(rating) AS avg_rating, COUNT(*) AS cnt FROM ratings GROUP BY course_id";
        $stmt = $db->prepare($sql);
        try {
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = [];
            foreach ($rows as $row) {
                $cid = isset($row['course_id']) ? (int)$row['course_id'] : 0;
                $result[$cid] = [
                    'avg' => isset($row['avg_rating']) ? (float) round((float)$row['avg_rating'], 2) : 0.0,
                    'count' => isset($row['cnt']) ? (int)$row['cnt'] : 0,
                ];
            }
            return $result;
        } catch (Exception $e) {
            error_log('Error computing grouped avg ratings: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Generic average helper: returns average rating for a given entity column and id.
     * Only allow whitelisted columns to avoid SQL injection.
     */
    public static function avgRatingByEntity(string $column, int $id): float {
        // whitelist columns we know exist in the ratings table
        $allowed = ['course_id'];
        if (!in_array($column, $allowed, true)) {
            error_log('avgRatingByEntity: disallowed column ' . $column);
            return 0.0;
        }
        $db = config::getConnexion();
        $sql = "SELECT AVG(rating) AS avg_rating FROM ratings WHERE $column = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && isset($row['avg_rating'])) {
                return (float) round((float)$row['avg_rating'], 2);
            }
        } catch (Exception $e) {
            error_log('Error computing avg rating by entity: ' . $e->getMessage());
        }
        return 0.0;
    }

    public static function deleteById(int $id): bool {
        $db = config::getConnexion();
        $sql = "DELETE FROM ratings WHERE id = :id";
        $stmt = $db->prepare($sql);
        try {
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log('Error deleting rating: ' . $e->getMessage());
            return false;
        }
    }
}

?>

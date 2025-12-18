<?php
/**
 * LessonController - Gère les opérations CRUD sur les leçons
 * 
 * Relations :
 *   - Une leçon appartient à un module (clé étrangère module_id)
 *   - Un module peut avoir plusieurs leçons
 */

require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/Lesson.php');

class LessonController
{
    /**
     * Last error message from controller operations (for debugging)
     * @var string
     */
    private $lastError = '';

    /**
     * Get the last error message
     */
    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * Helper: return array of column names for a table in the current database
     * @param string $table
     * @return array
     */
    private function getTableColumns(string $table): array
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':table', $table, PDO::PARAM_STR);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $cols = array_map(function ($r) { return $r['COLUMN_NAME']; }, $rows);
            return $cols;
        } catch (Exception $e) {
            // If information_schema is not accessible, return an empty array (caller must handle)
            error_log('getTableColumns error: ' . $e->getMessage());
            return [];
        }
    }
    /**
     * Récupère toutes les leçons
     */
    public function listLessons()
    {
        $sql = "SELECT * FROM lessons ORDER BY module_id, id";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log('Error listing lessons: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les leçons d'un module spécifique
     * 
     * @param int $module_id ID du module
     * @return array Liste des leçons du module
     */
    public function listLessonsByModule($module_id)
    {
        $sql = "SELECT * FROM lessons WHERE module_id = :module_id ORDER BY id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':module_id', $module_id, PDO::PARAM_INT);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            error_log('Error listing lessons by module: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère une leçon par son ID
     * 
     * @param int $id ID de la leçon
     * @return array|bool Données de la leçon ou false
     */
    public function showLesson($id)
    {
        $sql = "SELECT * FROM lessons WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);

        try {
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            error_log('Error showing lesson: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Alias pour showLesson (compatibilité)
     */
    public function getLessonById($id)
    {
        return $this->showLesson($id);
    }

    /**
     * Ajoute une nouvelle leçon
     * 
     * @param Lesson $lesson Objet leçon
     * @return int|bool ID de la nouvelle leçon ou false
     */
    public function addLesson(Lesson $lesson)
    {
        // Basic validation: module_id is required
        $moduleId = $lesson->getModuleId();
        if (empty($moduleId)) {
            $this->lastError = 'missing module_id';
            error_log('addLesson: missing module_id');
            return false;
        }

        $db = config::getConnexion();

        // Discover available columns for the lessons table.
        $cols = $this->getTableColumns('lessons');
        if (empty($cols)) {
            // If we couldn't detect columns, fall back to safe known set without videoUrl
            $cols = ['module_id', 'title', 'content', 'duration'];
        }

        // Map of logical fields to values
        $fieldValues = [
            'module_id' => (int)$moduleId,
            'title' => $lesson->getTitle(),
            'content' => $lesson->getContent(),
            // accept multiple possible DB column names for video
            'videoUrl' => $lesson->getVideoUrl(),
            'video_url' => $lesson->getVideoUrl(),
            'video' => $lesson->getVideoUrl(),
            'duration' => $lesson->getDuration() !== null ? (int)$lesson->getDuration() : 0,
        ];

        // Build insert using only columns that exist in the DB
        $insertCols = [];
        $placeholders = [];
        $valuesToBind = [];

        foreach ($fieldValues as $col => $val) {
            if (in_array($col, $cols, true)) {
                $insertCols[] = $col;
                $placeholders[] = ':' . $col;
                $valuesToBind[$col] = $val;
            }
        }

        if (empty($insertCols)) {
            $this->lastError = 'no matching columns in lessons table';
            error_log('addLesson: no matching columns in lessons table');
            return false;
        }

        $sql = 'INSERT INTO lessons (' . implode(', ', $insertCols) . ') VALUES (' . implode(', ', $placeholders) . ')';

        try {
            $query = $db->prepare($sql);
            // bind with sensible types
            foreach ($valuesToBind as $col => $val) {
                $param = ':' . $col;
                if (is_int($val)) {
                    $query->bindValue($param, $val, PDO::PARAM_INT);
                } else {
                    $query->bindValue($param, $val ?? '', PDO::PARAM_STR);
                }
            }

            $success = $query->execute();
            if ($success) {
                return $db->lastInsertId();
            }
            $this->lastError = 'unknown error during insert';
            return false;
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log('Error adding lesson: ' . $e->getMessage());
            return false;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error adding lesson: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour une leçon
     * 
     * @param int $id ID de la leçon
     * @param Lesson $lesson Objet leçon avec les données mises à jour
     * @return bool true si succès, false sinon
     */
    public function updateLesson($id, Lesson $lesson)
    {
        $db = config::getConnexion();

        // Determine available columns and only update those that exist
        $cols = $this->getTableColumns('lessons');
        if (empty($cols)) {
            error_log('updateLesson: cannot detect table columns');
            return false;
        }

        $fieldValues = [
            'module_id' => $lesson->getModuleId(),
            'title' => $lesson->getTitle(),
            'content' => $lesson->getContent(),
            'videoUrl' => $lesson->getVideoUrl(),
            'video_url' => $lesson->getVideoUrl(),
            'video' => $lesson->getVideoUrl(),
            'duration' => $lesson->getDuration() !== null ? (int)$lesson->getDuration() : 0,
        ];

        $setParts = [];
        $binds = [];
        foreach ($fieldValues as $col => $val) {
            if (in_array($col, $cols, true)) {
                $setParts[] = "$col = :$col";
                $binds[$col] = $val;
            }
        }

        if (empty($setParts)) {
            // Nothing to update
            error_log('updateLesson: no fields to update. Available columns: ' . implode(', ', $cols));
            return false;
        }

        $sql = 'UPDATE lessons SET ' . implode(', ', $setParts) . ' WHERE id = :id';

        try {
            error_log('updateLesson SQL: ' . $sql);
            error_log('updateLesson binds: ' . json_encode($binds));
            $query = $db->prepare($sql);
            foreach ($binds as $col => $val) {
                $param = ':' . $col;
                if (is_int($val)) {
                    $query->bindValue($param, $val, PDO::PARAM_INT);
                } else {
                    $query->bindValue($param, $val ?? '', PDO::PARAM_STR);
                }
            }
            $query->bindValue(':id', (int)$id, PDO::PARAM_INT);
            $result = $query->execute();
            error_log('updateLesson result: ' . ($result ? 'true' : 'false'));
            return $result;
        } catch (Exception $e) {
            error_log('Error updating lesson: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une leçon
     * 
     * @param int $id ID de la leçon
     * @return bool true si succès, false sinon
     */
    public function deleteLesson($id)
    {
        $db = config::getConnexion();
        $ownTransaction = false;
        try {
            // Start transaction only if none active
            if (!$db->inTransaction()) {
                $db->beginTransaction();
                $ownTransaction = true;
            }

            // Ensure lesson exists
            $select = $db->prepare('SELECT * FROM lessons WHERE id = :id');
            $select->bindValue(':id', $id, PDO::PARAM_INT);
            $select->execute();
            $row = $select->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                if ($ownTransaction) $db->rollBack();
                return false;
            }

            // Create archive table if missing and ensure archived_at column
            $db->exec('CREATE TABLE IF NOT EXISTS lessons_archive LIKE lessons');
            try {
                $db->exec("ALTER TABLE lessons_archive ADD COLUMN archived_at DATETIME NULL");
            } catch (Exception $e) {
                // ignore if column exists or other minor errors
            }

            // Insert into archive (preserve original columns order, then archived_at)
            $insertSql = 'INSERT INTO lessons_archive SELECT l.*, NOW() FROM lessons l WHERE l.id = :id';
            $ins = $db->prepare($insertSql);
            $ins->bindValue(':id', $id, PDO::PARAM_INT);
            $ins->execute();

            // Delete original
            $del = $db->prepare('DELETE FROM lessons WHERE id = :id');
            $del->bindValue(':id', $id, PDO::PARAM_INT);
            $del->execute();

            if ($ownTransaction) $db->commit();
            return true;
        } catch (Exception $e) {
            if ($ownTransaction && $db->inTransaction()) $db->rollBack();
            // When called inside a larger transaction, return false so caller handles rollback
            $this->lastError = $e->getMessage();
            error_log('Error deleting (archiving) lesson: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime toutes les leçons d'un module
     * (utile lors de la suppression d'un module)
     * 
     * @param int $module_id ID du module
     * @return bool true si succès, false sinon
     */
    public function deleteLessonsByModule($module_id, $parentOwnsTransaction = false)
    {
        $db = config::getConnexion();
        try {
            // Parent owns the transaction, so we just execute without managing it
            // Create archive table if missing and ensure archived_at column
            $db->exec('CREATE TABLE IF NOT EXISTS lessons_archive LIKE lessons');
            try {
                $db->exec("ALTER TABLE lessons_archive ADD COLUMN archived_at DATETIME NULL");
            } catch (Exception $e) {}

            // Archive lessons for the module
            $ins = $db->prepare('INSERT INTO lessons_archive SELECT l.*, NOW() FROM lessons l WHERE l.module_id = :module_id');
            $ins->bindValue(':module_id', $module_id, PDO::PARAM_INT);
            $ins->execute();

            // Delete original lessons
            $del = $db->prepare('DELETE FROM lessons WHERE module_id = :module_id');
            $del->bindValue(':module_id', $module_id, PDO::PARAM_INT);
            $del->execute();

            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error archiving/deleting lessons by module: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retourne le nombre total de leçons
     *
     * @return int
     */
    public function countLessons(): int
    {
        $sql = "SELECT COUNT(*) AS cnt FROM lessons";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            $row = $query->fetch();
            return isset($row['cnt']) ? (int)$row['cnt'] : 0;
        } catch (Exception $e) {
            error_log('Error counting lessons: ' . $e->getMessage());
            return 0;
        }
    }
}
?>

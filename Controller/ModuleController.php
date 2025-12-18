<?php
require_once(dirname(__DIR__) . '/config.php');
require_once(dirname(__DIR__) . '/Model/Module.php');
// LessonController used to remove dependent lessons when deleting a module
// Require the LessonController from the same Controller directory
require_once __DIR__ . '/LessonController.php';

class ModuleController {

    /**
     * Last error message for debugging
     * @var string
     */
    private $lastError = '';

    public function getLastError(): string {
        return $this->lastError;
    }

    // LIST ALL MODULES
    public function listModules() {
        $sql = "SELECT * FROM modules";
        $db = config::getConnexion();
        $this->lastError = '';
        try {
            $query = $db->query($sql);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // LIST MODULES FOR A GIVEN COURSE
    public function listModulesByCourse($course_id) {
        $sql = "SELECT * FROM modules WHERE course_id = :course_id ORDER BY id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':course_id', $course_id);

        try {
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // DELETE MODULE
    public function deleteModule($id, $parentOwnsTransaction = false) {
        $db = config::getConnexion();
        $ownTransaction = false;
        $this->lastError = '';
        try {
            // Only start transaction if parent doesn't own one and we're not already in one
            if (!$parentOwnsTransaction && !$db->inTransaction()) {
                $db->beginTransaction();
                $ownTransaction = true;
            }

            // archive lessons for this module (pass true if we started the transaction)
            $lessonController = new LessonController();
            $successLessons = $lessonController->deleteLessonsByModule($id, true);
            if ($successLessons === false) {
                throw new Exception('Failed to archive lessons for module ' . $id . '. LessonController error: ' . $lessonController->getLastError());
            }

            // Ensure modules_archive exists and has archived_at
            $db->exec('CREATE TABLE IF NOT EXISTS modules_archive LIKE modules');
            try { $db->exec("ALTER TABLE modules_archive ADD COLUMN archived_at DATETIME NULL"); } catch (Exception $e) {}

            // Archive module row
            $ins = $db->prepare('INSERT INTO modules_archive SELECT m.*, NOW() FROM modules m WHERE m.id = :id');
            $ins->bindValue(':id', (int)$id, PDO::PARAM_INT);
            $ins->execute();

            // Delete original module
            $del = $db->prepare('DELETE FROM modules WHERE id = :id');
            $del->bindValue(':id', (int)$id, PDO::PARAM_INT);
            $del->execute();

            if ($ownTransaction && $db->inTransaction()) {
                $db->commit();
            }
            return true;
        } catch (Exception $e) {
            if ($ownTransaction && $db->inTransaction()) {
                $db->rollBack();
            }
            $this->lastError = $e->getMessage();
            error_log('Error archiving/deleting module: ' . $e->getMessage());
            return false;
        }
    }

    // ADD MODULE
    public function addModule(Module $module) {
        $sql = "INSERT INTO modules (title, description, course_id) 
                VALUES (:title, :description, :course_id)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $success = $query->execute([
                'title' => $module->getTitle(),
                'description' => $module->getDescription(),
                'course_id' => $module->getCourseId(),
            ]);

            if ($success) {
                return $db->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            error_log('Error adding module: ' . $e->getMessage());
            return false;
        }
    }

    // UPDATE MODULE
    public function updateModule(Module $module, $id) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare(
                'UPDATE modules SET
                    title = :title,
                    description = :description,
                    course_id = :course_id
                WHERE id = :id'
            );

            $success = $query->execute([
                'id' => $id,
                'title' => $module->getTitle(),
                'description' => $module->getDescription(),
                'course_id' => $module->getCourseId(),
            ]);

            return $success;
        } catch (PDOException $e) {
            error_log('Error updating module: ' . $e->getMessage());
            return false;
        }
    }

    // SHOW SINGLE MODULE
    public function showModule($id) {
        $sql = "SELECT * FROM modules WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);

        try {
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            error_log('Error showing module: ' . $e->getMessage());
            return false;
        }
    }


    public function getModuleById($id) {
        return $this->showModule($id);
    }

    public function countModules(): int {
        $sql = "SELECT COUNT(*) AS cnt FROM modules";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            $row = $query->fetch();
            return isset($row['cnt']) ? (int)$row['cnt'] : 0;
        } catch (Exception $e) {
            error_log('Error counting modules: ' . $e->getMessage());
            return 0;
        }
    }
}
?>

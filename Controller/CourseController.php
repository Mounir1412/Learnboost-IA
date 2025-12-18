<?php
require_once(dirname(__DIR__) . '/config.php');
require_once(dirname(__DIR__) . '/Model/Course.php');

class CourseController {

    // CORRECTION : La méthode doit s'appeler listCourses() (avec un 'e')
    public function listCourses() {
        $sql = "SELECT * FROM courses";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    /**
     * Last error message for debugging
     * @var string
     */
    private $lastError = '';

    public function getLastError(): string {
        return $this->lastError;
    }

    public function deleteCourse($id) {
        $db = config::getConnexion();
        $ownTransaction = false;
        $this->lastError = '';
        try {
            if (!$db->inTransaction()) {
                $db->beginTransaction();
                $ownTransaction = true;
            }

            // Archive modules (and their lessons) for this course
            $moduleSql = 'SELECT id FROM modules WHERE course_id = :course_id';
            $stmt = $db->prepare($moduleSql);
            $stmt->bindValue(':course_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $modules = $stmt->fetchAll(PDO::FETCH_COLUMN);

            require_once(dirname(__DIR__) . '/Controller/ModuleController.php');
            $moduleController = new ModuleController();
            foreach ($modules as $mid) {
                $ok = $moduleController->deleteModule($mid, true);
                if ($ok === false) {
                    throw new Exception('Failed to delete module ' . $mid . '. Module error: ' . $moduleController->getLastError());
                }
            }

            // Ensure courses_archive exists and has archived_at
            $db->exec('CREATE TABLE IF NOT EXISTS courses_archive LIKE courses');
            try { $db->exec("ALTER TABLE courses_archive ADD COLUMN archived_at DATETIME NULL"); } catch (Exception $e) {}

            // Archive course
            $ins = $db->prepare('INSERT INTO courses_archive SELECT c.*, NOW() FROM courses c WHERE c.id = :id');
            $ins->bindValue(':id', $id, PDO::PARAM_INT);
            $ins->execute();

            // Delete original course
            $del = $db->prepare('DELETE FROM courses WHERE id = :id');
            $del->bindValue(':id', $id, PDO::PARAM_INT);
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
            error_log('Error archiving/deleting course: ' . $e->getMessage());
            return false;
        }
    }

    public function addCourse(Course $course) {
        $sql = "INSERT INTO courses (title, description, status) VALUES (:title, :description, :status)";
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $success = $query->execute([
                'title' => $course->getTitle(),
                'description' => $course->getDescription(),
                'status' => $course->getStatus(),
            ]);
            
            if ($success) {
                $courseId = $db->lastInsertId();
                
                // Send notification email for new course
                $courseData = [
                    'id' => $courseId,
                    'title' => $course->getTitle(),
                    'description' => $course->getDescription(),
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                require_once(dirname(__DIR__) . '/Service/MailingService.php');
                $mailer = new MailingService();
                $emailSent = $mailer->sendNewCourseEmail($courseData);
                
                if ($emailSent) {
                    error_log('New course notification email sent for course ID: ' . $courseId);
                } else {
                    error_log('Failed to send course notification email: ' . $mailer->getLastError());
                }
                
                return $courseId;
            }
            return false;
        } catch (Exception $e) {
            error_log('Error adding course: ' . $e->getMessage());
            return false;
        }
    }

    public function updateCourse(Course $course, $id) {
        try {
            $db = config::getConnexion();
            $query = $db->prepare(
                'UPDATE courses SET 
                    title = :title,
                    description = :description,
                    status = :status
                WHERE id = :id'
            );
            
            $success = $query->execute([
                'id' => $id,
                'title' => $course->getTitle(),
                'description' => $course->getDescription(),
                'status' => $course->getStatus(),
            ]);
            
            return $success;
        } catch (PDOException $e) {
            error_log('Error updating course: ' . $e->getMessage());
            return false;
        }
    }

    public function showCourse($id) {
        $sql = "SELECT * FROM courses WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);
        
        try {
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            error_log('Error showing course: ' . $e->getMessage());
            return false;
        }
    }

    public function getCourses() {
        return $this->listCourses();
    }

    public function countCourses(): int {
        $sql = "SELECT COUNT(*) AS cnt FROM courses";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            $row = $query->fetch();
            return isset($row['cnt']) ? (int)$row['cnt'] : 0;
        } catch (Exception $e) {
            error_log('Error counting courses: ' . $e->getMessage());
            return 0;
        }
    }
}
?>
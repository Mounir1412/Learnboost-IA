<?php
/**
 * Alternative MailingService with Demo Mode
 * Cette version sauvegarde les emails dans un fichier pour testing
 */

class MailingServiceDemo
{
    private $logFile = __DIR__ . '/../logs/emails_demo.log';
    private $lastError = '';

    public function __construct()
    {
        // Créer le répertoire logs s'il n'existe pas
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * Envoie un email de notification pour un nouveau cours (version demo)
     * 
     * @param array|object $course Données du cours
     * @param string $recipientEmail Email du destinataire
     * @return bool true si succès
     */
    public function sendNewCourseEmail($course, string $recipientEmail = 'ammenezzi@gmail.com'): bool
    {
        try {
            // Convertir l'objet en array si nécessaire
            if (is_object($course)) {
                $course = (array)$course;
            }

            $courseTitle = $course['title'] ?? 'Sans titre';
            $courseDescription = $course['description'] ?? 'Pas de description';
            $courseId = $course['id'] ?? 0;
            $courseDate = $course['created_at'] ?? date('Y-m-d H:i:s');

            // Créer une entrée de log
            $entry = [
                'timestamp' => date('Y-m-d H:i:s'),
                'to' => $recipientEmail,
                'subject' => 'Nouveau cours ajouté sur LearnBoost AI',
                'course_id' => $courseId,
                'course_title' => $courseTitle,
                'course_description' => $courseDescription,
                'course_date' => $courseDate,
                'status' => 'DEMO_SENT'
            ];

            // Écrire dans le fichier log
            $logEntry = json_encode($entry) . "\n";
            file_put_contents($this->logFile, $logEntry, FILE_APPEND);

            error_log('Demo email logged for course: ' . $courseTitle);
            return true;

        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('MailingServiceDemo error: ' . $this->lastError);
            return false;
        }
    }

    /**
     * Récupère tous les emails envoyés (pour debug)
     */
    public function getDemoLog(): array
    {
        if (!file_exists($this->logFile)) {
            return [];
        }

        $logs = [];
        $lines = file($this->logFile);
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $logs[] = json_decode($line, true);
            }
        }

        return array_reverse($logs); // Les plus récents en premier
    }

    /**
     * Vide les logs de demo
     */
    public function clearDemoLog(): bool
    {
        return file_put_contents($this->logFile, '') !== false;
    }
}
?>

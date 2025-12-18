<?php
/**
 * EmailLogService - Service pour logger les emails envoyés
 * Utile pour auditer et déboguer les envois d'email
 */

class EmailLogService
{
    private $logFile = __DIR__ . '/../logs/email.log';

    public function __construct()
    {
        // Créer le répertoire logs s'il n'existe pas
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    /**
     * Enregistre un email envoyé
     */
    public function logEmailSent(string $to, string $subject, string $type = 'course', int $typeId = 0): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $entry = sprintf(
            "[%s] To: %s | Type: %s (ID:%d) | Subject: %s\n",
            $timestamp,
            $to,
            $type,
            $typeId,
            $subject
        );
        
        file_put_contents($this->logFile, $entry, FILE_APPEND);
    }

    /**
     * Enregistre un email non envoyé
     */
    public function logEmailError(string $to, string $subject, string $error, string $type = 'course', int $typeId = 0): void
    {
        $timestamp = date('Y-m-d H:i:s');
        $entry = sprintf(
            "[%s] ERROR - To: %s | Type: %s (ID:%d) | Subject: %s | Error: %s\n",
            $timestamp,
            $to,
            $type,
            $typeId,
            $subject,
            $error
        );
        
        file_put_contents($this->logFile, $entry, FILE_APPEND);
    }

    /**
     * Récupère les derniers logs
     */
    public function getRecentLogs(int $lines = 50): array
    {
        if (!file_exists($this->logFile)) {
            return [];
        }

        $logs = file($this->logFile);
        return array_slice($logs, -$lines);
    }

    /**
     * Vide les logs
     */
    public function clearLogs(): bool
    {
        return file_put_contents($this->logFile, '') !== false;
    }
}
?>

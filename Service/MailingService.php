<?php
/**
 * MailingService - Service pour envoyer des emails automatiques
 * Utilise PHPMailer
 */

// Rely on Composer autoload (required in config.php) or compatibility shim to provide PHPMailer.
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;


class MailingService
{
    private string $smtpHost = 'smtp.gmail.com';
    private int    $smtpPort = 587;
    private string $smtpUser = '';
    private string $smtpPass = '';
    private string $fromEmail = '';
    private string $fromName  = 'LearnBoost AI';

    private string $lastError = '';

    public function __construct()
    {
        $this->loadCredentials();
    }

    /**
     * Charger les credentials depuis .env ou config.php
     */
    private function loadCredentials(): void
    {
        $this->smtpHost  = $_ENV['SMTP_HOST']  ?? getenv('SMTP_HOST')  ?: $this->smtpHost;
        $this->smtpPort  = (int) ($_ENV['SMTP_PORT'] ?? getenv('SMTP_PORT') ?: $this->smtpPort);
        $this->smtpUser  = $_ENV['SMTP_USER']  ?? getenv('SMTP_USER')  ?: '';
        $this->smtpPass  = $_ENV['SMTP_PASS']  ?? getenv('SMTP_PASS')  ?: '';
        $this->fromEmail = $_ENV['FROM_EMAIL'] ?? getenv('FROM_EMAIL') ?: $this->smtpUser;
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }

    /**
     * Envoi email nouveau cours
     */
    public function sendNewCourseEmail(array|object $course, string $recipientEmail = 'ammenezzi@gmail.com'): bool
    {
        if (is_object($course)) {
            $course = (array) $course;
        }

        $title = $course['title'] ?? 'Sans titre';
        $desc  = $course['description'] ?? 'Pas de description';
        $date  = $course['created_at'] ?? date('Y-m-d H:i:s');

        $subject = 'Nouveau cours ajouté sur LearnBoost AI';
        $link = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . '/learnboostai/View/FrontOffice/courses/courseList.php';

        $html = $this->buildHtmlEmail($title, $desc, $date, $link);

        return $this->sendWithPhpMailer($recipientEmail, $subject, $html);
    }

    /**
     * Envoi via PHPMailer
     */
    private function sendWithPhpMailer(string $to, string $subject, string $htmlContent): bool
    {
        if (!class_exists(PHPMailer::class)) {
            $this->lastError = 'PHPMailer not installed';
            return false;
        }

        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host       = $this->smtpHost;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->smtpUser;
            $mail->Password   = $this->smtpPass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->smtpPort;
            // Enable optional SMTP debug via env var SMTP_DEBUG (set to 2 for verbose output)
            $mail->SMTPDebug  = (getenv('SMTP_DEBUG') !== false) ? (int)getenv('SMTP_DEBUG') : 0;

            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlContent;
            $mail->AltBody = strip_tags($htmlContent);

            $mail->send();
            return true;

        } catch (PHPMailerException $e) {
            $msg = $e->getMessage();
            $this->lastError = 'PHPMailer Error: ' . $msg;
            // Add actionable hint for common Gmail auth issue
            if (stripos($msg, 'authenticate') !== false) {
                $this->lastError .= ' — Authentication failed. If you use Gmail, enable 2FA and create an App Password; set it in SMTP_PASS.';
            }
            error_log('Mailer error: ' . $this->lastError);

            // Fallback: try native PHP mail() as a last resort
            if ($this->sendWithNativePhp($to, $subject, $htmlContent)) {
                return true;
            }

            return false;
        }
    }

    /**
     * Try fallback using native PHP mail() and log diagnostic info on failure.
     */
    private function sendWithNativePhp(string $to, string $subject, string $htmlContent): bool
    {
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . ($this->fromName ?: $this->fromEmail) . " <" . $this->fromEmail . ">\r\n";
        $headers .= "X-Mailer: LearnBoost AI\r\n";

        $result = @mail($to, $subject, $htmlContent, $headers);
        if (!$result) {
            $smtp = ini_get('SMTP');
            $smtp_port = ini_get('smtp_port');
            $sendmail = ini_get('sendmail_path');
            $this->lastError = "PHP mail() failed. PHP ini settings - SMTP={$smtp}, smtp_port={$smtp_port}, sendmail_path={$sendmail}";
            error_log($this->lastError);
        }
        return $result;
    }

    /**
     * Return native mail PHP settings useful for debugging.
     */
    public function getNativeMailSettings(): array
    {
        return [
            'SMTP' => ini_get('SMTP'),
            'smtp_port' => ini_get('smtp_port'),
            'sendmail_path' => ini_get('sendmail_path'),
            'mail_log' => ini_get('mail.log'),
        ];
    }

    /**
     * Template HTML
     */
    private function buildHtmlEmail(string $title, string $description, string $date, string $link): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
body{font-family:Arial;background:#f4f4f4}
.container{max-width:600px;margin:auto;background:#fff;padding:20px;border-radius:8px}
.btn{display:inline-block;padding:12px 20px;background:#007bff;color:#fff;text-decoration:none;border-radius:5px}
</style>
</head>
<body>
<div class="container">
<h2>🎓 Nouveau cours disponible</h2>
<p><strong>Titre :</strong> {$title}</p>
<p>{$description}</p>
<p><strong>Date :</strong> {$date}</p>
<a href="{$link}" class="btn">Voir les cours</a>
<p style="margin-top:20px">— LearnBoost AI</p>
</div>
</body>
</html>
HTML;
    }

    /**
     * Envoi email générique
     */
    public function sendEmail(string $to, string $subject, string $htmlContent): bool
    {
        return $this->sendWithPhpMailer($to, $subject, $htmlContent);
    }
}

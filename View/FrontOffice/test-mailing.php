<?php
/**
 * Page de test du MailingService avec mode démo
 * Pour tester l'envoi d'emails avant de l'utiliser en production
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Service/MailingService.php';
require_once __DIR__ . '/../../Service/MailingServiceDemo.php';

$testEmail = 'ammenezzi@gmail.com';
$message = '';
$success = false;
$useDemo = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testEmail = $_POST['email'] ?? 'ammenezzi@gmail.com';
    $useDemo = isset($_POST['demo']) ? true : false;
    
    // Créer des données de test
    $testCourse = [
        'id' => rand(1, 1000),
        'title' => 'Test Course - ' . date('Y-m-d H:i:s'),
        'description' => 'Ceci est un cours de test pour vérifier le système de mailing automatique.',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    if ($useDemo) {
        // Mode démo
        $mailer = new MailingServiceDemo();
        $success = $mailer->sendNewCourseEmail($testCourse, $testEmail);
        
        if ($success) {
            $message = '✓ Email enregistré en mode DÉMO (pas d\'envoi réel)';
        } else {
            $message = '✗ Erreur: ' . $mailer->getLastError();
        }
    } else {
        // Mode réel
        $mailer = new MailingService();
        $success = $mailer->sendNewCourseEmail($testCourse, $testEmail);
        
        if ($success) {
            $message = '✓ Email envoyé avec succès à ' . htmlspecialchars($testEmail);
        } else {
            $message = '✗ Erreur lors de l\'envoi: ' . htmlspecialchars($mailer->getLastError());
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test MailingService</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        body { background: #f5f5f5; padding: 40px 0; }
        .test-container { max-width: 700px; margin: 0 auto; }
        .card { box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .alert { margin-top: 20px; }
        .demo-log { max-height: 300px; overflow-y: auto; background: #f8f9fa; border-radius: 4px; padding: 10px; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>
    <div class="test-container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">🧪 Test du MailingService</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Testez l'envoi d'email de notification de nouveau cours</p>
                
                <form method="POST" class="mt-4">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email destinataire:</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($testEmail); ?>" required>
                        <small class="form-text text-muted">Par défaut: ammenezzi@gmail.com</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="demo" name="demo" value="1">
                            <label class="form-check-label" for="demo">
                                Mode DÉMO (enregistre dans les logs, pas d'envoi réel)
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        📧 Envoyer un email de test
                    </button>
                </form>
                
                <?php if (!empty($message)): ?>
                    <div class="alert <?php echo $success ? 'alert-success' : 'alert-danger'; ?> mt-4">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <hr class="my-4">
                
                <?php if ($useDemo): ?>
                    <div class="alert alert-warning mb-4">
                        <strong>📝 Mode DÉMO actif</strong> - Les emails ne sont pas réellement envoyés, mais enregistrés dans un fichier de log pour testing.
                    </div>
                    
                    <?php 
                    $demoMailer = new MailingServiceDemo();
                    $demoLogs = $demoMailer->getDemoLog();
                    ?>
                    
                    <h5>Emails en mode DÉMO (<?php echo count($demoLogs); ?>):</h5>
                    <div class="demo-log">
                        <?php if (!empty($demoLogs)): ?>
                            <?php foreach ($demoLogs as $log): ?>
                                <div class="mb-2">
                                    <strong><?php echo $log['course_title']; ?></strong><br>
                                    À: <?php echo $log['to']; ?><br>
                                    ID Cours: <?php echo $log['course_id']; ?><br>
                                    Date: <?php echo $log['timestamp']; ?><br>
                                    <small class="text-muted"><?php echo substr($log['course_description'], 0, 50) . '...'; ?></small>
                                </div>
                                <hr style="margin: 8px 0;">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <em class="text-muted">Aucun email en démo enregistré</em>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="bg-light p-3 rounded mt-4">
                    <h5>ℹ️ Configuration requise pour l'envoi réel:</h5>
                    <ul class="mb-0">
                        <li>Remplissez les variables SMTP dans <code>.env</code></li>
                        <li>Pour Gmail: utilisez une <a href="https://support.google.com/accounts/answer/185833" target="_blank">App Password</a></li>
                        <li>Variables nécessaires: <code>SMTP_HOST</code>, <code>SMTP_PORT</code>, <code>SMTP_USER</code>, <code>SMTP_PASS</code></li>
                        <li><strong>Mode DÉMO</strong> est idéal pour tester sans connexion SMTP</li>
                    </ul>
                </div>
                
                <div class="mt-4 text-center">
                    <a href="../BackOffice/index.php" class="btn btn-secondary">Retour au BackOffice</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

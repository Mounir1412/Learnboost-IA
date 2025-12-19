# MailingService - Documentation

## Vue d'ensemble

Le **MailingService** est un service centralisé pour envoyer des emails automatiques dans LearnBoost AI. Il est conçu pour être extensible et maintenable.

## Architecture

```
Service/
├── MailingService.php       # Service principal d'envoi d'email
├── EmailLogService.php      # Service de logging des emails
└── README.md               # Cette documentation
```

## Configuration

### 1. Remplir les variables d'environnement

Copiez `.env.example` vers `.env` et remplissez les variables SMTP:

```ini
# SMTP Configuration for Email Notifications
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-app-password
FROM_EMAIL=noreply@learnboost.com
```

### 2. Pour Gmail

1. Activez l'authentification 2FA sur votre compte Google
2. Créez une [App Password](https://support.google.com/accounts/answer/185833)
3. Utilisez cette App Password comme `SMTP_PASS`

### 3. Pour autre serveur SMTP

Adaptez les variables selon votre fournisseur (SendGrid, Mailgun, AWS SES, etc.)

## Utilisation

### Envoi d'email de notification de nouveau cours

Le système **envoie automatiquement** un email chaque fois qu'un cours est ajouté:

```php
// Dans CourseController::addCourse()
// Cela se fait automatiquement après l'insertion du cours
$courseId = $db->lastInsertId();

// MailingService est appelé automatiquement
require_once(dirname(__DIR__) . '/Service/MailingService.php');
$mailer = new MailingService();
$emailSent = $mailer->sendNewCourseEmail($courseData);
```

### Envoi d'email personnalisé

```php
require_once 'Service/MailingService.php';

$mailer = new MailingService();

// Envoyer un email de notification de cours
$courseData = [
    'id' => 123,
    'title' => 'Introduction à PHP',
    'description' => 'Apprenez les bases de PHP',
    'created_at' => date('Y-m-d H:i:s')
];

$success = $mailer->sendNewCourseEmail($courseData, 'user@example.com');

if (!$success) {
    echo 'Erreur: ' . $mailer->getLastError();
}
```

### Envoi d'email générique

```php
$mailer = new MailingService();

$htmlContent = '<h1>Bonjour</h1><p>Ceci est un test</p>';

$success = $mailer->sendEmail(
    'recipient@example.com',
    'Sujet du mail',
    $htmlContent
);
```

## API du MailingService

### Méthodes publiques

#### `sendNewCourseEmail($course, $recipientEmail = 'ammenezzi@gmail.com'): bool`

Envoie une notification pour un nouveau cours.

**Paramètres:**
- `$course` (array|object): Données du cours avec clés: `id`, `title`, `description`, `created_at`
- `$recipientEmail` (string): Email du destinataire

**Retour:** `true` si succès, `false` sinon

**Exemple:**
```php
$course = [
    'id' => 1,
    'title' => 'Mon Cours',
    'description' => 'Description...',
    'created_at' => '2025-12-10 10:30:00'
];
$mailer->sendNewCourseEmail($course);
```

#### `sendEmail($to, $subject, $htmlContent): bool`

Envoie un email générique.

**Paramètres:**
- `$to` (string): Email du destinataire
- `$subject` (string): Sujet du mail
- `$htmlContent` (string): Contenu en HTML

#### `getLastError(): string`

Retourne le dernier message d'erreur.

## Logging

### Utiliser EmailLogService

```php
require_once 'Service/EmailLogService.php';

$logger = new EmailLogService();

// Enregistrer un email envoyé
$logger->logEmailSent('user@example.com', 'Nouveau cours', 'course', 123);

// Enregistrer une erreur
$logger->logEmailError('user@example.com', 'Nouveau cours', 'SMTP connection failed', 'course', 123);

// Récupérer les 50 derniers logs
$logs = $logger->getRecentLogs(50);

// Vider les logs
$logger->clearLogs();
```

Les logs sont sauvegardés dans `logs/email.log`.

## Test

### Page de test

Accédez à `/View/FrontOffice/test-mailing.php` pour tester l'envoi d'email manuellement.

### Commandes de test via PHP CLI

```bash
cd learnboostai
php -r "
require 'Service/MailingService.php';
\$mailer = new MailingService();
\$course = ['id' => 1, 'title' => 'Test', 'description' => 'Test course', 'created_at' => date('Y-m-d H:i:s')];
\$result = \$mailer->sendNewCourseEmail(\$course, 'ammenezzi@gmail.com');
echo \$result ? 'Email sent!' : 'Error: ' . \$mailer->getLastError();
"
```

## Déboggage

### Vérifier la configuration

```php
// Afficher les variables de configuration chargées
echo getenv('SMTP_HOST');      // Devrait afficher smtp.gmail.com
echo getenv('SMTP_USER');      // Devrait afficher votre email
```

### Logs d'erreur PHP

Les erreurs sont enregistrées dans les logs PHP standards:
- Sur XAMPP: `C:\xampp\apache\logs\error.log`
- Ou voir les logs d'application: `logs/email.log`

### Vérifier que PHPMailer est disponible

```php
if (class_exists('PHPMailer\PHPMailer\PHPMailer')) {
    echo 'PHPMailer is available';
} else {
    echo 'PHPMailer not found, will use native mail()';
}
```

## Futures améliorations

- [ ] Envoyer les emails dans une queue (jobs async)
- [ ] Support de templates d'email stockés en base de données
- [ ] Emails dynamiques selon l'utilisateur connecté
- [ ] Notifications pour d'autres actions (inscription, note, etc.)
- [ ] Webhooks pour intégrations externes
- [ ] Statistiques d'envoi (ouvertures, clics, rebonds)

## Support

Pour tout problème, consultez:
1. Les logs d'erreur Apache: `C:\xampp\apache\logs\error.log`
2. Les logs de mailing: `logs/email.log`
3. La page de test: `/View/FrontOffice/test-mailing.php`

## Licence

Intégré dans LearnBoost AI - Tous droits réservés

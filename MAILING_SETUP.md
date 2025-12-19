# Guide de Configuration - Système de Mailing

## Quick Start (5 minutes)

### Étape 1: Configurer SMTP pour Gmail

1. **Activez l'authentification 2FA** sur votre compte Google:
   - Allez sur https://myaccount.google.com
   - Sécurité → Authentification 2FA

2. **Générez une App Password**:
   - Allez sur https://myaccount.google.com/apppasswords
   - Sélectionnez "Mail" et "Windows"
   - Google génère un mot de passe (ex: `abcd efgh ijkl mnop`)
   - Copiez ce mot de passe

3. **Remplissez votre `.env`**:
```ini
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=votre-email@gmail.com
SMTP_PASS=abcdefghijklmnop
FROM_EMAIL=noreply@learnboost.com
```

### Étape 2: Tester la configuration

1. Ouvrez votre navigateur et allez à:
   ```
   http://localhost/try/learnboostai/learnboostai/View/FrontOffice/test-mailing.php
   ```

2. Cliquez sur "📧 Envoyer un email de test"

3. Vérifiez que l'email arrive dans `ammenezzi@gmail.com` (ou l'adresse que vous avez changée)

### Étape 3: C'est prêt!

À partir de maintenant, chaque fois qu'un cours est ajouté via le BackOffice, une notification email est automatiquement envoyée.

---

## Vérification

### ✓ Email reçu avec succès
- L'email contient le titre et la description du cours
- L'email vient de `noreply@learnboost.com`
- Il y a un bouton "Consulter tous les cours"

### ✗ Email non reçu ?

1. **Vérifiez les logs**:
   ```
   C:\xampp\apache\logs\error.log
   ```
   Cherchez les messages d'erreur SMTP

2. **Vérifiez les credentials**:
   ```php
   // Ouvrez test-mailing.php et regardez les erreurs
   // Ou exécutez en CLI:
   php -r "echo getenv('SMTP_USER'); echo getenv('SMTP_PASS');"
   ```

3. **Vérifiez le pare-feu Gmail**:
   - Gmail peut bloquer les connexions "moins sécurisées"
   - Allez sur: https://myaccount.google.com/lesssecureapps
   - Activez l'accès si nécessaire

---

## Utilisation

### Envoi manuel d'email

```php
require_once 'Service/MailingService.php';

$mailer = new MailingService();
$courseData = [
    'id' => 123,
    'title' => 'My Course',
    'description' => 'Course description',
    'created_at' => date('Y-m-d H:i:s')
];

$success = $mailer->sendNewCourseEmail($courseData, 'user@example.com');

if (!$success) {
    echo 'Erreur: ' . $mailer->getLastError();
}
```

### Envoi automatique (déjà intégré)

Aucune action requise! Chaque fois qu'un cours est ajouté:
- Le CourseController appelle automatiquement `MailingService`
- L'email est envoyé
- Un log est enregistré

---

## Autres fournisseurs SMTP

### SendGrid
```ini
SMTP_HOST=smtp.sendgrid.net
SMTP_PORT=587
SMTP_USER=apikey
SMTP_PASS=SG.your-api-key
```

### Mailgun
```ini
SMTP_HOST=smtp.mailgun.org
SMTP_PORT=587
SMTP_USER=postmaster@your-domain.mailgun.org
SMTP_PASS=your-password
```

### Outlook/Hotmail
```ini
SMTP_HOST=smtp-mail.outlook.com
SMTP_PORT=587
SMTP_USER=your-email@outlook.com
SMTP_PASS=your-password
```

---

## Architecture

```
Service/
├── MailingService.php          # Envoi d'emails
├── EmailLogService.php         # Logging des emails
└── README.md                   # Documentation

Controller/
└── CourseController.php        # Intégration MailingService

logs/
└── email.log                   # Historique des emails
```

---

## Support & Debugging

- 📧 **Page de test**: `/View/FrontOffice/test-mailing.php`
- 📋 **Logs des emails**: `/logs/email.log`
- 🔍 **Logs Apache**: `C:\xampp\apache\logs\error.log`
- 📖 **Documentation complète**: `/Service/README.md`

---

## Prochaines étapes

- [ ] Implémenter les emails pour autres événements (inscription, note, etc.)
- [ ] Ajouter des templates personnalisables
- [ ] Utiliser des variables par utilisateur (emails dynamiques)
- [ ] Mettre en place une queue pour les emails (envoi asynchrone)
- [ ] Ajouter du suivi d'ouverture/clics

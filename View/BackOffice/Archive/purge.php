<?php
/**
 * Permanently delete archived items (hard delete from archive table)
 */
require_once dirname(__DIR__,3) . '/config.php';

$table = $_GET['table'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$allowed = ['lessons_archive','modules_archive','courses_archive'];

if (!in_array($table, $allowed) || $id <= 0) {
    die('Paramètres invalides');
}

$db = config::getConnexion();
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Permanently delete from archive
        $delete = $db->prepare("DELETE FROM `{$table}` WHERE id = :id");
        $delete->bindValue(':id', $id, PDO::PARAM_INT);
        $delete->execute();
        $success = true;
    } catch (Exception $e) {
        $error = $e->getMessage();
        error_log('Purge error: ' . $error);
    }
}

require_once dirname(__DIR__) . '/assets/header.php';
?>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Supprimer définitivement</h4>
        
<?php if ($success): ?>
        <div class="alert alert-success">✓ Élément supprimé définitivement!</div>
        <p><a class="btn btn-primary" href="viewArchived.php?table=<?php echo urlencode($table); ?>">Retour à l'archive</a></p>
<?php elseif (!empty($error)): ?>
        <div class="alert alert-danger">✗ Erreur: <?php echo htmlspecialchars($error); ?></div>
        <p><a class="btn btn-secondary" href="viewArchived.php?table=<?php echo urlencode($table); ?>">Retour à l'archive</a></p>
<?php else: ?>
        <div class="alert alert-warning">⚠ Attention: Cette action est irréversible!</div>
        <p>Êtes-vous absolument sûr de vouloir supprimer définitivement cet élément?</p>
        <form method="POST" style="margin-top:1rem;">
            <button type="submit" class="btn btn-danger">✓ Oui, supprimer définitivement</button>
            <a class="btn btn-secondary" href="viewArchived.php?table=<?php echo urlencode($table); ?>">Annuler</a>
        </form>
<?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once dirname(__DIR__) . '/assets/footer.php'; ?>

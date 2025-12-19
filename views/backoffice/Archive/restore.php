<?php
/**
 * Restore archived items (courses, modules, lessons) back to their original tables
 */
require_once dirname(__DIR__,3) . '/config.php';

$table = $_GET['table'] ?? '';
$type = $_GET['type'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$allowed = ['lessons_archive','modules_archive','courses_archive'];
$types = ['lessons' => 'lessons_archive', 'modules' => 'modules_archive', 'courses' => 'courses_archive'];

if (!in_array($table, $allowed) || !isset($types[$type]) || $types[$type] !== $table || $id <= 0) {
    die('Paramètres invalides');
}

$db = config::getConnexion();
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db->beginTransaction();
        
        // Get the archived row
        $getRow = $db->prepare("SELECT * FROM `{$table}` WHERE id = :id");
        $getRow->bindValue(':id', $id, PDO::PARAM_INT);
        $getRow->execute();
        $archived = $getRow->fetch(PDO::FETCH_ASSOC);
        
        if (!$archived) {
            throw new Exception('Enregistrement archivé introuvable');
        }
        
        // Prepare columns for insert (exclude archived_at)
        $cols = array_keys($archived);
        $cols = array_filter($cols, function($c) { return $c !== 'archived_at'; });
        $colList = '`' . implode('`, `', $cols) . '`';
        $valList = ':' . implode(', :', $cols);
        
        $originalTable = $type; // e.g., 'lessons', 'modules', 'courses'
        
        // Insert back to original table
        $insert = $db->prepare("INSERT INTO `{$originalTable}` ({$colList}) VALUES ({$valList})");
        foreach ($cols as $col) {
            $insert->bindValue(':' . $col, $archived[$col]);
        }
        $insert->execute();
        
        // Delete from archive
        $delete = $db->prepare("DELETE FROM `{$table}` WHERE id = :id");
        $delete->bindValue(':id', $id, PDO::PARAM_INT);
        $delete->execute();
        
        $db->commit();
        $success = true;
    } catch (Exception $e) {
        if ($db->inTransaction()) $db->rollBack();
        $error = $e->getMessage();
        error_log('Restore error: ' . $error);
    }
}

require_once dirname(__DIR__) . '/assets/header.php';
?>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Restaurer un élément archivé</h4>
        
<?php if ($success): ?>
        <div class="alert alert-success">✓ Élément restauré avec succès!</div>
        <p><a class="btn btn-primary" href="viewArchived.php?table=<?php echo urlencode($table); ?>">Retour à l'archive</a></p>
<?php elseif (!empty($error)): ?>
        <div class="alert alert-danger">✗ Erreur: <?php echo htmlspecialchars($error); ?></div>
        <p><a class="btn btn-secondary" href="viewArchived.php?table=<?php echo urlencode($table); ?>">Retour à l'archive</a></p>
<?php else: ?>
        <p>Êtes-vous sûr de vouloir restaurer cet élément?</p>
        <form method="POST" style="margin-top:1rem;">
            <button type="submit" class="btn btn-success">✓ Restaurer</button>
            <a class="btn btn-secondary" href="viewArchived.php?table=<?php echo urlencode($table); ?>">Annuler</a>
        </form>
<?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require_once dirname(__DIR__) . '/assets/footer.php'; ?>

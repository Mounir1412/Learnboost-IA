<?php
// Simple viewer for archive tables. Use ?table=lessons_archive|modules_archive|courses_archive
require_once dirname(__DIR__,3) . '/config.php';
$allowed = ['lessons_archive','modules_archive','courses_archive'];
$table = $_GET['table'] ?? '';
if (!in_array($table, $allowed)) {
    die('Table non autorisée');
}
$db = config::getConnexion();

// Pagination simple
$page = max(1, (int)($_GET['page'] ?? 1));
$per = 25;
$offset = ($page-1)*$per;

try {
    $stmt = $db->prepare("SELECT * FROM `{$table}` ORDER BY archived_at DESC LIMIT :lim OFFSET :off");
    $stmt->bindValue(':lim', $per, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countRow = $db->query("SELECT COUNT(*) AS cnt FROM `{$table}`")->fetch();
    $total = isset($countRow['cnt']) ? (int)$countRow['cnt'] : 0;
} catch (Exception $e) {
    die('Erreur SQL: ' . $e->getMessage());
}

require_once dirname(__DIR__) . '/assets/header.php';
?>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Affichage: <?php echo htmlspecialchars($table); ?></h4>
        <p>Total: <?php echo $total; ?></p>
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
<?php if (!empty($rows)): ?>
<?php foreach (array_keys($rows[0]) as $col): ?>
                <th><?php echo htmlspecialchars($col); ?></th>
<?php endforeach; ?>
                <th>Actions</th>
<?php else: ?>
                <th>Aucune donnée</th>
<?php endif; ?>
              </tr>
            </thead>
            <tbody>
<?php foreach ($rows as $r): ?>
              <tr>
<?php foreach ($r as $v): ?>
                <td><?php echo htmlspecialchars((string)$v); ?></td>
<?php endforeach; ?>
                <td>
<?php 
$id = isset($r['id']) ? (int)$r['id'] : 0;
if ($id > 0):
  $archiveType = str_replace('_archive', '', $table);
?>
                  <a class="btn btn-sm btn-success" href="restore.php?table=<?php echo urlencode($table); ?>&type=<?php echo urlencode($archiveType); ?>&id=<?php echo $id; ?>">Restaurer</a>
                  <a class="btn btn-sm btn-danger" href="purge.php?table=<?php echo urlencode($table); ?>&id=<?php echo $id; ?>" onclick="return confirm('Supprimer définitivement?');">Purger</a>
<?php endif; ?>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div style="margin-top:1rem">
<?php if ($page > 1): ?>
  <a class="btn btn-sm btn-outline-secondary" href="viewArchived.php?table=<?php echo urlencode($table); ?>&page=<?php echo $page-1; ?>">Précédent</a>
<?php endif; ?>
<?php if ($offset + $per < $total): ?>
  <a class="btn btn-sm btn-primary" href="viewArchived.php?table=<?php echo urlencode($table); ?>&page=<?php echo $page+1; ?>">Suivant</a>
<?php endif; ?>
          <a class="btn btn-sm btn-light" href="archiveList.php">Retour</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once dirname(__DIR__) . '/assets/footer.php'; ?>

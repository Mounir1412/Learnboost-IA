<?php
$assetsUrl = $assetsUrl ?? null; // keep header.php logic happy
$pageTitle = 'Archives - BackOffice';
require_once dirname(__DIR__) . '/assets/header.php';

require_once dirname(__DIR__,3) . '/config.php';
$db = config::getConnexion();

// Count archived rows (if tables exist)
$counts = [
    'courses_archive' => 0,
    'modules_archive' => 0,
    'lessons_archive' => 0,
];
try {
    foreach (array_keys($counts) as $t) {
        $res = $db->query("SELECT COUNT(*) AS cnt FROM `{$t}`")->fetch();
        $counts[$t] = isset($res['cnt']) ? (int)$res['cnt'] : 0;
    }
} catch (Exception $e) {
    // Ignore errors - tables may not exist yet
}
?>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Archives</h4>
            <p class="card-description">Gérer les éléments archivés (cours, modules, leçons)</p>
            <div class="row">
              <div class="col-md-4">
                <div class="p-3 border rounded">
                  <h5>Cours archivés</h5>
                  <p style="font-size:1.6rem; font-weight:700"><?php echo $counts['courses_archive']; ?></p>
                  <a class="btn btn-sm btn-outline-primary" href="viewArchived.php?table=courses_archive">Voir</a>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 border rounded">
                  <h5>Modules archivés</h5>
                  <p style="font-size:1.6rem; font-weight:700"><?php echo $counts['modules_archive']; ?></p>
                  <a class="btn btn-sm btn-outline-primary" href="viewArchived.php?table=modules_archive">Voir</a>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 border rounded">
                  <h5>Leçons archivées</h5>
                  <p style="font-size:1.6rem; font-weight:700"><?php echo $counts['lessons_archive']; ?></p>
                  <a class="btn btn-sm btn-outline-primary" href="viewArchived.php?table=lessons_archive">Voir</a>
                </div>
              </div>
            </div>
            <div style="margin-top:1rem">
              <a href="<?php echo htmlspecialchars($backofficeBase); ?>/index.php" class="btn btn-light">Retour</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once dirname(__DIR__) . '/assets/footer.php'; ?>

<?php
require_once __DIR__ . '/../../../Controller/LessonController.php';
require_once __DIR__ . '/../../../Controller/ModuleController.php';

$lessonC = new LessonController();
$moduleC = new ModuleController();

$module_id = $_GET['module_id'] ?? null;
$lessons = [];
$module = null;

if ($module_id) {
    $module = $moduleC->showModule($module_id);
    $lessons = $lessonC->listLessonsByModule($module_id);
} else {
    $lessons = $lessonC->listLessons();
}
?>
<?php $assetsPath = __DIR__ . '/../assets'; require_once __DIR__ . '/../assets/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-info text-white mr-2">
            <i class="mdi mdi-file-document"></i>
        </span>
        <?php if ($module): ?>
            Leçons du module : <?php echo htmlspecialchars($module['title']); ?>
        <?php else: ?>
            Liste des leçons
        <?php endif; ?>
    </h3>
    <p class="page-sub-title">Gérez le contenu pédagogique</p>
</div>

        <!-- Messages -->
        <?php if (isset($_GET['success'])): ?>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-check-circle"></i> Leçon ajoutée/modifiée avec succès !
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="mdi mdi-alert-circle"></i> Une erreur s'est produite.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Add Lesson Button -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="addLesson.php<?php echo $module_id ? '?module_id=' . $module_id : ''; ?>" class="btn btn-info">
                    <i class="mdi mdi-plus"></i> Ajouter une leçon
                </a>
                <?php if ($module): ?>
                    <a href="../../Course/showCourse.php?id=<?php echo $module['course_id']; ?>" class="btn btn-outline-info">
                        <i class="mdi mdi-arrow-left"></i> Retour au cours
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Lessons Table Card -->
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Leçons disponibles</h4>

        <?php if (empty($lessons)): ?>
                            <p class="text-muted">Aucune leçon disponible.</p>
                        <?php else: ?>
                            <div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Contenu</th>
                <th>Durée (s)</th>
                <th colspan="2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lessons as $lesson): ?>
                <tr>
                    <td><?php echo htmlspecialchars($lesson['id']); ?></td>
                    <td><?php echo htmlspecialchars($lesson['title']); ?></td>
                    <td><?php echo htmlspecialchars(substr($lesson['content'] ?? '', 0, 50)); ?>...</td>
                    <td><?php echo htmlspecialchars($lesson['duration'] ?? 0); ?></td>
                    <td>
                        <a href="updateLesson.php?id=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-warning">
                            <i class="mdi mdi-pencil"></i> Modifier
                        </a>
                    </td>
                    <td>
                        <a href="deleteLesson.php?id=<?php echo $lesson['id']; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette leçon ?')">
                           <i class="mdi mdi-delete"></i> Supprimer
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

<?php require_once __DIR__ . '/../assets/footer.php'; ?>


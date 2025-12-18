<?php
require_once __DIR__ . '/../../../Controller/CourseController.php';
require_once __DIR__ . '/../../../Controller/ModuleController.php';
require_once __DIR__ . '/../../../Controller/LessonController.php';

$courseC = new CourseController();
$moduleC = new ModuleController();
$lessonC = new LessonController();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: courseList.php?error=no_id');
    exit;
}

$course = $courseC->showCourse($id);
if (!$course) {
    header('Location: courseList.php?error=not_found');
    exit;
}

$modules = $moduleC->listModulesByCourse($id);

?>
<?php $assetsPath = __DIR__ . '/../assets'; require_once __DIR__ . '/../assets/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span>
        Détails du cours
    </h3>
    <p class="page-sub-title">Informations complètes du cours et ses modules</p>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"><?php echo htmlspecialchars($course['title']); ?></h4>
                
                <div class="course-details mb-4">
                    <p><strong>Description:</strong></p>
                    <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                    
                    <p class="mt-3"><strong>Statut:</strong> 
                        <span class="badge <?php echo ($course['status'] == 'active') ? 'badge-success' : 'badge-warning'; ?>">
                            <?php echo htmlspecialchars($course['status']); ?>
                        </span>
                    </p>
                </div>

                <div class="action-buttons mb-4">
                    <a href="updateCourse.php?id=<?php echo $course['id']; ?>" class="btn btn-primary btn-sm mr-2">
                        <i class="mdi mdi-pencil"></i> Modifier le cours
                    </a>
                    <a href="deleteCourse.php?id=<?php echo $course['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer le cours ?')">
                        <i class="mdi mdi-delete"></i> Supprimer le cours
                    </a>
                    <a href="courseList.php" class="btn btn-secondary btn-sm">
                        <i class="mdi mdi-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modules Section -->
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Modules du cours</h4>
                    <a href="../Module/addModule.php?course_id=<?php echo $course['id']; ?>" class="btn btn-success btn-sm">
                        <i class="mdi mdi-plus"></i> Ajouter un module
                    </a>
                </div>

                <?php if (empty($modules)): ?>
                    <div class="alert alert-info">
                        <i class="mdi mdi-information"></i> Aucun module pour ce cours.
                    </div>
                <?php else: ?>
                    <div class="modules-list">
                        <?php foreach ($modules as $module): ?>
                            <div class="module-card card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h5 class="card-title mb-2">
                                                <i class="mdi mdi-layers text-primary"></i> 
                                                <?php echo htmlspecialchars($module['title']); ?>
                                            </h5>
                                            <p class="card-text text-muted">
                                                <?php echo nl2br(htmlspecialchars($module['description'])); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Lessons in Module -->
                                    <?php $lessons = $lessonC->listLessonsByModule($module['id']); ?>
                                    <?php if (!empty($lessons)): ?>
                                        <div class="lessons-list mt-3">
                                            <h6 class="text-secondary mb-2">Leçons:</h6>
                                            <ul class="list-group">
                                                <?php foreach ($lessons as $lesson): ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <i class="mdi mdi-file-document text-info"></i>
                                                            <?php echo htmlspecialchars($lesson['title']); ?>
                                                        </div>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <a href="../Lesson/updateLesson.php?id=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-outline-primary" title="Modifier">
                                                                <i class="mdi mdi-pencil"></i>
                                                            </a>
                                                            <a href="../Lesson/deleteLesson.php?id=<?php echo $lesson['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer la leçon ?')" title="Supprimer">
                                                                <i class="mdi mdi-delete"></i>
                                                            </a>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info alert-sm mt-3 mb-0">
                                            <i class="mdi mdi-information"></i> Aucune leçon pour ce module.
                                        </div>
                                    <?php endif; ?>

                                    <!-- Module Actions -->
                                    <div class="mt-3">
                                        <a href="../Module/updateModule.php?id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-primary mr-2">
                                            <i class="mdi mdi-pencil"></i> Modifier
                                        </a>
                                        <a href="../Lesson/addLesson.php?module_id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-success mr-2">
                                            <i class="mdi mdi-plus"></i> Ajouter une leçon
                                        </a>
                                        <a href="../Module/deleteModule.php?id=<?php echo $module['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer le module ?')">
                                            <i class="mdi mdi-delete"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../assets/footer.php'; ?>

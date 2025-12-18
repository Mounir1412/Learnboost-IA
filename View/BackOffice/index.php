<?php 
// Include the BackOffice header (template)
if (file_exists(__DIR__ . '/assets/header.php')) {
    require_once __DIR__ . '/assets/header.php';
} else {
    // Fallback: include a minimal header to avoid fatal errors
    echo "<!doctype html><html><head><meta charset=\"utf-8\"><title>BackOffice</title></head><body><main class=\"container\">";
}

// Include controllers to get statistics
require_once dirname(__DIR__, 2) . '/Controller/CourseController.php';
require_once dirname(__DIR__, 2) . '/Controller/ModuleController.php';
require_once dirname(__DIR__, 2) . '/Controller/LessonController.php';

$courseController = new CourseController();
$courseCount = $courseController->countCourses();

$moduleController = new ModuleController();
$moduleCount = $moduleController->countModules();

$lessonController = new LessonController();
$lessonCount = $lessonController->countLessons();
?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-home"></i>
        </span>
        Tableau de Bord
    </h3>
    <p class="page-sub-title">Bienvenue sur le tableau de bord Learnboost AI</p>
</div>

        <!-- Dashboard Statistics Cards -->
        <div class="row">
            <div class="col-md-3 stretch-card grid-margin">
                <a href="Course/courseList.php" style="text-decoration: none; color: inherit;">
                    <div class="card bg-gradient-primary card-img-holder text-white h-100">
                        <div class="card-body">
                            <img src="<?php echo $assetsUrl; ?>/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">
                                Cours <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5"><?php echo isset($courseCount) ? $courseCount : 0; ?></h2>
                            <h6 class="card-text">Total des cours créés</h6>
                            <p class="mt-3"><small><i class="mdi mdi-arrow-right"></i> Cliquez pour gérer</small></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <a href="Module/moduleList.php" style="text-decoration: none; color: inherit;">
                    <div class="card bg-gradient-danger card-img-holder text-white h-100">
                        <div class="card-body">
                            <img src="<?php echo $assetsUrl; ?>/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">
                                Modules <i class="mdi mdi-folder-outline mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5"><?php echo isset($moduleCount) ? $moduleCount : 0; ?></h2>
                            <h6 class="card-text">Modules disponibles</h6>
                            <p class="mt-3"><small><i class="mdi mdi-arrow-right"></i> Cliquez pour gérer</small></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <a href="Lesson/lessonList.php" style="text-decoration: none; color: inherit;">
                    <div class="card bg-gradient-success card-img-holder text-white h-100">
                        <div class="card-body">
                            <img src="<?php echo $assetsUrl; ?>/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                            <h4 class="font-weight-normal mb-3">
                                Leçons <i class="mdi mdi-file-document-outline mdi-24px float-right"></i>
                            </h4>
                            <h2 class="mb-5"><?php echo isset($lessonCount) ? $lessonCount : 0; ?></h2>
                            <h6 class="card-text">Leçons publiées</h6>
                            <p class="mt-3"><small><i class="mdi mdi-arrow-right"></i> Cliquez pour gérer</small></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 stretch-card grid-margin">
                <div class="card bg-gradient-info card-img-holder text-white h-100">
                    <div class="card-body">
                        <img src="<?php echo $assetsUrl; ?>/assets/images/circle.svg" class="card-img-absolute" alt="circle-image" />
                        <h4 class="font-weight-normal mb-3">
                            Utilisateurs <i class="mdi mdi-account-multiple-outline mdi-24px float-right"></i>
                        </h4>
                        <h2 class="mb-5">0</h2>
                        <h6 class="card-text">Utilisateurs actifs</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Panels -->
        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="mdi mdi-plus-circle-outline text-primary"></i> Gestion des Cours
                        </h4>
                        <p class="card-description mb-3">Créez, modifiez et gérez tous vos cours</p>
                        <div class="action-buttons">
                            <a href="Course/courseList.php" class="btn btn-outline-primary btn-sm mb-2 mr-2">
                                <i class="mdi mdi-eye"></i> Voir les cours
                            </a>
                            <a href="Course/addCourse.php" class="btn btn-primary btn-sm mb-2">
                                <i class="mdi mdi-plus"></i> Ajouter un cours
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="mdi mdi-folder-plus-outline text-success"></i> Gestion des Modules
                        </h4>
                        <p class="card-description mb-3">Organisez vos modules et liez-les aux cours</p>
                        <div class="action-buttons">
                            <a href="Module/moduleList.php" class="btn btn-outline-success btn-sm mb-2 mr-2">
                                <i class="mdi mdi-eye"></i> Voir les modules
                            </a>
                            <a href="Module/addModule.php" class="btn btn-success btn-sm mb-2">
                                <i class="mdi mdi-plus"></i> Ajouter un module
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="mdi mdi-file-plus-outline text-info"></i> Gestion des Leçons
                        </h4>
                        <p class="card-description mb-3">Créez et gérez le contenu de vos leçons</p>
                        <div class="action-buttons">
                            <a href="Lesson/lessonList.php" class="btn btn-outline-info btn-sm mb-2 mr-2">
                                <i class="mdi mdi-eye"></i> Voir les leçons
                            </a>
                            <a href="Lesson/addLesson.php" class="btn btn-info btn-sm mb-2">
                                <i class="mdi mdi-plus"></i> Ajouter une leçon
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">
                            <i class="mdi mdi-shield-check-outline text-warning"></i> Vérification Système
                        </h4>
                        <p class="card-description mb-3">Vérifiez la configuration et l'intégrité du système</p>
                        <div class="action-buttons">
                            <a href="Verification.php" class="btn btn-outline-warning btn-sm mb-2">
                                <i class="mdi mdi-bug-check"></i> Exécuter la vérification
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="card-title">Besoin d'aide?</h6>
                        <p class="card-description text-muted">Consultez la documentation ou contactez le support technique</p>
                        <a href="contact.php" class="btn btn-outline-secondary btn-sm">
                            <i class="mdi mdi-email"></i> Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php require_once __DIR__ . '/assets/footer.php'; ?>

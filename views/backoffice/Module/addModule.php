<?php
require_once __DIR__ . '/../../../Controller/ModuleController.php';
require_once __DIR__ . '/../../../Controller/CourseController.php';

$courseC = new CourseController();
$moduleC = new ModuleController();

$courses = $courseC->getCourses();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $module = new Module(
        null,
        $_POST['course_id'],
        $_POST['title'],
        $_POST['description']
    );

    $moduleC->addModule($module);
    header("Location: moduleList.php?success=1");
    exit;
}
?>
<?php $assetsPath = __DIR__ . '/../assets'; require_once __DIR__ . '/../assets/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white mr-2">
            <i class="mdi mdi-plus-circle"></i>
        </span>
        Ajouter un Module
    </h3>
    <p class="page-sub-title">Créez un nouveau module de cours</p>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body">
        
<form method="POST" id="moduleForm">
    <div class="form-group">
        <label for="course_id">Cours *</label>
        <select name="course_id" id="course_id" class="form-control" required>
            <option value="">-- Sélectionner un cours --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>"><?= htmlspecialchars($course['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="title">Titre du module *</label>
        <input type="text" name="title" id="title" class="form-control" placeholder="Ex: Fondamentaux" required minlength="3">
        <small class="form-text text-muted">Entrez un titre descriptif (min 3 caractères)</small>
    </div>

    <div class="form-group">
        <label for="description">Description *</label>
        <textarea name="description" id="description" class="form-control" rows="4" 
                  placeholder="Décrivez le contenu du module..." required minlength="5"></textarea>
        <small class="form-text text-muted">Donnez un aperçu du module</small>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-success btn-block">
            <i class="mdi mdi-plus"></i> Créer le module
        </button>
    </div>
</form>

<hr>
<div class="text-center">
    <a href="moduleList.php" class="btn btn-outline-secondary">
        <i class="mdi mdi-arrow-left"></i> Retour à la liste
    </a>
</div>

            </div>
        </div>
    </div>
</div>

    <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/module-form.js"></script>
    <?php require_once __DIR__ . '/../assets/footer.php'; ?>
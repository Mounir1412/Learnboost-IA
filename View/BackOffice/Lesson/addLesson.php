<?php
require_once __DIR__ . '/../../../Controller/LessonController.php';
require_once __DIR__ . '/../../../Controller/ModuleController.php';
require_once __DIR__ . '/../../../Model/Lesson.php';

$lessonC = new LessonController();
$moduleC = new ModuleController();

$module_id = $_GET['module_id'] ?? null;
$module = null;

if ($module_id) {
    $module = $moduleC->showModule($module_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate module_id
    $postedModuleId = $_POST['module_id'] ?? null;
    if (empty($postedModuleId) || !is_numeric($postedModuleId)) {
        $error = "Veuillez sélectionner un module valide.";
    } else {
        $lesson = new Lesson(
            null,
            $_POST['title'] ?? null,
            $_POST['content'] ?? null,
            $_POST['videoUrl'] ?? null,
            isset($_POST['duration']) ? (int)$_POST['duration'] : 0,
            (int)$postedModuleId
        );

        $result = $lessonC->addLesson($lesson);
            if ($result) {
                header('Location: lessonList.php?success=added&module_id=' . (int)$postedModuleId);
                exit;
            } else {
                $ctrlError = $lessonC->getLastError() ?? '';
                $error = "Erreur lors de l'ajout de la leçon" . (!empty($ctrlError) ? ": " . htmlspecialchars($ctrlError) : '');
            }
    }
}
?>
<?php $assetsPath = __DIR__ . '/../assets'; require_once __DIR__ . '/../assets/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-info text-white mr-2">
            <i class="mdi mdi-plus-circle"></i>
        </span>
        Ajouter une Leçon
    </h3>
    <p class="page-sub-title">Créez du contenu pédagogique</p>
</div>

<!-- Form Card -->
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="mdi mdi-alert-circle"></i> <?php echo htmlspecialchars($error); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<form method="POST" action="" id="lessonForm">
    <div class="form-group">
        <label for="module_id">Module *</label>
        <select id="module_id" name="module_id" class="form-control" required>
            <option value="">-- Sélectionner un module --</option>
            <?php 
            $modules = $moduleC->listModules();
            foreach ($modules as $mod): 
            ?>
                <option value="<?php echo $mod['id']; ?>" <?php echo ($module && $mod['id'] == $module['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($mod['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="title">Titre de la leçon *</label>
        <input id="title" name="title" type="text" class="form-control"
               placeholder="Ex: Introduction aux variables"
               value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required minlength="3">
    </div>

    <div class="form-group">
        <label for="content">Contenu *</label>
        <textarea id="content" name="content" class="form-control" rows="8" 
                  placeholder="Écrivez le contenu de la leçon..."
                  required minlength="5"><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="videoUrl">URL vidéo (optionnel)</label>
            <input id="videoUrl" name="videoUrl" type="url" class="form-control"
                   placeholder="https://exemple.com/video.mp4"
                   value="<?php echo htmlspecialchars($_POST['videoUrl'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-6">
            <label for="duration">Durée (secondes, optionnel)</label>
            <input id="duration" name="duration" type="number" class="form-control"
                   placeholder="Durée en secondes"
                   value="<?php echo isset($_POST['duration']) && $_POST['duration'] !== '' ? htmlspecialchars($_POST['duration']) : ''; ?>" min="0" step="1">
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-info btn-block">
            <i class="mdi mdi-plus"></i> Créer la leçon
        </button>
    </div>
</form>

<hr>
<div class="text-center">
    <a href="lessonList.php<?php echo $module_id ? '?module_id=' . $module_id : ''; ?>" class="btn btn-outline-secondary">
        <i class="mdi mdi-arrow-left"></i> Retour à la liste
    </a>
</div>

            </div>
        </div>
    </div>
</div>

    <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/lesson-form.js"></script>
<?php require_once __DIR__ . '/../assets/footer.php'; ?>


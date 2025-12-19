(The file `c:\xampp\htdocs\try\learnboostai\learnboostai\View\BackOffice\Lesson\updateLesson.php` exists, but is empty)
<?php
require_once __DIR__ . '/../../../Controller/LessonController.php';
require_once __DIR__ . '/../../../Controller/ModuleController.php';
require_once __DIR__ . '/../../../Model/Lesson.php';

$lessonC = new LessonController();
$moduleC = new ModuleController();

$id = $_GET['id'] ?? null;
$error = "";

if (!$id) {
    header('Location: lessonList.php?error=no_id');
    exit;
}

$lesson = $lessonC->showLesson($id);
if (!$lesson) {
    header('Location: lessonList.php?error=not_found');
    exit;
}

$modules = $moduleC->listModules();

// Normalize video field for templates: accept snake_case or camelCase column names
$videoValue = $lesson['videoUrl'] ?? $lesson['video_url'] ?? $lesson['video'] ?? '';
// Only show duration if it's greater than 0, otherwise leave empty
$durationValue = isset($lesson['duration']) && (int)$lesson['duration'] > 0 ? (int)$lesson['duration'] : '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate module_id
    $postedModuleId = $_POST['module_id'] ?? null;
    if (empty($postedModuleId) || !is_numeric($postedModuleId)) {
        $error = "Veuillez sélectionner un module valide.";
    } else {
        // Parse duration: if empty or 0, use 0; otherwise convert to int
        $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : 0;
        
        $updated = new Lesson(
            $id,
            $_POST['title'] ?? null,
            $_POST['content'] ?? null,
            $_POST['videoUrl'] ?? null,
            $duration,
            (int)$postedModuleId
        );

        $result = $lessonC->updateLesson($id, $updated);
        if ($result) {
            header('Location: lessonList.php?success=updated&module_id=' . (int)$postedModuleId);
            exit;
        } else {
            $ctrlError = method_exists($lessonC, 'getLastError') ? $lessonC->getLastError() : '';
            $error = "Erreur lors de la mise à jour de la leçon" . (!empty($ctrlError) ? ": " . htmlspecialchars($ctrlError) : '');
        }
    }
}
?>
<?php $assetsPath = __DIR__ . '/../assets'; require_once __DIR__ . '/../assets/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-info text-white mr-2">
            <i class="mdi mdi-pencil"></i>
        </span>
        Modifier la Leçon
    </h3>
    <p class="page-sub-title">Mettez à jour le contenu pédagogique</p>
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
            <?php foreach ($modules as $mod): ?>
                <option value="<?php echo $mod['id']; ?>" <?php echo ($mod['id'] == $lesson['module_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($mod['title']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="title">Titre de la leçon *</label>
        <input id="title" name="title" type="text" class="form-control"
               value="<?php echo htmlspecialchars($lesson['title']); ?>" required minlength="3">
    </div>

    <div class="form-group">
        <label for="content">Contenu *</label>
        <textarea id="content" name="content" class="form-control" rows="8" 
                  required minlength="5"><?php echo htmlspecialchars($lesson['content'] ?? ''); ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="videoUrl">URL vidéo (optionnel)</label>
            <input id="videoUrl" name="videoUrl" type="url" class="form-control"
                   value="<?php echo htmlspecialchars($videoValue); ?>">
        </div>

        <div class="form-group col-md-6">
            <label for="duration">Durée (secondes, optionnel)</label>
            <input id="duration" name="duration" type="number" class="form-control"
                   value="<?php echo $durationValue; ?>" min="0" step="1">
        </div>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-info btn-block">
            <i class="mdi mdi-content-save"></i> Enregistrer les modifications
        </button>
    </div>
</form>

<hr>
<div class="text-center">
    <a href="lessonList.php" class="btn btn-outline-secondary">
        <i class="mdi mdi-arrow-left"></i> Retour à la liste
    </a>
</div>

            </div>
        </div>
    </div>
</div>

    <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/lesson-form.js"></script>
    <?php require_once __DIR__ . '/../assets/footer.php'; ?>
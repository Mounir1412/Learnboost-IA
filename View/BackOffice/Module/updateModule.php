<?php
require_once __DIR__ . '/../../../Controller/ModuleController.php';
require_once __DIR__ . '/../../../Controller/CourseController.php';

$moduleC = new ModuleController();
$courseC = new CourseController();

$id = $_GET['id'];
$module = $moduleC->getModuleById($id);
$courses = $courseC->getCourses();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updated = new Module(
        $id,
        $_POST['course_id'],
        $_POST['title'],
        $_POST['description']
    );

    $moduleC->updateModule($id, $updated);
    header("Location: moduleList.php?updated=1");
    exit;
}
?>

<?php $assetsPath = __DIR__ . '/../assets'; require_once __DIR__ . '/../assets/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white mr-2">
            <i class="mdi mdi-pencil"></i>
        </span>
        Modifier le Module
    </h3>
    <p class="page-sub-title">Mettez à jour les informations du module</p>
</div>

<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body">

                <form method="POST" id="moduleForm">
                    <div class="form-group">
                        <label for="course_id">Cours *</label>
                        <select name="course_id" id="course_id" class="form-control" required>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?= $course['id'] ?>" <?= $course['id'] == $module['course_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($course['title']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Titre du module *</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($module['title']) ?>" required minlength="3">
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required minlength="5"><?= htmlspecialchars($module['description']) ?></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

    <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/module-form.js"></script>
<?php require_once __DIR__ . '/../assets/footer.php'; ?>

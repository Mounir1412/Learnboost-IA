<?php
require_once __DIR__ . '/../../../controllers/CourseController.php';
require_once __DIR__ . '/../../../models/Course.php';

$error = "";
$success = "";
$courseC = new CourseController();

if (isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["status"])) {

    // Validation correcte
    if (
        !empty($_POST["title"]) && strlen($_POST['title']) >= 3 &&
        !empty($_POST["description"]) && strlen($_POST['description']) >= 10 &&
        !empty($_POST["status"])
    ) {

        $course = new Course(
            null,
            $_POST['title'],
            $_POST['description'],
            $_POST['status']
        );

        $result = $courseC->addCourse($course);

        if ($result) {
            header('Location: courseList.php?success=added');
            exit;
        } else {
            $error = "Erreur lors de l'ajout du cours";
        }

    } else {
        $error = "Tous les champs sont obligatoires. 
        (Titre min 3 caractères, description min 10 caractères)";
    }
}
?>
    <?php $assetsPath = __DIR__ . '/../assets'; require_once __DIR__ . '/../assets/header.php'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-plus-circle"></i>
            </span>
            Ajouter un Cours
        </h3>
        <p class="page-sub-title">Créez un nouveau cours de formation</p>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="mdi mdi-alert-circle"></i> <?php echo $error; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="mdi mdi-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="courseForm">
        
            <div class="form-group">
                <label for="title">Titre du cours *</label>
                <input type="text" id="title" name="title" class="form-control"
                       placeholder="Ex: Principes de base en Python"
                       value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required minlength="3">
                <small class="form-text text-muted">Minimum 3 caractères</small>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" class="form-control" rows="4" 
                          placeholder="Décrivez le contenu du cours..."
                          required minlength="10"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                <small class="form-text text-muted">Minimum 10 caractères</small>
            </div>

            <div class="form-group">
                <label for="status">Statut *</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="">-- Sélectionner un statut --</option>
                    <option value="Non Terminé" <?php echo (($_POST['status'] ?? '') === 'Non Terminé') ? 'selected' : ''; ?>>Non Terminé</option>
                    <option value="Terminé" <?php echo (($_POST['status'] ?? '') === 'Terminé') ? 'selected' : ''; ?>>Terminé</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="mdi mdi-plus"></i> Ajouter le cours
                </button>
            </div>
        </form>

        <hr>
        <div class="text-center">
            <a href="courseList.php" class="btn btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> Voir la liste des cours
            </a>
        </div>

                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/course-form.js"></script>
    <?php require_once __DIR__ . '/../assets/footer.php'; ?>

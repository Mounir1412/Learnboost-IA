<?php
$assetsPath = __DIR__ . '/../assets';
require_once __DIR__ . '/../assets/header.php';
// Vérifier que les fichiers existent
if (!file_exists(__DIR__ . '/../../../Controller/CourseController.php')) {
    die('Fichier CourseController.php non trouvé');
}
if (!file_exists(__DIR__ . '/../../../Model/Course.php')) {
    die('Fichier Course.php non trouvé');
}

require_once __DIR__ . '/../../../Controller/CourseController.php';

// Créer l'instance du contrôleur
$courseC = new CourseController();

// Appeler la méthode listCourses() (avec un 'e')
$list = $courseC->listCourses();

// Debug: vérifier si des données sont retournées
// echo "<!-- DEBUG: Nombre de cours: " . count($list) . " -->";
?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white mr-2">
            <i class="mdi mdi-book"></i>
        </span>
        Liste des Cours
    </h3>
    <p class="page-sub-title">Gérez tous vos cours de formation</p>
</div>

        <!-- Add Course Button -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="addCourse.php" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Ajouter un nouveau cours
                </a>
            </div>
        </div>

    <?php
        // Afficher les messages de succès/erreur
        if (isset($_GET['success'])) {
            $messages = [
                'deleted' => 'Cours supprimé avec succès !',
                'updated' => 'Cours mis à jour avec succès !',
                'added' => 'Cours ajouté avec succès !'
            ];
            if (isset($messages[$_GET['success']])) {
                echo '<div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #c3e6cb;">';
                echo htmlspecialchars($messages[$_GET['success']]);
                echo '</div>';
            }
        }
        
        if (isset($_GET['error'])) {
            $errors = [
                'no_id' => 'Aucun ID fourni.',
                'not_found' => 'Cours non trouvé.',
                'delete_failed' => 'Erreur lors de la suppression.'
            ];
            if (isset($errors[$_GET['error']])) {
                echo '<div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #f5c6cb;">';
                echo htmlspecialchars($errors[$_GET['error']]);
                echo '</div>';
            }
        }
        ?>
        
        <!-- Courses Table Card -->
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Courses en ligne</h4>
        <div class="table-responsive">
        <table class="table table-hover">    <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Statut</th>
                    <th colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($list)): ?>
                    <tr>
                        <td colspan="7" class="empty-message">
                            Aucun cours trouvé. 
                            <a href="addCourse.php">Ajouter le premier cours</a>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($list as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['id'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($course['title'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($course['description'] ?? ''); ?></td>
                        <td>
                            <?php 
                            $status = $course['status'] ?? '';
                            $statusClass = ($status === 'Terminé') ? 'status-completed' : 'status-pending';
                            echo '<span class="' . $statusClass . '">' . htmlspecialchars($status) . '</span>';
                            ?>
                        </td>
                        <td class="actions">
                            <a href="showCourse.php?id=<?php echo $course['id']; ?>" class="btn" style="background: #17a2b8; color: white;">👁️ Voir</a>
                        </td>
                        <td class="actions">
                            <a href="updateCourse.php?id=<?php echo $course['id']; ?>" class="btn btn-update">✏️ Modifier</a>
                        </td>
                        <td class="actions">
                            <a href="deleteCourse.php?id=<?php echo $course['id']; ?>" 
                               class="btn btn-delete"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer le cours \"<?php echo htmlspecialchars($course['title']); ?>\" ?')">
                               🗑️ Supprimer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php require_once __DIR__ . '/../assets/footer.php'; ?>
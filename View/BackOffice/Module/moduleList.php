<?php
require_once __DIR__ . '/../../../Controller/ModuleController.php';

$moduleC = new ModuleController();
$modules = $moduleC->listModules();
?>

<?php $assetsPath = __DIR__ . '/../assets'; require_once __DIR__ . '/../assets/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white mr-2">
            <i class="mdi mdi-layers"></i>
        </span>
        Liste des Modules
    </h3>
    <p class="page-sub-title">Gérez les modules de vos cours</p>
</div>

        <!-- Add Module Button -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="addModule.php" class="btn btn-success">
                    <i class="mdi mdi-plus"></i> Ajouter un module
                </a>
            </div>
        </div>

        <!-- Modules Table Card -->
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Modules disponibles</h4>
                        <div class="table-responsive">
        <table class="table table-hover">
        <tr>
            <th>ID</th>
            <th>Course ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <tbody>
    <?php foreach ($modules as $module): ?>
        <tr>
            <td><?= htmlspecialchars($module['id']) ?></td>
            <td><?= htmlspecialchars($module['course_id']) ?></td>
            <td><?= htmlspecialchars($module['title']) ?></td>
            <td><?= htmlspecialchars($module['description']) ?></td>
            <td>
                <a href="updateModule.php?id=<?= $module['id'] ?>" class="btn btn-sm btn-warning">
                    <i class="mdi mdi-pencil"></i> Modifier
                </a> |
                <a href="deleteModule.php?id=<?= $module['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')">
                    <i class="mdi mdi-delete"></i> Supprimer
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
        </tbody>
    </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php require_once __DIR__ . '/../assets/footer.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<?php
require_once __DIR__ . '/shared/getHeader.php';
echo getPageHead('Tableau de bord', root: '../../..');

// Load controller
require_once __DIR__ . '/../../controller/UserController.php';
$uc = new UserController();

// Load Data
$totalUsers     = $uc->countUsers();
$totalTeachers  = $uc->countTeachers();
$totalStudents  = $uc->countStudents();
$trend          = $uc->getRegistrationTrend(30);
?>

<body class="bg-gray-100">

<div class="flex">

    <!-- Sidebar -->
    <?php
    require_once __DIR__ . '/getBackofficeSidebar.php';
    echo getBackofficeSidebar("", "dashboard");
    ?>

    <!-- MAIN PAGE -->
    <div class="flex-1 p-10">

        <h1 class="text-3xl font-bold mb-8">Tableau de bord</h1>

        <!-- ====================== -->
        <!-- TOP CARDS -->
        <!-- ====================== -->

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

            <!-- Users -->
            <div class="bg-white shadow-lg p-6 rounded-xl border-l-4 border-blue-500">
                <p class="text-gray-500">Nombre total d'utilisateurs</p>
                <h2 class="text-4xl font-bold mt-2">0</h2>
            </div>

            <!-- Teachers -->
            <div class="bg-white shadow-lg p-6 rounded-xl border-l-4 border-green-500">
                <p class="text-gray-500">Enseignants</p>
                <h2 class="text-4xl font-bold mt-2">0</h2>
            </div>

            <!-- Students -->
            <div class="bg-white shadow-lg p-6 rounded-xl border-l-4 border-purple-500">
                <p class="text-gray-500">Étudiants</p>
                <h2 class="text-4xl font-bold mt-2">0</h2>
            </div>

            <!-- New Users -->
            <div class="bg-white shadow-lg p-6 rounded-xl border-l-4 border-orange-500">
                <p class="text-gray-500">Nouveaux inscrits (30 jours)</p>
                <h2 class="text-4xl font-bold mt-2"><?= count($trend) ?></h2>
            </div>

        </div>

        <!-- ====================== -->
        <!-- REGISTRATION TREND -->
        <!-- ====================== -->

        <div class="bg-white shadow-lg rounded-xl p-6 mb-12">
            <h2 class="text-xl font-bold mb-4">Évolution des inscriptions (30 jours)</h2>

            <canvas id="trendChart" height="100"></canvas>
        </div>

        <!-- ====================== -->
        <!-- LOWER CARDS -->
        <!-- ====================== -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <div class="bg-white shadow-lg p-6 rounded-xl">
                <h2 class="text-xl font-bold mb-4">Vue d’ensemble des performances</h2>
                <canvas id="performanceChart" height="100"></canvas>
            </div>

            <div class="bg-white shadow-lg p-6 rounded-xl">
                <h2 class="text-xl font-bold mb-4">Activité des utilisateurs</h2>
                <canvas id="activityChart" height="100"></canvas>
            </div>

        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Données du graphique des inscriptions
    const trendLabels = <?= json_encode(array_column($trend, "date")) ?>;
    const trendCounts = <?= json_encode(array_column($trend, "count")) ?>;

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Inscriptions',
                data: trendCounts,
                borderWidth: 2
            }]
        }
    });
</script>

</body>
</html>

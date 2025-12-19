<!DOCTYPE html>
<html lang="fr">

<?php
require_once __DIR__ . '/shared/getHeader.php';
echo getPageHead('Tableau de bord', root: '../../..');

require_once __DIR__ . '/../../controller/UserController.php';
$uc = new UserController();

$totalUsers     = $uc->countUsers();
$totalTeachers  = $uc->countTeachers();
$totalStudents  = $uc->countStudents();
$trend          = $uc->getRegistrationTrend(30);

// 🔴 SÉCURITÉ : si pas assez de données → fake data temporaire
if (count($trend) < 2) {
    $trend = [
        ['date' => date('Y-m-d', strtotime('-2 days')), 'count' => 1],
        ['date' => date('Y-m-d', strtotime('-1 day')), 'count' => 3],
        ['date' => date('Y-m-d'), 'count' => 5],
    ];
}
?>

<body class="bg-gray-100">

<div class="flex">

<?php
require_once __DIR__ . '/getBackofficeSidebar.php';
echo getBackofficeSidebar("", "dashboard");
?>

<div class="flex-1 p-10">

<h1 class="text-3xl font-bold mb-8">Tableau de bord</h1>

<!-- TOP CARDS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">

<div class="bg-white shadow-lg p-6 rounded-xl border-l-4 border-blue-500">
<p class="text-gray-500">Utilisateurs</p>
<h2 class="text-4xl font-bold"><?= $totalUsers ?></h2>
</div>

<div class="bg-white shadow-lg p-6 rounded-xl border-l-4 border-green-500">
<p class="text-gray-500">Enseignants</p>
<h2 class="text-4xl font-bold"><?= $totalTeachers ?></h2>
</div>

<div class="bg-white shadow-lg p-6 rounded-xl border-l-4 border-purple-500">
<p class="text-gray-500">Étudiants</p>
<h2 class="text-4xl font-bold"><?= $totalStudents ?></h2>
</div>

<div class="bg-white shadow-lg p-6 rounded-xl border-l-4 border-orange-500">
<p class="text-gray-500">Nouveaux inscrits (30j)</p>
<h2 class="text-4xl font-bold"><?= array_sum(array_column($trend,'count')) ?></h2>
</div>

</div>

<!-- GRAPH -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">

<div class="md:col-span-2 bg-white shadow-lg rounded-xl p-6">
<h2 class="text-xl font-bold mb-4">Évolution des inscriptions</h2>

<!-- IMPORTANT -->
<canvas id="trendChart" style="width:100%; height:300px;"></canvas>
</div>

<div class="bg-white shadow-lg rounded-xl p-6 flex flex-col items-center">
<h2 class="text-xl font-bold mb-6">Répartition</h2>
<canvas id="roleChart" width="220" height="220"></canvas>
</div>

</div>

</div>
</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ===== DONNÉES PHP → JS =====
    const labels = <?= json_encode(array_column($trend,'date')) ?>;
    const data   = <?= json_encode(array_column($trend,'count')) ?>;

    console.log("COURBE OK", labels, data);

    const canvas = document.getElementById("trendChart");
    if (!canvas) {
        console.error("Canvas introuvable");
        return;
    }

    const ctx = canvas.getContext("2d");

    const gradient = ctx.createLinearGradient(0,0,0,300);
    gradient.addColorStop(0,"rgba(251,146,60,0.4)");
    gradient.addColorStop(1,"rgba(251,146,60,0.05)");

    new Chart(ctx,{
        type:"line",
        data:{
            labels: labels,
            datasets:[{
                data: data,
                borderColor:"#fb923c",
                backgroundColor: gradient,
                fill:true,
                tension:0.45,
                pointRadius:5
            }]
        },
        options:{
            responsive:true,
            plugins:{ legend:{display:false}},
            scales:{ y:{beginAtZero:true}}
        }
    });

    // ===== DOUGHNUT =====
    new Chart(document.getElementById("roleChart"),{
        type:"doughnut",
        data:{
            labels:["Étudiants","Enseignants"],
            datasets:[{
                data:[<?= $totalStudents ?>,<?= $totalTeachers ?>],
                backgroundColor:["#fb923c","#2dd4bf"]
            }]
        },
        options:{
            cutout:"70%",
            plugins:{legend:{display:false}}
        }
    });

});
</script>

</body>
</html>

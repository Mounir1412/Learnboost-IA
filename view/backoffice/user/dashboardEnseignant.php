<?php
session_start();
require_once '../../../model/config.php';

// Vérifier si l'utilisateur est connecté et est enseignant
if (!isset($_SESSION["user_id"]) || strtolower($_SESSION["user_role"]) != "enseignant") {
    die("Veuillez vous connecter en tant qu'enseignant.");
}

$idEnseignant = $_SESSION["user_id"];
$pdo = config::getConnexion();

/* Nombre de cours */
$sqlCours = $pdo->prepare("SELECT COUNT(*) FROM cours WHERE id_enseignant = ?");
$sqlCours->execute([$idEnseignant]);
$nbCours = $sqlCours->fetchColumn();

/* Nombre d'étudiants inscrits */
$sqlEtudiants = $pdo->prepare("
    SELECT COUNT(DISTINCT id_etudiant) 
    FROM inscriptions 
    WHERE id_cours IN (SELECT id FROM cours WHERE id_enseignant = ?)
");
$sqlEtudiants->execute([$idEnseignant]);
$nbEtudiants = $sqlEtudiants->fetchColumn();

/* Nombre de quiz créés */
$sqlQuiz = $pdo->prepare("SELECT COUNT(*) FROM quiz WHERE id_enseignant = ?");
$sqlQuiz->execute([$idEnseignant]);
$nbQuiz = $sqlQuiz->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LEARNBOOST - Dashboard Enseignant</title>
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { background-color:#f5f7fa; color:#333; min-height:100vh; padding:30px; }
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; border-bottom:1px solid #e0e6ed; padding-bottom:20px; }
    .header h2 { font-size:28px; font-weight:700; background:linear-gradient(135deg,#2c3e50,#3498db); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
    .user-info { display:flex; align-items:center; gap:15px; font-weight:500; background:white; padding:10px 20px; border-radius:50px; box-shadow:0 2px 10px rgba(0,0,0,0.05); }
    .user-icon { width:45px; height:45px; background:linear-gradient(135deg,#3498db,#2c3e50); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:bold; font-size:18px; }
    .stats-container { display:grid; grid-template-columns:repeat(auto-fit,minmax(280px,1fr)); gap:25px; margin-bottom:40px; }
    .stat-card { background:white; border-radius:12px; padding:30px 25px; box-shadow:0 4px 15px rgba(0,0,0,0.08); position:relative; border:1px solid #eef2f7; }
    .stat-card::before { content:''; position:absolute; top:0; left:0; width:100%; height:5px; background:linear-gradient(90deg,#3498db,#2c3e50); }
    .stat-card h3 { color:#7f8c8d; font-size:14px; text-transform:uppercase; letter-spacing:1px; margin-bottom:15px; font-weight:600; }
    .stat-number { color:#2c3e50; font-size:42px; font-weight:800; margin-bottom:15px; line-height:1; }
    .stat-trend { display:inline-block; font-size:14px; font-weight:600; padding:6px 12px; background-color:#f8fafc; border-radius:6px; }
</style>
</head>
<body>
<div class="header">
    <h2>Dashboard Enseignant</h2>
    <div class="user-info">
        <div class="user-icon"><?php echo strtoupper(substr($_SESSION["user_name"] ?? "E",0,1)); ?></div>
        <span><?php echo htmlspecialchars($_SESSION["user_name"] ?? "Enseignant"); ?></span>
    </div>
</div>

<div class="stats-container">
    <div class="stat-card">
        <h3>NOMBRE DE COURS</h3>
        <div class="stat-number"><?php echo $nbCours; ?></div>
        <div class="stat-trend">✓ Actif</div>
    </div>
    <div class="stat-card">
        <h3>ÉTUDIANTS INSCRITS</h3>
        <div class="stat-number"><?php echo $nbEtudiants; ?></div>
        <div class="stat-trend">En attente</div>
    </div>
    <div class="stat-card">
        <h3>QUIZ CRÉÉS</h3>
        <div class="stat-number"><?php echo $nbQuiz; ?></div>
        <div class="stat-trend">À configurer</div>
    </div>
    <div class="stat-card">
        <h3>ACTIVITÉS RÉCENTES</h3>
        <div class="stat-number">0</div>
        <div class="stat-trend">Aujourd'hui</div>
    </div>
</div>

</body>
</html>

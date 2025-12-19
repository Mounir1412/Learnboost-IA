<?php
/**
 * Page: Moyennes des ratings par cours
 * Affiche un résumé des avis avec la moyenne pour chaque cours
 */
$pageTitle = 'Moyennes des Ratings - BackOffice';
$assetBasePath = '../../';
include(__DIR__ . '/../assets/header.php');

require_once(dirname(__DIR__,3) . '/controllers/RatingController.php');
require_once(dirname(__DIR__,3) . '/controllers/CourseController.php');

$db = config::getConnexion();
$courseC = new CourseController();

// Récupérer tous les cours avec leurs moyennes et statistiques
$sql = <<<SQL
SELECT 
    c.id,
    c.title,
    c.description,
    COUNT(r.id) as total_ratings,
    COALESCE(AVG(r.rating), 0) as avg_rating,
    MIN(r.rating) as min_rating,
    MAX(r.rating) as max_rating,
    SUM(CASE WHEN r.rating = 5 THEN 1 ELSE 0 END) as count_5star,
    SUM(CASE WHEN r.rating = 4 THEN 1 ELSE 0 END) as count_4star,
    SUM(CASE WHEN r.rating = 3 THEN 1 ELSE 0 END) as count_3star,
    SUM(CASE WHEN r.rating = 2 THEN 1 ELSE 0 END) as count_2star,
    SUM(CASE WHEN r.rating = 1 THEN 1 ELSE 0 END) as count_1star
FROM courses c
LEFT JOIN ratings r ON c.id = r.course_id
GROUP BY c.id, c.title, c.description
ORDER BY avg_rating DESC, total_ratings DESC
SQL;

try {
    $stmt = $db->query($sql);
    $courses_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $courses_stats = [];
    error_log('Error fetching course ratings: ' . $e->getMessage());
}
?>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">⭐ Moyennes des Ratings par Cours</h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if (empty($courses_stats)): ?>
                            <div class="alert alert-info">Aucun cours disponible</div>
                        <?php else: ?>
                            <table class="table table-hover table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="width: 5%">ID</th>
                                        <th style="width: 25%">Cours</th>
                                        <th style="width: 12%; text-align: center;">Nombre d'avis</th>
                                        <th style="width: 20%; text-align: center;">Moyenne</th>
                                        <th style="width: 35%">Distribution</th>
                                        <th style="width: 10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courses_stats as $course): ?>
                                    <tr>
                                        <td><?php echo $course['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($course['title']); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo substr(htmlspecialchars($course['description'] ?? ''), 0, 50) . '...'; ?></small>
                                        </td>
                                        <td style="text-align: center; font-weight: bold;">
                                            <?php echo $course['total_ratings']; ?> avis
                                        </td>
                                        <td style="text-align: center;">
                                            <?php 
                                                $avg = round($course['avg_rating'], 2);
                                                $rating_color = 'gray';
                                                if ($avg >= 4.5) $rating_color = 'success';
                                                elseif ($avg >= 3.5) $rating_color = 'info';
                                                elseif ($avg >= 2.5) $rating_color = 'warning';
                                                else $rating_color = 'danger';
                                            ?>
                                            <span class="badge badge-<?php echo $rating_color; ?>" style="font-size: 14px; padding: 8px;">
                                                ⭐ <?php echo $avg; ?>/5
                                            </span>
                                            <br>
                                            <small>Min: <?php echo $course['min_rating'] ?? '-'; ?> | Max: <?php echo $course['max_rating'] ?? '-'; ?></small>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 4px; font-size: 12px;">
                                                <!-- 5 stars -->
                                                <span style="color: gold; min-width: 25px;">
                                                    ⭐⭐⭐⭐⭐: <?php echo $course['count_5star'] ?? 0; ?>
                                                </span>
                                                <!-- Progress bar -->
                                                <div style="width: 100px; height: 8px; background: #e0e0e0; border-radius: 4px; overflow: hidden; position: relative;">
                                                    <?php 
                                                        $total = $course['total_ratings'] ?: 1;
                                                        $p5 = ($course['count_5star'] ?? 0) / $total * 100;
                                                        $p4 = ($course['count_4star'] ?? 0) / $total * 100;
                                                        $p3 = ($course['count_3star'] ?? 0) / $total * 100;
                                                    ?>
                                                    <div style="position: absolute; height: 100%; background: gold; width: <?php echo $p5; ?>%; left: 0;"></div>
                                                    <div style="position: absolute; height: 100%; background: #90ee90; width: <?php echo $p4; ?>%; left: <?php echo $p5; ?>%;"></div>
                                                    <div style="position: absolute; height: 100%; background: #ffd700; width: <?php echo $p3; ?>%; left: <?php echo ($p5+$p4); ?>%;"></div>
                                                </div>
                                            </div>
                                            <br>
                                            <small>
                                                ⭐⭐⭐⭐: <?php echo $course['count_4star'] ?? 0; ?> | 
                                                ⭐⭐⭐: <?php echo $course['count_3star'] ?? 0; ?> | 
                                                ⭐⭐: <?php echo $course['count_2star'] ?? 0; ?> | 
                                                ⭐: <?php echo $course['count_1star'] ?? 0; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <a href="ratingsList.php?course_id=<?php echo $course['id']; ?>" class="btn btn-sm btn-info">
                                                Voir avis
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <!-- Résumé global -->
                            <hr class="my-4">
                            <div class="row mt-4">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5>Nombre total de cours</h5>
                                            <h2><?php echo count($courses_stats); ?></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5>Nombre total d'avis</h5>
                                            <h2><?php echo array_sum(array_column($courses_stats, 'total_ratings')); ?></h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5>Moyenne globale</h5>
                                            <h2>
                                                <?php 
                                                    $total_all = array_sum(array_column($courses_stats, 'total_ratings'));
                                                    $avg_all = $total_all > 0 
                                                        ? round(array_sum(array_map(function($c) { 
                                                            return ($c['avg_rating'] * $c['total_ratings']); 
                                                        }, $courses_stats)) / $total_all, 2)
                                                        : 0;
                                                    echo $avg_all;
                                                ?>
                                                ⭐
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5>Meilleur cours</h5>
                                            <h6>
                                                <?php 
                                                    $best = $courses_stats[0] ?? null;
                                                    echo $best ? htmlspecialchars(substr($best['title'], 0, 20)) . '...' : '-';
                                                ?>
                                            </h6>
                                            <p style="margin: 0;">
                                                <?php echo $best ? round($best['avg_rating'], 1) . ' ⭐' : '-'; ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../assets/footer.php'); ?>

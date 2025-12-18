<?php
$pageTitle = 'Ratings - BackOffice';
$assetBasePath = '../../';
include(__DIR__ . '/../assets/header.php');

require_once(dirname(__DIR__,3) . '/Controller/RatingController.php');
require_once(dirname(__DIR__,3) . '/Controller/CourseController.php');

$ratingC = new RatingController();
$courseC = new CourseController();

/**
 * Retourne la moyenne des ratings pour un cours donné
 * @param PDO $db
 * @param int $courseId
 * @return float
 */
function getCourseAverage($db, $courseId) {
    if (empty($courseId)) return 0;
    try {
        $stmt = $db->prepare("SELECT AVG(rating) as avg_rating FROM ratings WHERE course_id = :cid");
        $stmt->bindValue(':cid', (int)$courseId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return isset($row['avg_rating']) && $row['avg_rating'] ? round($row['avg_rating'], 2) : 0;
    } catch (Exception $e) {
        return 0;
    }
}

// Pagination + filtering
$db = config::getConnexion();
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = isset($_GET['per_page']) ? max(10, min(200, (int)$_GET['per_page'])) : 50;
$offset = ($page - 1) * $perPage;
$filterCourse = isset($_GET['course_id']) && (int)$_GET['course_id'] > 0 ? (int)$_GET['course_id'] : null;

// Count total
$countSql = 'SELECT COUNT(*) AS cnt FROM ratings' . ($filterCourse ? ' WHERE course_id = :course_id' : '');
$countStmt = $db->prepare($countSql);
if ($filterCourse) $countStmt->bindValue(':course_id', $filterCourse, PDO::PARAM_INT);
$countStmt->execute();
$total = (int)$countStmt->fetchColumn();

// Fetch page
$sql = 'SELECT r.*, c.title as course_title FROM ratings r LEFT JOIN courses c ON r.course_id = c.id' . ($filterCourse ? ' WHERE r.course_id = :course_id' : '') . ' ORDER BY r.created_at DESC LIMIT :limit OFFSET :offset';
$stmt = $db->prepare($sql);
if ($filterCourse) $stmt->bindValue(':course_id', $filterCourse, PDO::PARAM_INT);
$stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$ratings = $stmt->fetchAll();

// courses for filter dropdown
$allCourses = $courseC->listCourses();
?>
<div class="container" style="padding:2rem;">
    <h2>Liste des avis</h2>
    <form method="get" class="form-inline" style="margin-bottom:1rem;">
        <label style="margin-right:8px;">Filtrer par cours:</label>
        <select name="course_id" class="form-control" style="margin-right:8px;">
            <option value="">Tous les cours</option>
            <?php foreach($allCourses as $c): ?>
                <option value="<?php echo $c['id']; ?>" <?php echo ($filterCourse == $c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['title']); ?></option>
            <?php endforeach; ?>
        </select>
        <label style="margin-left:8px; margin-right:8px;">Par page:</label>
        <select name="per_page" class="form-control" style="margin-right:8px;">
            <?php foreach([10,25,50,100] as $pp): ?>
                <option value="<?php echo $pp; ?>" <?php echo ($perPage == $pp) ? 'selected' : ''; ?>><?php echo $pp; ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary" type="submit">Appliquer</button>
    </form>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cours</th>
                <th>Utilisateur</th>
                <th>Note</th>
                <th>Commentaire</th>
                <th>IP</th>
                <th>Créé le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($ratings as $r): ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td>
                    <?php echo htmlspecialchars($r['course_title'] ?? '—'); ?>
                    <?php $avgForCourse = getCourseAverage($db, $r['course_id'] ?? 0); ?>
                    <?php if ($avgForCourse > 0): ?>
                        <br><small class="text-muted">Moyenne: <?php echo $avgForCourse; ?> ⭐</small>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($r['user_name'] ?? 'Anonyme'); ?></td>
                <td><?php echo (int)$r['rating']; ?></td>
                <td><?php echo nl2br(htmlspecialchars($r['comment'])); ?></td>
                <td><?php echo htmlspecialchars($r['ip'] ?? ''); ?></td>
                <td><?php echo $r['created_at']; ?></td>
                <td>
                    <a href="deleteRating.php?id=<?php echo $r['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cet avis ?');">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
        <?php
        // Pagination controls
        $totalPages = max(1, (int)ceil($total / $perPage));
        if ($totalPages > 1):
        ?>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php $prev = max(1, $page-1); ?>
                <li class="page-item <?php echo $page<=1? 'disabled':''; ?>"><a class="page-link" href="?page=<?php echo $prev; ?>&per_page=<?php echo $perPage; ?><?php echo $filterCourse? '&course_id='.$filterCourse:''; ?>">Préc</a></li>
                <?php for($p=1;$p<=$totalPages;$p++): ?>
                        <li class="page-item <?php echo $p==$page? 'active':''; ?>"><a class="page-link" href="?page=<?php echo $p; ?>&per_page=<?php echo $perPage; ?><?php echo $filterCourse? '&course_id='.$filterCourse:''; ?>"><?php echo $p; ?></a></li>
                <?php endfor; ?>
                <?php $next = min($totalPages, $page+1); ?>
                <li class="page-item <?php echo $page>=$totalPages? 'disabled':''; ?>"><a class="page-link" href="?page=<?php echo $next; ?>&per_page=<?php echo $perPage; ?><?php echo $filterCourse? '&course_id='.$filterCourse:''; ?>">Suiv</a></li>
            </ul>
        </nav>
        <?php endif; ?>
</div>

<?php include(__DIR__ . '/../assets/footer.php'); ?>

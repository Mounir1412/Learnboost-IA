<?php
$pageTitle = "Détails du cours - LearnBoost AI";
$headerClass = "nav-visible"; 
$headerLogo = "logo.png"; 
$assetBasePath = '../';
include(__DIR__ . '/../includes/header.php');

require_once(__DIR__ . '/../../../Controller/CourseController.php');
require_once(__DIR__ . '/../../../Controller/ModuleController.php');
require_once(__DIR__ . '/../../../Controller/LessonController.php');

$courseC = new CourseController();
$moduleC = new ModuleController();
$lessonC = new LessonController();

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: courseList.php');
    exit;
}

$course = $courseC->showCourse($id);
if (!$course) {
    header('Location: courseList.php?error=not_found');
    exit;
}

$modules = $moduleC->listModulesByCourse($id);
// Rating helpers
require_once(__DIR__ . '/../../../Model/Rating.php');
$avgRating = Rating::avgRatingByCourse($id);
$ratingCount = Rating::countByCourse($id);
// Messages from rating submission (rate.php redirect)
$rating_ok = isset($_GET['rating_ok']) ? (int)$_GET['rating_ok'] : null;
$rating_msg = isset($_GET['rating_msg']) ? trim($_GET['rating_msg']) : null;
?>

<!-- Course Header Section -->
<div id="course-header" class="section course-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="Black-text"><?php echo htmlspecialchars($course['title']); ?></h1>
                <p class="lead Black-text">Cours - Statut: <?php echo htmlspecialchars($course['status']); ?></p>
                <div class="course-rating-summary" style="margin-top:1rem;">
                    <span style="color: #ffd700; font-size: 1.1rem;">
                        <?php for ($i=1;$i<=5;$i++): ?>
                            <?php if ($i <= round($avgRating)): ?>
                                <i class="fa fa-star"></i>
                            <?php else: ?>
                                <i class="fa fa-star-o"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </span>
                    <span style="color:#fff; margin-left:0.5rem;"><?php echo $avgRating > 0 ? $avgRating : '—'; ?></span>
                    <span style="color:#fff; margin-left:0.5rem; font-size:0.9rem;">(<?php echo $ratingCount; ?> avis)</span>
                    <a href="#rating-form" id="open-rating" class="main-button icon-button" style="margin-left:1rem; background:#fff; color:#007bff;">Donner votre avis</a>
                </div>
                <div class="breadcrumb-nav">
                    <a href="courseList.php" class="breadcrumb-link">Tous les cours</a> 
                    <span> / </span> 
                    <span><?php echo htmlspecialchars($course['title']); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Course Header Section -->

            <?php if ($rating_ok !== null): ?>
                <div class="container" style="margin-top:1rem;">
                    <div class="alert <?php echo $rating_ok ? 'alert-success' : 'alert-danger'; ?> rating-alert" role="alert">
                        <?php echo htmlspecialchars($rating_msg ?? ($rating_ok ? 'Merci pour votre avis.' : 'Erreur lors de l\'envoi.')); ?>
                    </div>
                </div>
            <?php endif; ?>

<!-- Course Description Section -->
<div id="course-about" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header text-center">
                    <h2>À propos de ce cours</h2>
                </div>
            </div>
        </div>
        <!-- Rating form (hidden by default) -->
        <div class="row" id="rating-form" style="display:none; margin-bottom:1.5rem;">
            <div class="col-md-8 col-md-offset-2">
                <form action="rate.php" method="post" id="form-rate">
                    <input type="hidden" name="course_id" value="<?php echo (int)$course['id']; ?>">
                    <div class="form-group">
                        <label>Votre note</label>
                        <div id="star-select" style="font-size:1.6rem; color:#ffd700;">
                            <?php for ($s=1;$s<=5;$s++): ?>
                                <label style="cursor:pointer; margin-right:6px;">
                                    <input type="radio" name="rating" value="<?php echo $s; ?>" style="display:none;"> 
                                    <i class="fa fa-star-o star" data-value="<?php echo $s; ?>"></i>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nom (optionnel)</label>
                        <input type="text" name="user_name" class="form-control" placeholder="Votre prénom">
                    </div>
                    <div class="form-group">
                        <label>Commentaire</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Votre avis (optionnel)"></textarea>
                    </div>
                    <div class="form-group text-center">
                        <button type="submit" class="main-button">Envoyer l'avis</button>
                        <button type="button" id="cancel-rating" class="main-button" style="background:#6c757d; margin-left:0.5rem;">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="course-description-box">
                    <p><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Course Description Section -->

<!-- Modules & Lessons Section -->
<div id="course-modules" class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header text-center">
                    <h2>Modules du cours</h2>
                    <p class="lead">Explorez tous les modules et leçons disponibles</p>
                </div>
            </div>
        </div>

        <?php if (empty($modules)): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="no-content-box">
                        <p><i class="fa fa-info-circle"></i> Aucun module disponible pour ce cours.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row modules-grid">
                <?php foreach ($modules as $module): ?>
                    <div class="col-md-12">
                        <div class="module-feature-card">
                            <div class="module-feature-icon">
                                <i class="fa fa-layers"></i>
                            </div>
                            <div class="module-feature-content">
                                <h3><?php echo htmlspecialchars($module['title']); ?></h3>
                                <p><?php echo htmlspecialchars($module['description']); ?></p>
                                
                                <!-- Lessons in Module -->
                                <?php $lessons = $lessonC->listLessonsByModule($module['id']); ?>
                                <?php if (!empty($lessons)): ?>
                                    <div class="lessons-list-wrapper">
                                        <h4><i class="fa fa-bookmark"></i> Leçons</h4>
                                        <ul class="lessons-list">
                                            <?php foreach ($lessons as $lesson): ?>
                                                <li>
                                                    <a href="lessonDetail.php?id=<?php echo $lesson['id']; ?>&course_id=<?php echo $course['id']; ?>" class="lesson-link">
                                                        <span class="lesson-title">
                                                            <i class="fa fa-file-text-o"></i> 
                                                            <?php echo htmlspecialchars($lesson['title']); ?>
                                                        </span>
                                                        <?php if (!empty($lesson['duration']) && (int)$lesson['duration'] > 0): ?>
                                                            <span class="lesson-duration">
                                                                <i class="fa fa-clock-o"></i> <?php echo (int)$lesson['duration']; ?>s
                                                            </span>
                                                        <?php endif; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <p class="no-lessons"><i class="fa fa-info-circle"></i> Aucune leçon dans ce module.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Back to Course List -->
        <div class="row">
            <div class="col-md-12 center-btn">
                <a href="courseList.php" class="main-button icon-button">
                    <i class="fa fa-arrow-left"></i> Retour à la liste des cours
                </a>
            </div>
        </div>
    </div>
</div>
<!-- /Modules & Lessons Section -->

<!-- Custom Styles for Course Detail -->
<style>
/* Course Header Background */
.course-header-bg {
    background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), 
                url('../assets/img/carousel/img_1.jpg') center/cover;
    color: white;
    padding: 5rem 0;
    text-align: center;
}

.course-header-bg h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.course-header-bg .lead {
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
}

.breadcrumb-nav {
    display: inline-block;
    background: rgba(255,255,255,0.1);
    padding: 0.5rem 1.5rem;
    border-radius: 4px;
    font-size: 0.95rem;
}

.breadcrumb-link {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-link:hover {
    color: #e0e0e0;
    text-decoration: underline;
}

/* Course Description Box */
.course-description-box {
    background: #f9f9f9;
    padding: 2rem;
    border-radius: 8px;
    border-left: 4px solid #007bff;
    line-height: 1.8;
    color: #000;
}

/* Module Feature Cards */
.module-feature-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 2rem;
    margin-bottom: 2rem;
    display: flex;
    gap: 2rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.08);
}

.module-feature-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.module-feature-icon {
    font-size: 2.5rem;
    color: #007bff;
    flex-shrink: 0;
    width: 70px;
    text-align: center;
}

.module-feature-content {
    flex: 1;
}

.module-feature-content h3 {
    font-size: 1.4rem;
    color: #000;
    margin-bottom: 0.75rem;
    font-weight: 700;
}

.module-feature-content p {
    color: #666;
    margin-bottom: 1.5rem;
}

/* Lessons List Wrapper */
.lessons-list-wrapper {
    background: #f5f5f5;
    padding: 1.5rem;
    border-radius: 6px;
    margin-top: 1.5rem;
}

.lessons-list-wrapper h4 {
    color: #333;
    font-size: 1.1rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.lessons-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.lessons-list li {
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    background: white;
    border-radius: 4px;
    border-left: 3px solid #007bff;
    transition: all 0.3s ease;
}

.lessons-list li:last-child {
    margin-bottom: 0;
}

.lessons-list li:hover {
    background: #f0f8ff;
    border-left-color: #0056b3;
    transform: translateX(4px);
}

.lesson-link {
    text-decoration: none;
    color: #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.lesson-link:hover {
    color: #007bff;
}

.lesson-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.lesson-title i {
    color: #007bff;
    font-size: 1rem;
}

.lesson-duration {
    background: #e9ecef;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.85rem;
    color: #666;
    white-space: nowrap;
}

/* No Content Box */
.no-content-box {
    text-align: center;
    padding: 3rem 2rem;
    background: #f9f9f9;
    border: 2px dashed #ddd;
    border-radius: 8px;
    color: #999;
}

.no-content-box i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #bbb;
}

.no-lessons {
    color: #999;
    font-style: italic;
    margin: 1rem 0 0 0;
}

/* Center Button */
.center-btn {
    text-align: center;
    margin-top: 2rem;
}

/* Section Header */
.section-header {
    margin-bottom: 3rem;
}

.section-header h2 {
    font-size: 2rem;
    color: #000;
    font-weight: 700;
    margin-bottom: 1rem;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.75rem;
    display: inline-block;
}

.section-header .lead {
    font-size: 1.1rem;
    color: #666;
    margin-top: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .course-header-bg {
        padding: 3rem 0;
    }

    .course-header-bg h1 {
        font-size: 1.8rem;
    }

    .module-feature-card {
        flex-direction: column;
        gap: 1rem;
    }

    .module-feature-icon {
        width: auto;
        margin-bottom: 0.5rem;
    }

    .lesson-link {
        flex-wrap: wrap;
    }

    .lesson-duration {
        margin-top: 0.5rem;
        width: 100%;
        text-align: right;
    }

    .lessons-list li {
        padding: 0.75rem;
    }

    .section-header h2 {
        font-size: 1.5rem;
    }
}
</style>

<style>
@media (max-width: 768px) {
    .lesson-link {
        flex-direction: column;
        align-items: flex-start;
    }

    .duration-badge {
        margin-top: 0.5rem;
    }

    .section-title {
        font-size: 1.5rem;
    }
}
</style>

<?php
$assetBasePath = '../';
// Chatbot removed: widget include deleted

include(__DIR__ . '/../includes/footer.php');
?>

<script>
// Rating UI: toggle form and star selection + auto-hide alerts
document.addEventListener('DOMContentLoaded', function(){
    var open = document.getElementById('open-rating');
    var form = document.getElementById('rating-form');
    var cancel = document.getElementById('cancel-rating');
    if(open){
        open.addEventListener('click', function(e){
            e.preventDefault();
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
            form.scrollIntoView({behavior:'smooth'});
        });
    }
    if(cancel){
        cancel.addEventListener('click', function(){ form.style.display='none'; });
    }

    // star selection
    var stars = document.querySelectorAll('#star-select .star');
    stars.forEach(function(st){
        st.addEventListener('mouseenter', function(){
            var v = parseInt(this.getAttribute('data-value'));
            highlightStars(v);
        });
        st.addEventListener('mouseleave', function(){
            var selected = document.querySelector('#star-select input[name="rating"]:checked');
            highlightStars(selected ? parseInt(selected.value) : 0);
        });
        st.addEventListener('click', function(){
            var v = parseInt(this.getAttribute('data-value'));
            var input = document.querySelector('#star-select input[name="rating"][value="'+v+'"]');
            if(input) input.checked = true;
            highlightStars(v);
        });
    });

    function highlightStars(value){
        stars.forEach(function(s){
            var v = parseInt(s.getAttribute('data-value'));
            if(v <= value) s.classList.remove('fa-star-o'), s.classList.add('fa-star');
            else s.classList.remove('fa-star'), s.classList.add('fa-star-o');
        });
    }

    // Auto-hide rating alert if present
    var ratingAlert = document.querySelector('.rating-alert');
    if(ratingAlert){
        setTimeout(function(){
            ratingAlert.style.transition = 'opacity 0.5s ease';
            ratingAlert.style.opacity = '0';
            setTimeout(function(){ ratingAlert.parentNode.removeChild(ratingAlert); }, 600);
        }, 5000);
    }
});
</script>

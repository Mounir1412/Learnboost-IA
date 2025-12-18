<?php
$pageTitle = "Liste des cours - LearnBoost AI";
// Use transparent header and homepage-style hero
$headerClass = "transparent-nav"; // make header overlay the hero background
$headerLogo = "logo.png"; // Logo normal for the pages
$assetBasePath = '../';
include(__DIR__ . '/../includes/header.php');

require_once(__DIR__ . '/../../../Controller/CourseController.php');
require_once(__DIR__ . '/../../../Model/Rating.php');
$courseController = new CourseController();
$courses = $courseController->listCourses();
?>

<!-- Home-style Hero (appliqué à la page Cours) -->
<div id="home" class="hero-area">
    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(<?php echo $assetBasePath; ?>assets/img/home-background.jpg)"></div>
    <!-- /Backgound Image -->
    
    <div class="home-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="white-text">Nos Cours</h1>
                    <p class="lead white-text">Découvrez nos cours en ligne et développez vos compétences.</p>
                    <a class="main-button icon-button" href="<?php echo $assetBasePath; ?>courses/courseList.php">Voir les cours</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Home-style Hero -->

<!-- Courses -->
<div id="courses" class="section">
    <div class="container">
        <div class="row">
            <div class="section-header text-center">
                <h2>Tous nos cours</h2>
                <p class="lead">Découvrez notre catalogue complet de formations.</p>
            </div>
        </div>
        
        <div id="courses-wrapper">
            <div class="row">
                <?php 
                $courseImages = ['course01.jpg', 'course02.jpg', 'course03.jpg', 'course04.jpg', 
                                'course05.jpg', 'course06.jpg', 'course07.jpg', 'course08.jpg'];
                $imageIndex = 0;
                
                if (!empty($courses)): 
                    foreach ($courses as $course): 
                ?>
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="courseDetail.php?id=<?php echo $course['id']; ?>" class="course-img">
                            <img src="../assets/img/<?php echo $courseImages[$imageIndex % count($courseImages)]; ?>" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="courseDetail.php?id=<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['title']); ?></a>
                        <?php $avg = Rating::avgRatingByCourse($course['id']); $cnt = Rating::countByCourse($course['id']); ?>
                        <div class="course-rating-small" style="margin-top:6px;">
                            <span style="color:#ffd700;">
                                <?php for($i=1;$i<=5;$i++): ?>
                                    <?php if($i <= round($avg)): ?>
                                        <i class="fa fa-star"></i>
                                    <?php else: ?>
                                        <i class="fa fa-star-o"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </span>
                            <span style="margin-left:6px; font-size:0.9rem; color:#374050;"><?php echo $avg>0 ? $avg : '—'; ?> (<?php echo $cnt; ?>)</span>
                        </div>
                        <div class="course-details">
                            <span class="course-category">Formation</span>
                            <span class="course-price course-<?php echo strtolower($course['status']) == 'terminé' ? 'premium' : 'free'; ?>">
                                <?php echo htmlspecialchars($course['status']); ?>
                            </span>
                        </div>
                        <?php if (!empty($course['description'])): ?>
                        <p class="course-description"><?php echo htmlspecialchars(substr($course['description'], 0, 100)) . '...'; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php 
                        $imageIndex++;
                    endforeach; 
                else: 
                ?>
                <div class="col-md-12 text-center">
                    <p>Aucun cours disponible pour le moment.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- /Courses -->

<?php
$assetBasePath = '../';
// Chatbot removed: widget include deleted
include(__DIR__ . '/../includes/footer.php');
?>
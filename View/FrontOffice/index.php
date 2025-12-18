<?php
$pageTitle = "LearnBoost AI - Accueil";
$assetBasePath = '';
include(__DIR__ . '/includes/header.php');

// Inclure le contrôleur pour récupérer les cours
require_once(__DIR__ . '/../../Controller/CourseController.php');
$courseController = new CourseController();
$courses = $courseController->listCourses();
?>

<!-- Home -->
<div id="home" class="hero-area">
    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(assets/img/home-background.jpg)"></div>
    <!-- /Backgound Image -->
    
    <div class="home-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="white-text">LearnBoost AI - Formation en ligne gratuite</h1>
                    <p class="lead white-text">Découvrez nos cours en ligne et développez vos compétences avec nos formations expertes.</p>
                    <a class="main-button icon-button" href="courses/courseList.php">Commencer !</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Home -->

<!-- About -->
<div id="about" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-6">
                <div class="section-header">
                    <h2>Bienvenue sur LearnBoost AI</h2>
                    <p class="lead">Votre plateforme d'apprentissage en ligne de référence.</p>
                </div>
                
                <!-- feature -->
                <div class="feature">
                    <i class="feature-icon fa fa-flask"></i>
                    <div class="feature-content">
                        <h4>Cours en ligne</h4>
                        <p>Accédez à une large gamme de cours en ligne adaptés à tous les niveaux.</p>
                    </div>
                </div>
                <!-- /feature -->
                
                <!-- feature -->
                <div class="feature">
                    <i class="feature-icon fa fa-users"></i>
                    <div class="feature-content">
                        <h4>Enseignants experts</h4>
                        <p>Apprenez auprès de professionnels expérimentés dans leur domaine.</p>
                    </div>
                </div>
                <!-- /feature -->
                
                <!-- feature -->
                <div class="feature">
                    <i class="feature-icon fa fa-comments"></i>
                    <div class="feature-content">
                        <h4>Communauté</h4>
                        <p>Rejoignez une communauté active d'apprenants et partagez vos expériences.</p>
                    </div>
                </div>
                <!-- /feature -->
            </div>
            
            <div class="col-md-6">
                <div class="about-img">
                    <img src="assets/img/about.png" alt="">
                </div>
            </div>
        </div>
        <!-- row -->
    </div>
    <!-- container -->
</div>
<!-- /About -->

<!-- Courses -->
<div id="courses" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="section-header text-center">
                <h2>Explorez nos cours</h2>
                <p class="lead">Découvrez notre sélection de formations pour développer vos compétences.</p>
            </div>
        </div>
        <!-- /row -->
        
        <!-- courses -->
        <div id="courses-wrapper">
            <!-- row -->
            <div class="row">
                <?php 
                $courseImages = ['course01.jpg', 'course02.jpg', 'course03.jpg', 'course04.jpg', 
                                'course05.jpg', 'course06.jpg', 'course07.jpg', 'course08.jpg'];
                $imageIndex = 0;
                
                if (!empty($courses)): 
                    foreach (array_slice($courses, 0, 8) as $course): 
                ?>
                <!-- single course -->
                <div class="col-md-3 col-sm-6 col-xs-6">
                    <div class="course">
                        <a href="courses/courseList.php" class="course-img">
                            <img src="assets/img/<?php echo $courseImages[$imageIndex % count($courseImages)]; ?>" alt="">
                            <i class="course-link-icon fa fa-link"></i>
                        </a>
                        <a class="course-title" href="courses/courseList.php"><?php echo htmlspecialchars($course['title']); ?></a>
                        <div class="course-details">
                            <span class="course-category">Formation</span>
                            <span class="course-price course-<?php echo strtolower($course['status']) == 'terminé' ? 'premium' : 'free'; ?>">
                                <?php echo $course['status']; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- /single course -->
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
            <!-- /row -->
        </div>
        <!-- /courses -->
        
        <div class="row">
            <div class="center-btn">
                <a class="main-button icon-button" href="courses/courseList.php">Plus de cours</a>
            </div>
        </div>
    </div>
    <!-- container -->
</div>
<!-- /Courses -->

<!-- Call To Action -->
<div id="cta" class="section">
    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(assets/img/cta1-background.jpg)"></div>
    <!-- /Backgound Image -->
    
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-6">
                <h2 class="white-text">Prêt à commencer votre parcours d'apprentissage ?</h2>
                <p class="lead white-text">Rejoignez des milliers d'apprenants et développez vos compétences dès aujourd'hui.</p>
                <a class="main-button icon-button" href="courses/courseList.php">Commencer !</a>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /Call To Action -->

<!-- Why us -->
<div id="why-us" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="section-header text-center">
                <h2>Pourquoi LearnBoost AI</h2>
                <p class="lead">Une plateforme d'apprentissage moderne et efficace.</p>
            </div>
            
            <!-- feature -->
            <div class="col-md-4">
                <div class="feature">
                    <i class="feature-icon fa fa-flask"></i>
                    <div class="feature-content">
                        <h4>Cours en ligne</h4>
                        <p>Accédez à nos cours depuis n'importe où, à tout moment.</p>
                    </div>
                </div>
            </div>
            <!-- /feature -->
            
            <!-- feature -->
            <div class="col-md-4">
                <div class="feature">
                    <i class="feature-icon fa fa-users"></i>
                    <div class="feature-content">
                        <h4>Enseignants experts</h4>
                        <p>Apprenez auprès des meilleurs professionnels du secteur.</p>
                    </div>
                </div>
            </div>
            <!-- /feature -->
            
            <!-- feature -->
            <div class="col-md-4">
                <div class="feature">
                    <i class="feature-icon fa fa-comments"></i>
                    <div class="feature-content">
                        <h4>Communauté</h4>
                        <p>Rejoignez une communauté active et bienveillante.</p>
                    </div>
                </div>
            </div>
            <!-- /feature -->
        </div>
        <!-- /row -->
        
        <hr class="section-hr">
        
        <!-- row -->
        <div class="row">
            <div class="col-md-6">
                <h3>Une expérience d'apprentissage exceptionnelle</h3>
                <p class="lead">Développez vos compétences avec nos formations de qualité.</p>
                <p>Notre plateforme vous offre un accès à des cours de haute qualité, conçus par des experts et adaptés à tous les niveaux. Que vous soyez débutant ou avancé, vous trouverez des formations qui correspondent à vos besoins.</p>
            </div>
            
            <div class="col-md-5 col-md-offset-1">
                <a class="about-video" href="#">
                    <img src="assets/img/about-video.jpg" alt="">
                    <i class="play-icon fa fa-play"></i>
                </a>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /Why us -->

<!-- Contact CTA -->
<div id="contact-cta" class="section">
    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(assets/img/cta2-background.jpg)"></div>
    <!-- Backgound Image -->
    
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center">
                <h2 class="white-text">Contactez-nous</h2>
                <p class="lead white-text">Une question ? N'hésitez pas à nous contacter.</p>
                <a class="main-button icon-button" href="contact.php">Nous contacter</a>
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /Contact CTA -->

<?php
// Chatbot removed: widget include deleted
include(__DIR__ . '/includes/footer.php');
?>
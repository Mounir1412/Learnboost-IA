<?php
$pageTitle = "Article de blog - LearnBoost AI";
$headerClass = ""; // Pas de classe transparent-nav pour les pages internes
$headerLogo = "logo.png"; // Logo normal pour les pages internes
$assetBasePath = '';
include(__DIR__ . '/includes/header.php');

// Récupérer l'ID de l'article depuis l'URL (si disponible)
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
$postTitle = "Comment démarrer avec l'apprentissage en ligne";
$postDate = date('d M, Y');
$postAuthor = "Admin";
?>

<!-- Hero-area -->
<div class="hero-area section">
    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(assets/img/blog-post-background.jpg)"></div>
    <!-- /Backgound Image -->
    
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <ul class="hero-area-tree">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="blog.php">Blog</a></li>
                    <li><?php echo htmlspecialchars($postTitle); ?></li>
                </ul>
                <h1 class="white-text"><?php echo htmlspecialchars($postTitle); ?></h1>
                <ul class="blog-post-meta">
                    <li class="blog-meta-author">Par : <a href="#"><?php echo htmlspecialchars($postAuthor); ?></a></li>
                    <li><?php echo $postDate; ?></li>
                    <li class="blog-meta-comments"><a href="#"><i class="fa fa-comments"></i> 35</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /Hero-area -->

<!-- Blog -->
<div id="blog" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- main blog -->
            <div id="main" class="col-md-9">
                <!-- blog post -->
                <div class="blog-post">
                    <p>L'apprentissage en ligne est devenu une méthode de formation de plus en plus populaire, offrant flexibilité et accessibilité à tous ceux qui souhaitent développer leurs compétences. Dans cet article, nous explorerons les meilleures façons de démarrer votre parcours d'apprentissage en ligne avec LearnBoost AI.</p>
                    
                    <p>Que vous soyez débutant ou que vous cherchiez à approfondir vos connaissances existantes, notre plateforme offre une gamme complète de cours adaptés à tous les niveaux. L'apprentissage en ligne présente de nombreux avantages, notamment la possibilité d'apprendre à votre propre rythme et depuis le confort de votre domicile.</p>
                    
                    <p>Notre équipe d'experts a conçu des cours interactifs qui combinent théorie et pratique, garantissant une expérience d'apprentissage enrichissante. Chaque cours est structuré de manière à faciliter la compréhension et la rétention des informations.</p>
                    
                    <blockquote>
                        <p>"L'apprentissage est un trésor qui suivra son propriétaire partout." - Proverbe chinois</p>
                    </blockquote>
                    
                    <p>Pour tirer le meilleur parti de votre expérience d'apprentissage en ligne, nous recommandons de créer un planning d'étude régulier. La constance est la clé du succès dans l'apprentissage. Réservez du temps chaque jour ou chaque semaine pour suivre vos cours et pratiquer ce que vous avez appris.</p>
                    
                    <p>Notre plateforme offre également des fonctionnalités interactives telles que des forums de discussion, où vous pouvez échanger avec d'autres apprenants et poser des questions à nos instructeurs. Cette communauté d'apprentissage crée un environnement stimulant et encourageant.</p>
                    
                    <p>En conclusion, l'apprentissage en ligne avec LearnBoost AI vous offre l'opportunité de développer vos compétences de manière flexible et efficace. Commencez dès aujourd'hui et découvrez un monde de possibilités d'apprentissage.</p>
                </div>
                <!-- /blog post -->
                
                <!-- blog share -->
                <div class="blog-share">
                    <h4>Partager cet article :</h4>
                    <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                    <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                    <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                </div>
                <!-- /blog share -->
                
                <!-- blog comments -->
                <div class="blog-comments">
                    <h3>5 Commentaires</h3>
                    
                    <!-- single comment -->
                    <div class="media">
                        <div class="media-left">
                            <img src="assets/img/avatar.png" alt="">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">Jean Dupont</h4>
                            <p>Excellent article ! J'ai commencé à utiliser la plateforme il y a quelques semaines et je suis vraiment impressionné par la qualité des cours. Merci pour ces conseils utiles.</p>
                            <div class="date-reply"><span><?php echo date('d M, Y'); ?> - 10:30</span><a href="#" class="reply">Répondre</a></div>
                        </div>
                        
                        <!-- comment reply -->
                        <div class="media">
                            <div class="media-left">
                                <img src="assets/img/avatar.png" alt="">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">Marie Martin</h4>
                                <p>Je suis d'accord avec vous ! La flexibilité de l'apprentissage en ligne est vraiment un avantage majeur.</p>
                                <div class="date-reply"><span><?php echo date('d M, Y'); ?> - 11:00</span><a href="#" class="reply">Répondre</a></div>
                            </div>
                        </div>
                        <!-- /comment reply -->
                        
                        <!-- comment reply -->
                        <div class="media">
                            <div class="media-left">
                                <img src="assets/img/avatar.png" alt="">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">Pierre Durand</h4>
                                <p>Les forums de discussion sont effectivement très utiles pour échanger avec la communauté.</p>
                                <div class="date-reply"><span><?php echo date('d M, Y'); ?> - 11:15</span><a href="#" class="reply">Répondre</a></div>
                            </div>
                        </div>
                        <!-- /comment reply -->
                    </div>
                    <!-- /single comment -->
                    
                    <!-- single comment -->
                    <div class="media">
                        <div class="media-left">
                            <img src="assets/img/avatar.png" alt="">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">Sophie Bernard</h4>
                            <p>Merci pour cet article très informatif. Je recommande vivement LearnBoost AI à tous ceux qui souhaitent apprendre en ligne.</p>
                            <div class="date-reply"><span><?php echo date('d M, Y', strtotime('-1 day')); ?> - 14:20</span><a href="#" class="reply">Répondre</a></div>
                        </div>
                    </div>
                    <!-- /single comment -->
                    
                    <!-- blog reply form -->
                    <div class="blog-reply-form">
                        <h3>Laisser un commentaire</h3>
                        <form method="POST" action="#">
                            <input class="input name-input" type="text" name="name" placeholder="Nom" required>
                            <input class="input email-input" type="email" name="email" placeholder="Email" required>
                            <textarea class="input" name="message" placeholder="Entrez votre message" rows="5" required></textarea>
                            <button type="submit" class="main-button icon-button">Envoyer</button>
                        </form>
                    </div>
                    <!-- /blog reply form -->
                </div>
                <!-- /blog comments -->
            </div>
            <!-- /main blog -->
            
            <!-- aside blog -->
            <div id="aside" class="col-md-3">
                <!-- search widget -->
                <div class="widget search-widget">
                    <form method="GET" action="blog.php">
                        <input class="input" type="text" name="search" placeholder="Rechercher...">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <!-- /search widget -->
                
                <!-- category widget -->
                <div class="widget category-widget">
                    <h3>Catégories</h3>
                    <a class="category" href="#">Développement Web <span>12</span></a>
                    <a class="category" href="#">CSS <span>5</span></a>
                    <a class="category" href="#">WordPress <span>24</span></a>
                    <a class="category" href="#">HTML <span>78</span></a>
                    <a class="category" href="#">Business <span>36</span></a>
                    <a class="category" href="#">Formation <span>45</span></a>
                </div>
                <!-- /category widget -->
                
                <!-- posts widget -->
                <div class="widget posts-widget">
                    <h3>Articles récents</h3>
                    
                    <!-- single posts -->
                    <div class="single-post">
                        <a class="single-post-img" href="blog-post.php">
                            <img src="assets/img/post01.jpg" alt="">
                        </a>
                        <a href="blog-post.php">Comment démarrer avec l'apprentissage en ligne</a>
                        <p><small>Par : Admin . <?php echo date('d M, Y'); ?></small></p>
                    </div>
                    <!-- /single posts -->
                    
                    <!-- single posts -->
                    <div class="single-post">
                        <a class="single-post-img" href="blog-post.php">
                            <img src="assets/img/post02.jpg" alt="">
                        </a>
                        <a href="blog-post.php">Les meilleures pratiques pour l'apprentissage en ligne</a>
                        <p><small>Par : Admin . <?php echo date('d M, Y', strtotime('-1 day')); ?></small></p>
                    </div>
                    <!-- /single posts -->
                    
                    <!-- single posts -->
                    <div class="single-post">
                        <a class="single-post-img" href="blog-post.php">
                            <img src="assets/img/post03.jpg" alt="">
                        </a>
                        <a href="blog-post.php">Développement de compétences avec LearnBoost AI</a>
                        <p><small>Par : Admin . <?php echo date('d M, Y', strtotime('-2 days')); ?></small></p>
                    </div>
                    <!-- /single posts -->
                </div>
                <!-- /posts widget -->
                
                <!-- tags widget -->
                <div class="widget tags-widget">
                    <h3>Tags</h3>
                    <a class="tag" href="#">Web</a>
                    <a class="tag" href="#">Formation</a>
                    <a class="tag" href="#">CSS</a>
                    <a class="tag" href="#">Responsive</a>
                    <a class="tag" href="#">WordPress</a>
                    <a class="tag" href="#">HTML</a>
                    <a class="tag" href="#">Site Web</a>
                    <a class="tag" href="#">Business</a>
                    <a class="tag" href="#">Apprentissage</a>
                    <a class="tag" href="#">Cours</a>
                </div>
                <!-- /tags widget -->
            </div>
            <!-- /aside blog -->
        </div>
        <!-- row -->
    </div>
    <!-- container -->
</div>
<!-- /Blog -->

<?php include(__DIR__ . '/includes/footer.php'); ?>


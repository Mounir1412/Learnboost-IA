<?php
$pageTitle = "Blog - LearnBoost AI";
$headerClass = ""; // Pas de classe transparent-nav pour les pages internes
$headerLogo = "logo.png"; // Logo normal pour les pages internes
$assetBasePath = '';
include(__DIR__ . '/includes/header.php');
?>

<!-- Hero-area -->
<div class="hero-area section">
    <!-- Backgound Image -->
    <div class="bg-image bg-parallax overlay" style="background-image:url(assets/img/page-background.jpg)"></div>
    <!-- /Backgound Image -->
    
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <ul class="hero-area-tree">
                    <li><a href="index.php">Accueil</a></li>
                    <li>Blog</li>
                </ul>
                <h1 class="white-text">Blog</h1>
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
                <!-- row -->
                <div class="row">
                    <!-- single blog -->
                    <div class="col-md-6">
                        <div class="single-blog">
                            <div class="blog-img">
                                <a href="blog-post.php">
                                    <img src="assets/img/blog01.jpg" alt="">
                                </a>
                            </div>
                            <h4><a href="blog-post.php">Comment démarrer avec l'apprentissage en ligne</a></h4>
                            <div class="blog-meta">
                                <span class="blog-meta-author">Par : <a href="#">Admin</a></span>
                                <div class="pull-right">
                                    <span><?php echo date('d M, Y'); ?></span>
                                    <span class="blog-meta-comments"><a href="#"><i class="fa fa-comments"></i> 12</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /single blog -->
                    
                    <!-- single blog -->
                    <div class="col-md-6">
                        <div class="single-blog">
                            <div class="blog-img">
                                <a href="blog-post.php">
                                    <img src="assets/img/blog02.jpg" alt="">
                                </a>
                            </div>
                            <h4><a href="blog-post.php">Les meilleures pratiques pour l'apprentissage en ligne</a></h4>
                            <div class="blog-meta">
                                <span class="blog-meta-author">Par : <a href="#">Admin</a></span>
                                <div class="pull-right">
                                    <span><?php echo date('d M, Y', strtotime('-1 day')); ?></span>
                                    <span class="blog-meta-comments"><a href="#"><i class="fa fa-comments"></i> 8</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /single blog -->
                    
                    <!-- single blog -->
                    <div class="col-md-6">
                        <div class="single-blog">
                            <div class="blog-img">
                                <a href="blog-post.php">
                                    <img src="assets/img/blog03.jpg" alt="">
                                </a>
                            </div>
                            <h4><a href="blog-post.php">Développement de compétences avec LearnBoost AI</a></h4>
                            <div class="blog-meta">
                                <span class="blog-meta-author">Par : <a href="#">Admin</a></span>
                                <div class="pull-right">
                                    <span><?php echo date('d M, Y', strtotime('-2 days')); ?></span>
                                    <span class="blog-meta-comments"><a href="#"><i class="fa fa-comments"></i> 15</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /single blog -->
                    
                    <!-- single blog -->
                    <div class="col-md-6">
                        <div class="single-blog">
                            <div class="blog-img">
                                <a href="blog-post.php">
                                    <img src="assets/img/blog04.jpg" alt="">
                                </a>
                            </div>
                            <h4><a href="blog-post.php">Conseils pour réussir vos formations en ligne</a></h4>
                            <div class="blog-meta">
                                <span class="blog-meta-author">Par : <a href="#">Admin</a></span>
                                <div class="pull-right">
                                    <span><?php echo date('d M, Y', strtotime('-3 days')); ?></span>
                                    <span class="blog-meta-comments"><a href="#"><i class="fa fa-comments"></i> 20</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /single blog -->
                </div>
                <!-- /row -->
                
                <!-- row -->
                <div class="row">
                    <!-- pagination -->
                    <div class="col-md-12">
                        <div class="post-pagination">
                            <a href="#" class="pagination-back pull-left">Précédent</a>
                            <ul class="pages">
                                <li class="active">1</li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                            </ul>
                            <a href="#" class="pagination-next pull-right">Suivant</a>
                        </div>
                    </div>
                    <!-- pagination -->
                </div>
                <!-- /row -->
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


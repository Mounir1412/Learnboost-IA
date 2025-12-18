<?php
$assetBasePath = isset($assetBasePath) ? $assetBasePath : '';
?>
    <!-- Footer -->
    <footer id="footer" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- footer logo -->
                <div class="col-md-6">
                    <div class="footer-logo">
                        <a class="logo" href="<?php echo $assetBasePath; ?>index.php">
                            <img src="<?php echo $assetBasePath; ?>assets/img/logo.png" alt="logo">
                        </a>
                    </div>
                </div>
                <!-- footer logo -->
                
                <!-- footer nav -->
                <div class="col-md-6">
                    <ul class="footer-nav">
                        <li><a href="<?php echo $assetBasePath; ?>index.php">Accueil</a></li>
                        <li><a href="<?php echo $assetBasePath; ?>index.php#about">À propos</a></li>
                        <li><a href="<?php echo $assetBasePath; ?>courses/courseList.php">Cours</a></li>
                        <li><a href="<?php echo $assetBasePath; ?>blog.php">Blog</a></li>
                        <li><a href="<?php echo $assetBasePath; ?>contact.php">Contact</a></li>
                    </ul>
                </div>
                <!-- /footer nav -->
            </div>
            <!-- /row -->
            
            <!-- row -->
            <div id="bottom-footer" class="row">
                <!-- social -->
                <div class="col-md-4 col-md-push-8">
                    <ul class="footer-social">
                        <li><a href="#" class="facebook"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#" class="twitter"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#" class="instagram"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#" class="youtube"><i class="fa fa-youtube"></i></a></li>
                        <li><a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a></li>
                    </ul>
                </div>
                <!-- /social -->
                
                <!-- copyright -->
                <div class="col-md-8 col-md-pull-4">
                    <div class="footer-copyright">
                        <span>&copy; Copyright <?php echo date('Y'); ?>. Tous droits réservés. | LearnBoost AI</span>
                    </div>
                </div>
                <!-- /copyright -->
            </div>
            <!-- row -->
        </div>
        <!-- /container -->
    </footer>
    <!-- /Footer -->
    
    <!-- preloader -->
    <div id='preloader'><div class='preloader'></div></div>
    <!-- /preloader -->
    
    <!-- jQuery Plugins -->
    <script type="text/javascript" src="<?php echo $assetBasePath; ?>assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $assetBasePath; ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo $assetBasePath; ?>assets/js/main.js"></script>
</body>
</html>
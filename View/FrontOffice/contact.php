<?php
$pageTitle = "Contact - LearnBoost AI";
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
                    <li>Contact</li>
                </ul>
                <h1 class="white-text">Contactez-nous</h1>
            </div>
        </div>
    </div>
</div>
<!-- /Hero-area -->

<!-- Contact -->
<div id="contact" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- contact form -->
            <div class="col-md-6">
                <div class="contact-form">
                    <h4>Envoyer un message</h4>
                    <form method="POST" action="#">
                        <input class="input" type="text" name="name" placeholder="Nom" required>
                        <input class="input" type="email" name="email" placeholder="Email" required>
                        <input class="input" type="text" name="subject" placeholder="Sujet" required>
                        <textarea class="input" name="message" placeholder="Entrez votre message" rows="6" required></textarea>
                        <button type="submit" class="main-button icon-button pull-right">Envoyer le message</button>
                    </form>
                </div>
            </div>
            <!-- /contact form -->
            
            <!-- contact information -->
            <div class="col-md-5 col-md-offset-1">
                <h4>Informations de contact</h4>
                <ul class="contact-details">
                    <li><i class="fa fa-envelope"></i> contact@learnboostai.com</li>
                    <li><i class="fa fa-phone"></i> +33 1 23 45 67 89</li>
                    <li><i class="fa fa-map-marker"></i> 123 Rue de l'Éducation, 75001 Paris, France</li>
                </ul>
                
                <!-- contact map -->
                <div id="contact-map"></div>
                <!-- /contact map -->
            </div>
            <!-- contact information -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /Contact -->

<?php include(__DIR__ . '/includes/footer.php'); ?>

<!-- Google Maps Script -->
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript" src="assets/js/google-map.js"></script>
<script type="text/javascript">
    // Configuration de la carte Google Maps
    function initMap() {
        var location = {lat: 48.8566, lng: 2.3522}; // Coordonnées de Paris
        var map = new google.maps.Map(document.getElementById('contact-map'), {
            zoom: 15,
            center: location
        });
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }
    // Initialiser la carte quand la page est chargée
    if (typeof google !== 'undefined' && google.maps) {
        google.maps.event.addDomListener(window, 'load', initMap);
    }
</script>


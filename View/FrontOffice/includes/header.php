<?php
// Ensure $assetBasePath is set correctly for nested pages.
// If the page didn't provide it, try to infer from the request path so
// assets (css/js/img) load correctly from nested folders (e.g. /courses/).
if (!isset($assetBasePath) || $assetBasePath === null) {
    $assetBasePath = '';
}

if ($assetBasePath === '') {
    $script = $_SERVER['SCRIPT_NAME'] ?? ($_SERVER['PHP_SELF'] ?? '');
    if ($script !== '') {
        // If the request path contains directories like /courses/ or /BackOffice pages,
        // adjust the relative path to reach the shared `assets/` folder.
        if (strpos($script, '/courses/') !== false
            || strpos($script, '/Course/') !== false
            || strpos($script, '/Lesson/') !== false
            || strpos($script, '/Module/') !== false
            || strpos($script, '/BackOffice/') !== false
        ) {
            $assetBasePath = '../';
        }
    }
}

// Default header class: prefer visible nav for internal pages if not explicitly set
if (!isset($headerClass) || $headerClass === null || $headerClass === '') {
    $headerClass = ($assetBasePath !== '') ? 'nav-visible' : 'transparent-nav';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?php echo isset($pageTitle) ? $pageTitle : 'LearnBoost AI - Plateforme d\'apprentissage'; ?></title>
    
    <!-- Favicon - Logo du projet -->
    <link rel="icon" type="image/png" href="<?php echo $assetBasePath; ?>assets/img/logo.png">
    <link rel="shortcut icon" type="image/png" href="<?php echo $assetBasePath; ?>assets/img/logo.png">
    
    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="<?php echo $assetBasePath; ?>assets/css/bootstrap.min.css"/>
    
    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="<?php echo $assetBasePath; ?>assets/css/font-awesome.min.css">
    
    <!-- Custom stylesheet -->
    <link type="text/css" rel="stylesheet" href="<?php echo $assetBasePath; ?>assets/css/style.css"/>
    <link type="text/css" rel="stylesheet" href="<?php echo $assetBasePath; ?>assets/css/custom-fixes.css"/>
    
    <!-- HTML5 shim and Respond.js for IE8 support -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- Header -->
    <header id="header" class="<?php echo isset($headerClass) ? $headerClass : 'transparent-nav'; ?>">
        <nav class="navbar" style="display:flex; align-items:center; justify-content:space-between; padding:10px 0;">
            <div class="container" style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                <!-- Logo -->
                <div class="navbar-brand">
                    <a class="logo" href="<?php echo $assetBasePath; ?>index.php">
                        <img class="site-logo" src="<?php echo $assetBasePath; ?>assets/img/<?php echo isset($headerLogo) ? $headerLogo : 'logo-alt.png'; ?>" alt="LearnBoost AI" style="max-width:150px; max-height:150px; height:auto; margin:0; padding:0; display:block;">
                    </a>
                </div>
                <!-- /Logo -->
                
                <!-- Mobile toggle -->
                <button class="navbar-toggle" onclick="document.getElementById('header').classList.toggle('nav-collapse')">
                    <span></span>
                </button>
                <!-- /Mobile toggle -->
                
                <!-- Navigation -->
                <nav id="nav" class="navbar-collapse">
                    <ul class="main-menu nav navbar-nav navbar-right">
                        <li><a href="<?php echo $assetBasePath; ?>index.php">Accueil</a></li>
                        <li><a href="<?php echo $assetBasePath; ?>index.php#about">À propos</a></li>
                        <li><a href="<?php echo $assetBasePath; ?>courses/courseList.php">Cours</a></li>
                        <li><a href="<?php echo $assetBasePath; ?>blog.php">Blog</a></li>
                        <li><a href="<?php echo $assetBasePath; ?>contact.php">Contact</a></li>
                    </ul>
                </nav>
                <!-- /Navigation -->
            </div>
        </nav>
    </header>
    <!-- /Header -->
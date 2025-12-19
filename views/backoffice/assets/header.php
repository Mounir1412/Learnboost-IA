<?php
// Determine a usable web URL for the BackOffice assets folder.
// Preferred: caller provides $assetsUrl (web path). If not, compute from the current script path.
if (!isset($assetsUrl)) {
    // Ensure config.php is loaded early so environment variables from .env are available
    $configPathEarly = dirname(__DIR__,3) . '/config.php';
    if (file_exists($configPathEarly)) {
        require_once $configPathEarly;
    }

    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
    $pos = strpos($script, '/View/BackOffice');
    if ($pos !== false) {
        $base = substr($script, 0, $pos);
        $assetsUrl = $base . '/View/BackOffice/assets';
        $backofficeBase = $base . '/View/BackOffice';
    } else {
        // Fallback to a relative path
        $assetsUrl = '/View/BackOffice/assets';
        $backofficeBase = '/View/BackOffice';
    }

    // Some template packages nest the static files under an inner `assets/` folder
    // e.g. View/BackOffice/assets/assets/... Detect that and adjust the web path.
    if (is_dir(__DIR__ . '/assets')) {
        $assetsUrl = rtrim($assetsUrl, '/') . '/assets';
        $assetsFsSub = __DIR__ . '/assets';
    } else {
        $assetsFsSub = __DIR__;
    }

    // Filesystem path used for file_exists checks below
    $assetsFs = $assetsFsSub;
    // Debug: log computed base paths to help diagnose 404s from incorrect URLs
    $debugMsg = '[' . date('Y-m-d H:i:s') . '] BackOffice header: SCRIPT_NAME=' . ($_SERVER['SCRIPT_NAME'] ?? '') . "\n";
    $debugMsg .= '[' . date('Y-m-d H:i:s') . '] BackOffice header: computed backofficeBase=' . ($backofficeBase ?? '') . "\n";
    $debugMsg .= '[' . date('Y-m-d H:i:s') . '] BackOffice header: computed assetsUrl=' . ($assetsUrl ?? '') . "\n";
    @file_put_contents(__DIR__ . '/debug_header.log', $debugMsg, FILE_APPEND);

    // Also render a small debug banner only when explicitly enabled.
    // Controlled by BACKOFFICE_DEBUG env var: set to '1' to show, otherwise the banner is hidden.
    $backofficeDebugEnv = (defined('BACKOFFICE_DEBUG') ? constant('BACKOFFICE_DEBUG') : getenv('BACKOFFICE_DEBUG'));
    $showDebug = ($backofficeDebugEnv === '1');

    if ($showDebug) {
      $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
      $requestUri = $_SERVER['REQUEST_URI'] ?? '';
      $banner = '<div style="position:fixed;bottom:8px;right:8px;z-index:9999;background:rgba(0,0,0,0.75);color:#fff;padding:8px 12px;border-radius:6px;font-size:12px;line-height:1.3;max-width:420px;box-shadow:0 2px 8px rgba(0,0,0,0.5)">';
      $banner .= '<strong>DEBUG</strong><br/>';
      $banner .= 'SCRIPT: ' . htmlspecialchars($scriptName) . '<br/>';
      $banner .= 'REQUEST: ' . htmlspecialchars($requestUri) . '<br/>';
      $banner .= 'BACKOFFICE_BASE: ' . htmlspecialchars($backofficeBase ?? '') . '<br/>';
      $banner .= 'ASSETS_URL: ' . htmlspecialchars($assetsUrl ?? '') . '</div>';
      echo $banner;
    }

      // Count recent ratings (last 24 hours) to show notification badge in navbar
      $recentCount = 0;
      $configPath = dirname(__DIR__,3) . '/config.php';
      if (file_exists($configPath)) {
        require_once $configPath;
        try {
          $db = config::getConnexion();
          $row = $db->query("SELECT COUNT(*) AS cnt FROM ratings WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)")->fetch();
          $recentCount = isset($row['cnt']) ? (int)$row['cnt'] : 0;
        } catch (Exception $e) {
          $recentCount = 0;
        }
      }
      
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Learnboost AI - BackOffice</title>
    <?php // Conditionally include vendor and theme CSS if present in the assets folder ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/mdi/css/materialdesignicons.min.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/mdi/css/materialdesignicons.min.css">
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/css/vendor.bundle.base.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/css/vendor.bundle.base.css">
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/jvectormap/jquery-jvectormap.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/jvectormap/jquery-jvectormap.css">
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/flag-icon-css/css/flag-icon.min.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/flag-icon-css/css/flag-icon.min.css">
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/owl-carousel-2/owl.carousel.min.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/owl-carousel-2/owl.carousel.min.css">
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/owl-carousel-2/owl.theme.default.min.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/owl-carousel-2/owl.theme.default.min.css">
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/css/style.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/css/style.css">
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/css/images.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/css/images.css">
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/css/custom.css')): ?>
        <link rel="stylesheet" href="<?php echo htmlspecialchars($assetsUrl); ?>/css/custom.css">
    <?php endif; ?>
    <link rel="shortcut icon" href="<?php echo htmlspecialchars($assetsUrl); ?>/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="<?php echo htmlspecialchars($backofficeBase); ?>/index.php">
            <?php if (file_exists($assetsFs . '/images/logo.svg')): ?>
              <img src="<?php echo htmlspecialchars($assetsUrl); ?>/images/logo.svg?v=<?php echo time(); ?>" alt="logo" />
            <?php elseif (file_exists($assetsFs . '/images/logo-alt.png')): ?>
              <img src="<?php echo htmlspecialchars($assetsUrl); ?>/images/logo-alt.png?v=<?php echo time(); ?>" alt="logo" style="max-height: 120px;" />
            <?php else: ?>
              <span>Learnboost AI</span>
            <?php endif; ?>
          </a>
          <a class="sidebar-brand brand-logo-mini" href="<?php echo htmlspecialchars($backofficeBase); ?>/index.php">
            <?php if (file_exists($assetsFs . '/images/favicon.png')): ?>
              <img src="<?php echo htmlspecialchars($assetsUrl); ?>/images/favicon.png?v=<?php echo time(); ?>" alt="logo" style="max-height: 70px;" />
            <?php elseif (file_exists($assetsFs . '/images/logo-alt.png')): ?>
              <img src="<?php echo htmlspecialchars($assetsUrl); ?>/images/logo-alt.png?v=<?php echo time(); ?>" alt="logo" style="max-height: 70px;" />
            <?php else: ?>
              <span>LA</span>
            <?php endif; ?>
          </a>
        </div>
        <ul class="nav">
          <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo htmlspecialchars($backofficeBase); ?>/index.php">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Tableau de bord</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo htmlspecialchars($backofficeBase); ?>/Course/courseList.php">
              <span class="menu-icon">
                <i class="mdi mdi-book"></i>
              </span>
              <span class="menu-title">Cours</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo htmlspecialchars($backofficeBase); ?>/Course/ratingsList.php">
              <span class="menu-icon">
                <i class="mdi mdi-star"></i>
              </span>
              <span class="menu-title">Avis / Ratings</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo htmlspecialchars($backofficeBase); ?>/Module/moduleList.php">
              <span class="menu-icon">
                <i class="mdi mdi-layers"></i>
              </span>
              <span class="menu-title">Modules</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo htmlspecialchars($backofficeBase); ?>/Lesson/lessonList.php">
              <span class="menu-icon">
                <i class="mdi mdi-file-document"></i>
              </span>
              <span class="menu-title">Leçons</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo htmlspecialchars($backofficeBase); ?>/Verification.php">
              <span class="menu-icon">
                <i class="mdi mdi-check-circle"></i>
              </span>
              <span class="menu-title">Vérification</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="<?php echo htmlspecialchars($backofficeBase); ?>/Archive/archiveList.php">
              <span class="menu-icon">
                <i class="mdi mdi-archive"></i>
              </span>
              <span class="menu-title">Archives</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="<?php echo htmlspecialchars($backofficeBase); ?>/index.php">
              <?php if (file_exists($assetsFs . '/images/favicon.png')): ?>
                <img src="<?php echo htmlspecialchars($assetsUrl); ?>/images/favicon.png" alt="logo" style="max-height: 50px;" />
              <?php elseif (file_exists($assetsFs . '/images/logo-alt.png')): ?>
                <img src="<?php echo htmlspecialchars($assetsUrl); ?>/images/logo-alt.png" alt="logo" style="max-height: 50px;" />
              <?php else: ?>
                <span>LA</span>
              <?php endif; ?>
            </a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav w-100">
              <li class="nav-item w-100">
                <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search" method="GET">
                  <input type="text" class="form-control" placeholder="Rechercher..." id="searchInput">
                </form>
              </li>
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              <li class="nav-item nav-settings d-none d-lg-block">
                <a class="nav-link" href="#">
                  <i class="mdi mdi-view-grid"></i>
                </a>
              </li>
              <li class="nav-item dropdown border-left">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                  <i class="mdi mdi-email"></i>
                  <span class="count bg-success"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                  <h6 class="p-3 mb-0">Messages</h6>
                  <div class="dropdown-divider"></div>
                </div>
              </li>
              <li class="nav-item dropdown border-left">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                  <i class="mdi mdi-bell"></i>
                  <span class="count bg-danger"><?php echo isset($recentCount) && $recentCount>0 ? (int)$recentCount : ''; ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                  <h6 class="p-3 mb-0">Notifications</h6>
                  <div class="dropdown-divider"></div>
                </div>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                  <div class="navbar-profile">
                    <p class="mb-0 d-none d-sm-block navbar-profile-name">Admin</p>
                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                  <h6 class="p-3 mb-0">Profil</h6>
                  <div class="dropdown-divider"></div>
                </div>
              </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
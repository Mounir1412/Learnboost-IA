<?php
// Footer: include template JS if present in the assets folder provided by the template.
?>
          </div>
        </div>
        <!-- main-panel ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © Learnboost AI 2025</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">BackOffice Admin</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/js/vendor.bundle.base.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/js/vendor.bundle.base.js"></script>
    <?php endif; ?>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/chart.js/Chart.min.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/chart.js/Chart.min.js"></script>
    <?php endif; ?>

    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/progressbar.js/progressbar.min.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/progressbar.js/progressbar.min.js"></script>
    <?php endif; ?>

    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/jvectormap/jquery-jvectormap.min.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <?php endif; ?>

    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/jvectormap/jquery-jvectormap-world-mill-en.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <?php endif; ?>

    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/vendors/owl-carousel-2/owl.carousel.min.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/vendors/owl-carousel-2/owl.carousel.min.js"></script>
    <?php endif; ?>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/js/off-canvas.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/off-canvas.js"></script>
    <?php endif; ?>

    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/js/hoverable-collapse.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/hoverable-collapse.js"></script>
    <?php endif; ?>

    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/js/misc.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/misc.js"></script>
    <?php endif; ?>

    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/js/settings.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/settings.js"></script>
    <?php endif; ?>

    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/js/todolist.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/todolist.js"></script>
    <?php endif; ?>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/js/dashboard.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/dashboard.js"></script>
    <?php endif; ?>
    <?php if (!empty($assetsFs) && file_exists($assetsFs . '/js/custom.js')): ?>
        <script src="<?php echo htmlspecialchars($assetsUrl); ?>/js/custom.js"></script>
    <?php endif; ?>
    <!-- End custom js for this page -->
  </body>
</html>

<!DOCTYPE html>
<html lang="en">

<?php

require_once __DIR__ . '/../shared/getHeader.php';
echo getPageHead('Page', root: '../../..');
?>

<body>

    <div class="flex flex-1 overflow-hidden h-screens">
        
        <!-- Sidebar -->
        <?php
        require_once __DIR__ . '/./getBackofficeSidebar.php';
        echo getBackofficeSidebar("", "users"); // "users" = onglet actif
        ?>

        <!-- Header + Main -->
        <div class="flex flex-col flex-1 overflow-hidden h-screen">
            
            <!-- Header -->
            <?php
            require_once __DIR__ . '/./getBackofficeHeader.php';
            echo getBackofficeHeader();
            ?>

            <!-- MAIN CONTENT -->
            <main class="flex flex-col flex-1 p-5 bg-gray-300 overflow-hidden">
                <div class="relative container mx-auto flex flex-col flex-1 overflow-hidden">

                    <!-- ❌ SUPPRIMÉ : Tendance des inscriptions -->

                    <!-- TABLEAU DES UTILISATEURS -->
                    <div class="overflow-auto bg-white rounded shadow p-4">
                        <?php include __DIR__ . '/./user/listUser.php'; ?>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <!-- Scripts -->
    <?php
    require_once __DIR__ . '/./shared/getScripts.php';
    echo getScripts('../../..');
    ?>

</body>

</html>

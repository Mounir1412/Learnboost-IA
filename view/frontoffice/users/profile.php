<?php?>
<div class="container mt-5">

    <h2>Mon Profil</h2>

    <div class="card" style="padding: 20px;">

        <p><strong>Nom :</strong> <?php echo $_SESSION['user']['nom']; ?></p>
        <p><strong>Email :</strong> <?php echo $_SESSION['user']['email']; ?></p>
        <p><strong>Rôle :</strong> <?php echo $_SESSION['user']['role']; ?></p>

        <p><strong>Niveau :</strong>
            <?php
                if ($_SESSION['user']['niveau'] == null) {
                    echo "Non défini";
                } else {
                    echo $_SESSION['user']['niveau'];
                }
            ?>
        </p>

        <hr>

        <!-- Bouton TEST -->
        <?php if ($_SESSION['user']['niveau'] == null): ?>
            <a href="index.php?page=testStart" class="btn btn-warning">Passer le test de niveau</a>
        <?php else: ?>
            <a href="index.php?page=courses" class="btn btn-success">Voir mes cours</a>
        <?php endif; ?>

        <a href="index.php?page=logout" class="btn btn-danger" style="margin-top: 10px;">
            Déconnexion
        </a>

    </div>

</div>


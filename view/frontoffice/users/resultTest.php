<?php ?>

<div class="container mt-5">

    <h2>Résultat du test</h2>

    <div class="card p-4">

        <p><strong>Score :</strong> <?php echo $score; ?>/100</p>

        <p><strong>Niveau obtenu :</strong>
            <?php echo $niveau; ?>
        </p>

        <p>
            Félicitations ! Vous avez obtenu le niveau 
            <strong><?php echo $niveau; ?></strong>.
        </p>

        <hr>

        <a href="index.php?page=courses" class="btn btn-primary">
            Voir mes cours
        </a>

        <a href="index.php?page=profile" class="btn btn-secondary" style="margin-left: 10px;">
            Retour au profil
        </a>

    </div>

</div>

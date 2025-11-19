<?php ?>

<div class="container mt-5">

    <h2>Test de niveau</h2>

    <div class="card p-4">

        <!-- Afficher la question -->
        <p><strong>Question :</strong> 
            <?php echo $question['texte']; ?>
        </p>

        <!-- Formulaire pour choisir la réponse -->
        <form method="POST" action="index.php?page=saveAnswer">

            <?php foreach ($question['choix'] as $index => $choix): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" 
                           name="reponse" value="<?php echo $index; ?>" required>
                    <label class="form-check-label">
                        <?php echo $choix; ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <input type="hidden" name="numero_question" value="<?php echo $numero_question; ?>">

            <button type="submit" class="btn btn-primary mt-3">
                Suivant
            </button>
        </form>

    </div>

</div>

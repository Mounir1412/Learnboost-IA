
<form action="index.php?page=register" method="POST">

    <div class="form-group">
        <label>Nom :</label>
        <input type="text" name="nom" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Email :</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Mot de passe :</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Confirmer mot de passe :</label>
        <input type="password" name="confirm_password" class="form-control" required>
    </div>

    <!-- rôle par défaut -->
    <input type="hidden" name="role" value="apprenant">

    <button type="submit" class="btn btn-primary">Créer un compte</button>

    <div style="margin-top: 10px;">
        <a href="index.php?page=login">J'ai déjà un compte</a>
    </div>

</form>

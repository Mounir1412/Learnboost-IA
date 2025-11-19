<form action="index.php?page=login" method="POST">

    <div class="form-group">
        <label>Email :</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
        <label>Mot de passe :</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Se connecter</button>

    <div style="margin-top: 10px;">
        <a href="#">Mot de passe oublié ?</a><br>
        <a href="index.php?page=register">Créer un compte</a>
    </div>

</form>
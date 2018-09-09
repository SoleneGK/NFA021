<p id="chemin_page">Profil</p>

<p>
	Pseudo : <?= $_SESSION['utilisateur']->pseudo ?><br />
	Mail : <?= $_SESSION['utilisateur']->mail ?>
</p>

<hr />

<form method="post">
	<?= isset($message_erreur_1) ? '<div class="alert alert-danger">'.$message_erreur_1.'</div>' : '' ?>
	<div class="form-group">
		<label for="pseudo">Changer le pseudo :</label>
		<input type="text" id="pseudo" name="pseudo" class="form-control" value="<?= afficher($_SESSION['utilisateur']->pseudo) ?>" required />
	</div>
	<div class="form-group">
		<label for="mail">Changer le mail :</label>
		<input type="text" id="mail" name="mail" class="form-control" value="<?= afficher($_SESSION['utilisateur']->mail) ?>" required />
	</div>
	<input type="submit" name="modifier_pseudo_mail" class="btn input" value="Modifier" />
</form>

<hr />

<form method="post">
	<?= isset($message_erreur_2) ? '<div class="alert alert-danger">'.$message_erreur_2.'</div>' : '' ?>
	<?= isset($message_succes) ? '<div class="alert alert-success">'.$message_succes.'</div>' : '' ?>
	<div class="form-group">
		<label for="ancien_mot_de_passe">Ancien mot de passe : </label>
		<input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" class="form-control"  required />
	</div>
	<div class="form-group">
		<label for="nouveau_mot_de_passe_1">Nouveau mot de passe : </label>
		<input type="password" id="nouveau_mot_de_passe_1" name="nouveau_mot_de_passe_1" class="form-control" required />
	</div>
	<div class="form-group">
		<label for="nouveau_mot_de_passe_2">Nouveau mot de passe : </label>
		<input type="password" id="nouveau_mot_de_passe_2" name="nouveau_mot_de_passe_2" class="form-control" required />
	</div>
	<input type="submit"name="modifier_mdp" class="btn input" value="Modifier le mot de passe" />
</form>
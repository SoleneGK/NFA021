<p id="chemin_page">Utilisateurs > Ajouter</p>

<form method="post">
	<?= isset($message_erreur) ? '<div class="alert alert-danger" role="alert">'.$message_erreur.'</div>' : '' ?>
	<div class="form-group">
		<label for="pseudo">Pseudo :</label>
		<input type="text" id="pseudo" name="pseudo" class="form-control" value="<?= isset($_POST['pseudo']) ? afficher($_POST['pseudo']) : '' ?>" required />
	</div>

	<div class="form-group">
		<label for="mail_1">Mail :</label>
		<input type="text" id="mail_1" name="mail_1" class="form-control" value="<?= isset($_POST['mail_1']) ? afficher($_POST['mail_1']) : '' ?>" required />
	</div>

	<div class="form-group">
		<label for="mail_2">Réécrire le mail :</label>
		<input type="text" id="mail_2" name="mail_2" class="form-control" value="<?= isset($_POST['mail_2']) ? afficher($_POST['mail_2']) : '' ?>" required />
	</div>
	<input type="submit" class="btn input" value="Envoyer" />
</form>


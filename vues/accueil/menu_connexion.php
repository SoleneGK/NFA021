<div id="banniere"><a href='admin.php'>Empreinte</a></div>

<main>

	<?= isset($message_erreur) ? '<div class="alert alert-danger">'.$message_erreur.'</div>' : '' ?>
	<?= isset($message_succes) ? '<div class="alert alert-success">'.$message_succes.'</div>' : '' ?>

	<form method="post" action="admin.php">
		<div class="form-group">
			<label for="mail_connexion">Mail :</label>
			<input type="text" id="mail_connexion" name="mail_connexion" class="form-control" required />
		</div>
		<div class="form-group">
			<label for="mail_connexion">Mot de passe :</label>
			<input type="password" id="mot_de_passe_connexion" name="mot_de_passe_connexion" class="form-control" required />
		</div>
		<p><a href="admin.php?mot_de_passe_perdu">Mot de passe oublié</a></p>
		<input type="submit" class="btn input" value="Envoyer" />
	</form>
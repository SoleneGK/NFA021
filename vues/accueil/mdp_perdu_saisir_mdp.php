<div id="banniere_accueil"><a href='admin.php'>Empreinte</a></div>

<main class="mt-3">

	<?= isset($message_erreur) ? '<div class="alert alert-danger">'.$message_erreur.'</div>' : '' ?>
	<form method="post">
		<div class="form-group">
			<label for="mot_de_passe_1">Entrez le nouveau mot de passe :</label>
			<input type="password" id="mot_de_passe_1" name="mot_de_passe_1" class="form-control" required />
		</div>
		<div class="form-group">
			<label for="mot_de_passe_2">Confirrmez le mot de passe :</label>
			<input type="password" id="mot_de_passe_2" name="mot_de_passe_2" class="form-control" required />
		</div>
		<input type="submit" class="btn input" value="Modifier" />
	</form>




<p id="chemin_page">Utilisateurs > Afficher</p>

<p>
	Pseudo : <?= $utilisateur->pseudo ?><br>
	Mail : <?= $utilisateur->mail ?>
</p>

<hr>

<form method="post">
	<?= isset($message_erreur_1) ? '<div class="alert alert-danger">'.$message_erreur_1.'</div>' : '' ?>
	<div class="form-group">
		<label for="pseudo">Changer le pseudo :</label>
		<input type="text" id="pseudo" name="pseudo" class="form-control" value="<?= afficher($utilisateur->pseudo) ?>" required>
	</div>
	<div class="form-group">
		<label for="mail">Changer le mail :</label>
		<input type="text" id="mail" name="mail" class="form-control" value="<?= afficher($utilisateur->mail) ?>" required>
	</div>
	<input type="submit" class="btn input" value="Modifier">
</form>

<hr>

<form method="post">
	<div class="input-group mb-3">
	<div class="input-group-prepend">
		<label class="input-group-text" for="droit_tout">Tout</label>
		</div>
		<select class="custom-select" id="droit_tout" name="<?= Section::TOUT ?>" required>
			<option value="<?= Utilisateur::ADMIN ?>" <?= $droits[Section::TOUT] == Utilisateur::ADMIN ? 'selected ' : ' ' ?> >Administrateur</option>
			<option value="<?= Utilisateur::SANS_DROIT ?>" <?= $droits[Section::TOUT] == Utilisateur::SANS_DROIT ? 'selected ' : ' ' ?> >Non admin</option>
		</select>
	</div>

	<div class="input-group mb-3">
	<div class="input-group-prepend">
		<label class="input-group-text" for="droit_photos">Photos</label>
		</div>
		<select class="custom-select" id="droit_photos" name="<?= Section::PHOTOS ?>" required>
			<option value="<?= Utilisateur::MODERATEUR ?>" <?= $droits[Section::PHOTOS] == Utilisateur::MODERATEUR ? 'selected ' : ' ' ?> >Modérateur</option>
			<option value="<?= Utilisateur::CONTRIBUTEUR ?>" <?= $droits[Section::PHOTOS] == Utilisateur::CONTRIBUTEUR ? 'selected ' : ' ' ?> >Contributeur</option>
			<option value="<?= Utilisateur::SANS_DROIT ?>" <?= $droits[Section::PHOTOS] == Utilisateur::SANS_DROIT ? 'selected ' : ' ' ?> >Aucun droit</option>
		</select>
	</div>

	<div class="input-group mb-3">
	<div class="input-group-prepend">
		<label class="input-group-text" for="droit_politique">Articles politique</label>
		</div>
		<select class="custom-select" id="droit_politique" name="<?= Section::POLITIQUE ?>" required>
			<option value="<?= Utilisateur::MODERATEUR ?>" <?= $droits[Section::POLITIQUE] == Utilisateur::MODERATEUR ? 'selected ' : ' ' ?> >Modérateur</option>
			<option value="<?= Utilisateur::CONTRIBUTEUR ?>" <?= $droits[Section::POLITIQUE] == Utilisateur::CONTRIBUTEUR ? 'selected ' : ' ' ?> >Contributeur</option>
			<option value="<?= Utilisateur::SANS_DROIT ?>" <?= $droits[Section::POLITIQUE] == Utilisateur::SANS_DROIT ? 'selected ' : ' ' ?> >Aucun droit</option>
		</select>
	</div>

	<div class="input-group mb-3">
	<div class="input-group-prepend">
		<label class="input-group-text" for="droit_voyage">Articles voyage</label>
		</div>
		<select class="custom-select" id="droit_voyage" name="<?= Section::VOYAGE ?>" required>
			<option value="<?= Utilisateur::MODERATEUR ?>" <?= $droits[Section::VOYAGE] == Utilisateur::MODERATEUR ? 'selected ' : ' ' ?> >Modérateur</option>
			<option value="<?= Utilisateur::CONTRIBUTEUR ?>" <?= $droits[Section::VOYAGE] == Utilisateur::CONTRIBUTEUR ? 'selected ' : ' ' ?> >Contributeur</option>
			<option value="<?= Utilisateur::SANS_DROIT ?>" <?= $droits[Section::VOYAGE] == Utilisateur::SANS_DROIT ? 'selected ' : ' ' ?> >Aucun droit</option>
		</select>
	</div>

	<input type="submit" class="btn input" value="Modifier les droits">
</form>


<?= isset($message) ? $message : '' ?>

<p>
	Pseudo : <?= $utilisateur->pseudo ?><br />
	Mail : <?= $utilisateur->mail ?>

<form method="post">
	Changer le pseudo : <input type="text" name="pseudo" value="<?= $utilisateur->pseudo ?>" required /><br />
	Changer le mail : <input type="text" name="mail" value="<?= $utilisateur->mail ?>" required /><br />
	<input type="submit" value="Modifier" />
</form>

<form method="post">
	Tout le site :
	<select name="<?= Section::TOUT ?>" required>
		<option value="<?= Utilisateur::ADMIN ?>" <?= $droits[Section::TOUT] == Utilisateur::ADMIN ? 'selected ' : ' ' ?> >Administrateur</option>
		<option value="<?= Utilisateur::SANS_DROIT ?>" <?= $droits[Section::TOUT] == Utilisateur::SANS_DROIT ? 'selected ' : ' ' ?> >Non admin</option>
	</select><br />
	Photos :
	<select name="<?= Section::PHOTOS ?>" required>
		<option value="<?= Utilisateur::MODERATEUR ?>" <?= $droits[Section::PHOTOS] == Utilisateur::MODERATEUR ? 'selected ' : ' ' ?> >Modérateur</option>
		<option value="<?= Utilisateur::CONTRIBUTEUR ?>" <?= $droits[Section::PHOTOS] == Utilisateur::CONTRIBUTEUR ? 'selected ' : ' ' ?> >Contributeur</option>
		<option value="<?= Utilisateur::SANS_DROIT ?>" <?= $droits[Section::PHOTOS] == Utilisateur::SANS_DROIT ? 'selected ' : ' ' ?> >Aucun droit</option>
	</select><br />
	Articles politiques :
	<select name="<?= Section::POLITIQUE ?>" required>
		<option value="<?= Utilisateur::MODERATEUR ?>" <?= $droits[Section::POLITIQUE] == Utilisateur::MODERATEUR ? 'selected ' : ' ' ?> >Modérateur</option>
		<option value="<?= Utilisateur::CONTRIBUTEUR ?>" <?= $droits[Section::POLITIQUE] == Utilisateur::CONTRIBUTEUR ? 'selected ' : ' ' ?> >Contributeur</option>
		<option value="<?= Utilisateur::SANS_DROIT ?>" <?= $droits[Section::POLITIQUE] == Utilisateur::SANS_DROIT ? 'selected ' : ' ' ?> >Aucun droit</option>
	</select><br />
	Articles voyages :
	<select name="<?= Section::VOYAGE ?>" required>
		<option value="<?= Utilisateur::MODERATEUR ?>" <?= $droits[Section::VOYAGE] == Utilisateur::MODERATEUR ? 'selected ' : ' ' ?> >Modérateur</option>
		<option value="<?= Utilisateur::CONTRIBUTEUR ?>" <?= $droits[Section::VOYAGE] == Utilisateur::CONTRIBUTEUR ? 'selected ' : ' ' ?> >Contributeur</option>
		<option value="<?= Utilisateur::SANS_DROIT ?>" <?= $droits[Section::VOYAGE] == Utilisateur::SANS_DROIT ? 'selected ' : ' ' ?> >Aucun droit</option>
	</select><br />
	<input type="submit" value="Modifier" />
</form>
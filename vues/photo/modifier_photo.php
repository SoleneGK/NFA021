<form method="post">
	Titre : <input type="text" name="titre_photo" value="<?= $photo->titre ?>" required /><br />
	Catégorie : 
	<select name="id_categorie" required>

<?php
	foreach ($categories as $categorie):
?>
		<option value="<?= $categorie->id ?>" <?= $categorie->id == $photo->categorie->id ? 'selected' : '' ?>><?= $categorie->nom ?></option>
<?php
	endforeach;
?>
	
	</select><br />
	Ajoutée par 
	<select name="id_utilisateur" required>

<?php
	foreach ($utilisateurs as $utilisateur):
?>
		<option value="<?= $utilisateur->id ?>" <?= $utilisateur->id == $photo->utilisateur->id ? 'selected' : '' ?>><?= $utilisateur->pseudo ?></option>
<?php
	endforeach;
?>
	
	</select><br />
	<textarea name="description_photo"><?= $photo->description ?></textarea><br />
	<input type="submit" value="Modifier" />
</form>


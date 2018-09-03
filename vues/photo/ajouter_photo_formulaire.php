<form method="post" enctype="multipart/form-data">
	Fichier : <input type="file" name="image" required /><br />

<?php
	if ($_GET['section'] == 'photos'):
?>

	Catégorie :
	<select name="id_categorie" required>

<?php
		foreach($categories as $categorie):
?>

		<option value="<?= $categorie->id ?>"><?= $categorie->nom ?></option>

<?php
		endforeach;
?>

	</select><br />

<?php
	endif;
?>

	Titre : <input type="text" name="titre_photo" required /><br />
	Description<br />
	<textarea name="description_photo"></textarea><br />
	<input type="submit" name="ajouter_photo" value="Ajouter" />
</form>


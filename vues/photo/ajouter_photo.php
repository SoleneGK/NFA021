<h1>Ajouter une photo</h1>

<?= isset($message) ? $message : '' ?>

<form method="post" enctype="multipart/form-data">
	Fichier : <input type="file" name="image" required /><br />
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
	Titre : <input type="text" name="titre_photo" required /><br />
	Description<br />
	<textarea name="description_photo"></textarea><br />
	<input type="submit" value="Ajouter" />
</form>


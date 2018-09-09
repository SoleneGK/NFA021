<p id="chemin_page">Photos > Ajouter</p>

<form method="post" enctype="multipart/form-data">
	<?= isset($message_erreur) ? '<div class="alert alert-danger">'.$message_erreur.'</div>' : '' ?>
	<?= isset($message_succes) ? '<div class="alert alert-success">'.$message_succes.'</div>' : '' ?>

	<div class="form-group">
		<input type="file" class="btn" name="image" required />
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<label class="input-group-text" for="id_categorie">Catégorie</label>
		</div>
		<select class="custom-select" id="id_categorie" name="id_categorie" required>

<?php
	foreach($categories as $categorie):
?>

			<option value="<?= $categorie->id ?>" <?= (isset($_POST['id_categorie']) && $categorie->id == $_POST['id_categorie']) ? ' selected ' : '' ?>><?= afficher($categorie->nom) ?></option>

<?php
	endforeach;
?>

		</select>
	</div>

	<div class="form-group">
		<label for="titre_photo">Titre :</label>
		<input type="text" id="titre_photo" name="titre_photo" class="form-control" value="<?= isset($_POST['titre_photo']) ? afficher($_POST['titre_photo']) : '' ?>" required />
	</div>

	<div class="form-group">
		<label for="description_photo">Description :</label>
		<textarea id="description_photo" name="description_photo" class="form-control" rows="5" required><?= isset($_POST['description_photo']) ? htmlentities($_POST['description_photo']) : '' ?></textarea>
	</div>

	<input type="submit" class="btn input" name="ajouter_photo" value="Ajouter" />
</form>


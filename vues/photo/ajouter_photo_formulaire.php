<hr>

<form method="post" enctype="multipart/form-data">
	<p class="small-caps">Ajouter une photo à cette catégorie</p>
	<?= isset($message_erreur) ? '<div class="alert alert-danger">'.$message_erreur.'</div>' : '' ?>
	<?= isset($message_succes) ? '<div class="alert alert-success">'.$message_succes.'</div>' : '' ?>

	<input type="hidden" name="MAX_FILE_SIZE" value="1048576">
	<div class="form-group">
		<input type="file" class="btn" name="image" required>
	</div>

	<div class="form-group">
		<label for="titre_photo">Titre :</label>
		<input type="text" id="titre_photo" name="titre_photo" class="form-control" value="<?= isset($_POST['titre_photo']) ? afficher($_POST['titre_photo']) : '' ?>" required>
	</div>

	<div class="form-group">
		<label for="description_photo">Description :</label>
		<textarea id="description_photo" name="description_photo" class="form-control" rows="5" required><?= isset($_POST['description_photo']) ? htmlentities($_POST['description_photo']) : '' ?></textarea>
	</div>

	<input type="submit" class="btn input" name="ajouter_photo" value="Ajouter">
</form>

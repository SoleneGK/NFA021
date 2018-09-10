<p id="chemin_page">Catégories de photos > Ajouter</p>

<?= isset($message) ? $message : '' ?>

<form method="post">
	<div class="form-group">
		<label for="nom_categorie">Nom de la catégorie :</label>
		<input type="text" id="nom_categorie" name="nom_categorie" class="form-control" required>
	</div>
	<div class="form-group">
		<label for="description_categorie">Description :</label>
		<textarea id="description_categorie" name="description_categorie" class="form-control" rows="10" required></textarea>
	</div>
	<input type="submit" class="btn input" value="Ajouter">
</form>

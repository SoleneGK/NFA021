<p id="chemin_page">Articles politique > Ajouter</p>

<form method="post">
	<div class="form-group">
		<label for="titre_article">Titre :</label>
		<input type="text" id="titre_article" name="titre_article" class="form-control" value="<?= isset($_POST['titre_article']) ? afficher($_POST['titre_article']) : '' ?>" required />
	</div>
	<div class="form-group">
		<label for="contenu_article">Contenu :</label>
		<textarea id="contenu_article" name="contenu_article" class="form-control" rows="15" required><?= isset($_POST['contenu_article']) ? afficher($_POST['contenu_article']) : '' ?></textarea>
	</div>
	<input type="submit" class="btn input" value="Envoyer" />
</form>
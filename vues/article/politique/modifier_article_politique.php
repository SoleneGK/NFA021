<p id="chemin_page">Articles politique > Modifier</p>

<form method="post">
	<div class="form-group">
		<label for="titre_article">Titre :</label>
		<input type="text" id="titre_article" name="titre_article" class="form-control" value="<?= afficher($article->titre) ?>" required />
	</div>
	<div class="form-group">
		<label for="contenu_article">Contenu :</label>
		<textarea id="contenu_article" name="contenu_article" class="form-control" rows="15" required><?= htmlentities($article->contenu) ?></textarea>
	</div>
	<input type="submit" class="btn input" value="Modifier" />
</form>


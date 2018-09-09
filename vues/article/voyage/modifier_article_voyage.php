<p id="chemin_page">Articles voyage > Modifier</p>

<form method="post">
	<div class="form-group">
		<label for="titre_article">Titre :</label>
		<input type="text" id="titre_article" name="titre_article" class="form-control" value="<?= afficher($article->titre) ?>" required />
	</div>
	<div class="form-group">
		<label for="titre_article">Pays :</label>
		<select name="id_pays" class="form-control">
			<option value="-1"<?= is_null($article->pays) ? ' selected ' : '' ?>>Aucun</option>

<?php
		foreach($pays as $p):
?>

		<option value="<?= $p->id ?>"<?= (!is_null($article->pays) && $p->id == $article->pays->id) ? ' selected ' : '' ?>><?= afficher($p->nom) ?></option>

<?php
		endforeach;
?>
		</select>
	</div>
	<div class="form-group">
		<label for="contenu_article">Contenu :</label>
		<textarea id="contenu_article" name="contenu_article" class="form-control" rows="15" required><?= htmlentities($article->contenu) ?></textarea>
	</div>
	<input type="submit" class="btn input" value="Modifier" />
</form>

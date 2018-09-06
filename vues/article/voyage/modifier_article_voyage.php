<form method="post">
	Titre : <input type="text" name="titre_article" value="<?= $article->titre ?>" required /><br />
	Pays :
	<select name="id_pays">
		<option value="-1"<?= is_null($article->pays) ? ' selected ' : '' ?>>Aucun</option>

<?php
		foreach($_pays as $p):
?>

		<option value="<?= $p->id ?>"<?= (!is_null($article->pays) && $p->id == $article->pays->id) ? ' selected ' : '' ?>><?= $p->nom ?></option>

<?php
		endforeach;
?>

	</select><br />
	<textarea name="contenu_article" required><?= $article->contenu ?></textarea><br />
	<input type="submit" value="Envoyer" />
</form>
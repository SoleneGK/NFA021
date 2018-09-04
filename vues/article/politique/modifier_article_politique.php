<form method="post">
	Titre : <input type="text" name="titre_article" value="<?= $article->titre ?>" required /><br />
	<textarea name="contenu_article" required><?= $article->contenu ?></textarea><br />
	<input type="submit" value="Envoyer" />
</form>
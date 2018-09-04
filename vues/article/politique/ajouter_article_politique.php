<form method="post">
	Titre : <input type="text" name="titre_article" value="<?= isset($_POST['titre_article']) ? $_POST['titre_article'] : '' ?>" required /><br />
	<textarea name="contenu_article" required><?= isset($_POST['contenu_article']) ? $_POST['contenu_article'] : '' ?></textarea><br />
	<input type="submit" value="Envoyer" />
</form>
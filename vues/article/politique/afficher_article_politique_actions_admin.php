<form method="post" action="admin.php?section=politique&id=<?= $article->id ?>&modifier">
	<input type="submit" value="Modifier" />
</form>

<form method="post" action="admin.php?section=politique">
	<input type="hidden" name="id_article" value="<?= $article->id ?>" />
	<input type="submit" name="supprimer_article" value="Supprimer" />
</form>
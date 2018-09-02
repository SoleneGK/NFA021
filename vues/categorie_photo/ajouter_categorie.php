<?= isset($message) ? $message : '' ?>

<form method="post">
	Nom : <input type="text" name="nom_categorie" required /><br />
	Description<br />
	<textarea name="description_categorie"></textarea><br />
	<input type="submit" value="Ajouter" />
</form>


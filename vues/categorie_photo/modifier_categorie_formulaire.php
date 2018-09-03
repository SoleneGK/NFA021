<form method="post">
	Changer le nom : <input type="text" name="nom_categorie" value="<?= $categorie->nom ?>" required /><br />
	Changer la description<br />
	<textarea name="description_categorie"><?= $categorie->description ?></textarea><br />
	<input type="submit" value="Modifier" />
</form>
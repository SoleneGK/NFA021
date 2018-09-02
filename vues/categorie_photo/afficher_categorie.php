<?= isset($message) ? $message : '' ?>

<p>
	Nom : <?= $categorie->nom ?><br />
	Description : <?= $categorie->description ?>

<form method="post">
	Changer le nom : <input type="text" name="nom" value="<?= $categorie->nom ?>" required /><br />
	Changer la description<br />
	<textarea name="description"><?= $categorie->description ?></textarea><br />
	<input type="submit" value="Modifier" />
</form>
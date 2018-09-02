<h1>Catégorie</h1>

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

<h2>Photos</h2>

<?php

foreach ($photos as $photo):

?>

<p>
	<strong><?= $photo->titre ?></strong><br />
	<em>Ajoutée le <?= date('d-m-Y', $photo->date_ajout) ?> par <?= $photo->utilisateur->pseudo ?></em><br />
	<?= $photo->description ?><br />
	<img src="public/images/photos/<?= $photo->nom_fichier ?>" />
</p>

<?php

endforeach;

?>

<form method="post" enctype="multipart/form-data">
	Fichier : <input type="file" name="image" required /><br />
	Titre : <input type="text" name="titre_photo" required /><br />
	Description<br />
	<textarea name="description_photo"></textarea><br />
	<input type="submit" value="Ajouter" name="ajouter_photo" />
</form>


<h1>Catégories photos</h1>

<?php

echo isset($message) ? $message : '';

foreach ($categories as $categorie):

?>

<form method="post">
	<a href="<?= $lien.$categorie->id ?>"><?= $categorie->nom ?></a>
	<input type="hidden" name="id_categorie" value="<?= $categorie->id ?>" />
	<input type="submit" name="supprimer_categorie" value="Supprimer" />
</form>

<?php

endforeach;

?>

<p><a href="admin.php?section=categories&ajouter">Ajouter une catégorie</a></p>
<h1>Catégories photos</h1>

<?php

echo isset($message) ? $message : '';

foreach ($categories as $categorie):

?>

<form method="post">
	<a href="admin.php?section=categories&id=<?= $categorie->id ?>"><?= $categorie->nom ?></a>
	<input type="hidden" name="id" value="<?= $categorie->id ?>" />
	<input type="submit" name="supprimer" value="Supprimer" />
</form>

<?php

endforeach;

?>

<p><a href="admin.php?section=categories&ajouter">Ajouter une catégorie</a></p>
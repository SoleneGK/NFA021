<h1>Pays</h1>


<?php

echo isset($message) ? $message : '';

foreach ($pays as $item):
?>

<form method="post">
	<input type="hidden" name="id_pays" value="<?= $item->id ?>" />
	<input type="text" name="nom_pays" value="<?= $item->nom ?>" required />
	<input type="submit" name="modifier" value="Modifier" />
	<input type="submit" name="supprimer" value="Supprimer" />
</form>

<?php

endforeach;

?>

<form method="post">
	<input type="text" name="nom_pays" required />
	<input type="submit" name="ajouter" value="Ajouter" />
</form>


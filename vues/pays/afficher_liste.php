<p id="chemin_page">Pays > Liste</p>

<?php

if (isset($message_succes))
	echo'<div class="alert alert-success">'.$message_succes.'</div>';

foreach ($pays as $item):
?>

<form method="post" class="liste_pays mb-1">
	<input type="hidden" name="id_pays" value="<?= $item->id ?>" />

	<div class="input-group mb-1">
		<div class="input-group-prepend">
			<span class="input-group-text" id="nom_pays-addon1" ?>Nom</span>
		</div>
		<input type="text" class="form-control" name="nom_pays" value="<?= afficher($item->nom) ?>" required />
	</div>

	<input type="submit" class="btn input" name="modifier" value="Modifier" />
</form>

<form method="post" class="liste_pays">
	<input type="hidden" name="id_pays" value="<?= $item->id ?>" />
	<input type="submit" class="btn supprimer" name="supprimer" value="Supprimer" />
</form>

<?php

endforeach;

?>

<form method="post">
		<div class="input-group mb-1">
		<div class="input-group-prepend">
			<span class="input-group-text" id="nom_pays-addon1" ?>Nom</span>
		</div>
		<input type="text" class="form-control" name="nom_pays" required />
	</div>
	<input type="submit" class="btn input" name="ajouter" value="Ajouter un pays" />
</form>


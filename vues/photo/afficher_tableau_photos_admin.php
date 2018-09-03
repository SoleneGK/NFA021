<h2>Photos</h2>

<?php

foreach ($photos as $photo):

?>

<form method="post">
	<strong><a href="admin.php?section=photos&id=<?= $photo->id ?>"><?= $photo->titre ?></a></strong><br />
	<img src="public/images/photos/<?= $photo->nom_fichier ?>" /><br />
	<input type="hidden" name="id_photo" value="<?= $photo->id ?>" />
	<input type="hidden" name="nom_fichier" value="<?= $photo->nom_fichier ?>" />
	<input type="submit" name="supprimer_photo" value="Supprimer" />
</form>

<?php

endforeach;

?>
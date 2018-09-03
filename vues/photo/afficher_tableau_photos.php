<h2>Photos</h2>

<?php

foreach ($photos as $photo):

?>

<p>
	<strong><a href="admin.php?section=photos&id=<?= $photo->id ?>"><?= $photo->titre ?></a></strong><br />
	<img src="public/images/photos/<?= $photo->nom_fichier ?>" />
</p>

<?php

endforeach;

?>
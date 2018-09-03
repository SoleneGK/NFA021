<h1>Catégories photos</h1>

<?php

echo isset($message) ? $message : '';

foreach ($categories as $categorie):

?>


	<p><a href="<?= $lien.$categorie->id ?>"><?= $categorie->nom ?></a></p>

<?php

endforeach;

?>


<?php

foreach ($commentaires as $commentaire):

?>

<p>
	<strong><?= $commentaire->utilisateur->pseudo ?></strong><br />
	<?= $commentaire->contenu ?>
</p>

<?php

endforeach;

?>
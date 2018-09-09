<div id="commentaires">

<?php
if (!$commentaires):
?>

	<p>Aucun commentaire</p>

<?php
else:
?>

	<p class="titre_commentaires">Commentaires</p>

<?php
	foreach ($commentaires as $commentaire):

?>

	<p class="p_commentaire">
		Le <?= date('d-m-y', $commentaire->date_ajout) ?> à <?= date('H:i', $commentaire->date_ajout) ?> par <span class="font-weight-bold"><?= afficher($commentaire->utilisateur->pseudo) ?></span><br />
		<?= afficher($commentaire->contenu) ?>
	</p>

<?php

	endforeach;

endif;


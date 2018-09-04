<?php

foreach ($commentaires as $commentaire):

?>

<form method="post">
	Pseudo : <input type="text" name="pseudo_commentaire" value="<?= $commentaire->utilisateur->pseudo ?>" <?= !empty($commentaire->utilisateur->id) ? 'disabled' : '' ?> required /><br />
	Mail : <input type="text" name="mail_commentaire" value="<?= $commentaire->utilisateur->mail ?>" <?= !empty($commentaire->utilisateur->id) ? 'disabled' : '' ?> /><br />
	<textarea name="contenu_commentaire" required><?= $commentaire->contenu ?></textarea><br />
	<input type="hidden" name="id_utilisateur" value="<?= $commentaire->utilisateur->id ?>" />
	<input type="hidden" name="id_commentaire" value="<?= $commentaire->id ?>" />
	<input type="submit" name="modifier_commentaire" value="Modifier" />
	<input type="submit" name="supprimer_commentaire" value="Supprimer" />
</form>

<?php

endforeach;

?>
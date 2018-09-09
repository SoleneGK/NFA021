<div id="banniere">Empreinte</div>

<main>

<?php
	if (isset($envoi_mail_reussi)):
?>

	<p>Le mail a bien été envoyé.</p>

<?php
	else:
?>

<?= isset($message_erreur) ? '<div class="alert alert-danger" role="alert">'.$message_erreur.'</div>' : '' ?>
	<form method="post" action="admin.php?mot_de_passe_perdu">
		<p>Entrez le mail associé à votre compte. Un mail vous sera envoyé avec un lien permettant de modifier votre mot de passe.</p>
		<input type="text" name="mail_mdp_perdu" class="form-control" required /><br />
		<input type="submit" class="btn input" value="Envoyer" />
	</form>

<?php
	endif;
?>


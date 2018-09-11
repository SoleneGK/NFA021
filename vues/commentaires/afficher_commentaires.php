<hr>

<div class="commentaires">

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

		<div class="div_commentaire mb-3">

<?php
		if ($admin && $_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[$id_section] <= Utilisateur::MODERATEUR):
?>

		<form method="post" class="modifier_commentaire_form">

<?php
		endif;
?>

			<p class="p_commentaire">
				Le <?= date('d-m-y', $commentaire->date_ajout) ?> à <?= date('H:i', $commentaire->date_ajout) ?> par <span class="font-weight-bold pseudo_commentaire"><?= afficher($commentaire->utilisateur->pseudo) ?></span><br>
				<span class="contenu_commentaire"><?= afficher($commentaire->contenu) ?></span>
			</p>

<?php
		if ($admin && $_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[$id_section] <= Utilisateur::MODERATEUR):
?>

			<button class="afficher_modification btn input">Modifier</button>

			<div class="modifier_commentaire_elements">
				<div class="form-group">
					<label>Pseudo :</label>
					<input type="text" name="pseudo_commentaire" class="form-control pseudo_commentaire" value="<?= afficher($commentaire->utilisateur->pseudo) ?>" required <?= !empty($commentaire->utilisateur->id) ? 'disabled' : '' ?>>
				</div>

				<div class="form-group">
					<label>Contenu :</label>
					<textarea name="contenu_commentaire" class="form-control contenu_commentaire" required><?= htmlentities($commentaire->contenu) ?></textarea>
				</div>

				<input type="hidden" name="id_utilisateur" value="<?= $commentaire->utilisateur->id ?>">
				<input type="hidden" name="id_section" value="<?= $id_section ?>">
				<input type="hidden" name="id_commentaire" value="<?= $commentaire->id ?>">
				<input type="submit" class="btn input" name="modifier_commentaire" value="Enregistrer">
			</div>
		</form>

		<form method="post" class="supprimer_commentaire_form mt-1">
			<input type="hidden" name="id_commentaire" value="<?= $commentaire->id ?>">
			<input type="hidden" name="id_section" value="<?= $id_section ?>">
			<input type="submit" class="btn supprimer" name="supprimer_commentaire" value="Supprimer">
		</form>

<?php
		endif;
?>
	
	</div>

<?php
	endforeach;
?>

</div>

<?php
endif;


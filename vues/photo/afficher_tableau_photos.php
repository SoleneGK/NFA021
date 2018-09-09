<hr />

<p class="small-caps">Photos</p>

<?php

$compteur = 0;
foreach ($photos as $photo):
	if ($compteur % 2 == 0)
		echo '<div class="row">';
?>

<div class="col-sm-6 mb-3">
	<article class="card">
		<a href="admin.php?section=photos&id=<?= $photo->id ?>"><img src="public/images/photos/<?= $photo->nom_fichier ?>" class="card-img-top" /></a>
		<div class="card-body">
			<h5 class="card-title mb-0"><a href="admin.php?section=photos&id=<?= $photo->id ?>"><?= afficher($photo->titre) ?></a></h5>
			<p class="font-italic card-text">Ajoutée le <?= date('d-m-Y', $photo->date_ajout) ?> par <?= afficher($photo->utilisateur->pseudo) ?></p>

<?php
	if ($admin):
		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::MODERATEUR):
?>

			<form method="post">
				<input type="hidden" name="id_photo" value="<?= $photo->id ?>" />
				<input type="hidden" name="nom_fichier" value="<?= $photo->nom_fichier ?>" />
				<input type="submit" class="btn supprimer" name="supprimer_photo" value="Supprimer" />
			</form>

<?php
		endif;
	endif;
?>

		</div>
	</article>
</div>

<?php

	if ($compteur % 2 == 1)
		echo '</div>';

	$compteur++;

endforeach;

if ($compteur % 2 == 1)
	echo '</div>';


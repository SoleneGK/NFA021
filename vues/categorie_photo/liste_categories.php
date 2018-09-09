<p id="chemin_page">Catégories de photos > Liste</p>

<?php
if (!$categories):
?>

<p>Aucune catégorie trouvée.</p>

<?php
else:
	$premier_element = true;
	// Afficher la liste des categories
	foreach($categories as $categorie):
		if ($premier_element)
			$premier_element = false;
		else
			echo '<hr />';
?>

<article>
	<h3><a href="<?= $admin ? 'admin' : 'index' ?>.php?section=<?= ($_GET['section'] == 'categories') ? 'categories&id=' : 'photos&categorie=' ?><?= $categorie->id ?>"><?= afficher($categorie->nom) ?></a></h3>
	<p><?= afficher($categorie->description) ?></p>

<?php
			if ($admin && $_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN):
?>

	<div class="mb-3">
		<form method="post" class="d-inline-block" action="admin.php?section=categories&id=<?= $categorie->id ?>">
			<input type="submit" class="btn input" value="Modifier" />
		</form>
		<form method="post" class="d-inline-block" action="admin.php?section=categories">
			<input type="hidden" name="id_categorie" value="<?= $categorie->id ?>" />
			<input type="submit" class="btn supprimer" name="supprimer_categorie" value="Supprimer" />
		</form>
	</div>

<?php
			endif;
?>

</article>

<?php
	endforeach;

	if ($admin && $_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN):
?>

<hr />
<form method="post" action="admin.php?section=categories&ajouter">
	<input type="submit" class="btn input" value="Ajouter une catégorie" />
</form>

<?php
	endif;
endif;


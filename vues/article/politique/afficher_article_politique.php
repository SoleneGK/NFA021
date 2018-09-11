<p id="chemin_page">Articles politique > Afficher</p>

<?php
if (!$article):
?>
<p>
	Aucun article trouvé.<br>
	<a href="<?= $admin ? 'admin' : 'index' ?>.php?section=politique">Retourner à la liste des articles</a>
</p>

<?php
else:
?>

<!-- Afficher le contenu de l'article -->
<h2><?= afficher($article->titre) ?></h2>
<p class="font-italic">Ajouté par <a href="index.php?section=utilisateur&id=<?= $article->utilisateur->id ?>"><?= afficher($article->utilisateur->pseudo) ?></a> le <?= date('d-m-Y', $article->date_publication) ?></p>

<?php
	
	// Boutons de modification et de suppression
	if ($admin):
		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::MODERATEUR || ($_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::CONTRIBUTEUR && $_SESSION['utilisateur']->id == $article->utilisateur->id)):
?>

<div class="mb-3">
	<form method="post" class="d-inline-block" action="admin.php?section=politique&id=<?= $article->id ?>&modifier">
		<input type="submit" class="btn input" value="Modifier">
	</form>

<?php
			if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::MODERATEUR):
?>

	<form method="post" class="d-inline-block" action="admin.php?section=politique">
		<input type="hidden" name="id_article" value="<?= $article->id ?>">
		<input type="submit" class="btn supprimer" name="supprimer_article" value="Supprimer">
	</form>

<?php
			endif;
?>

</div>

<?php
		endif;
	endif;
?>

<p><?= afficher($article->contenu) ?></p>


<!-- Afficher les liens vers les autres articles  -->
<nav>
	<ul class="pagination justify-content-between">
		<li class="page-item">
			<?= empty($article_precedent) ? '' : '<button class="btn"><a href="'.($admin ? 'admin' : 'index').'.php?section=politique&id='.$article_precedent->id.'">Article précédent</a></button>' ?>
		</li>
		<li class="page-item">
			<?= empty($article_suivant) ? '' : '<button class="btn"><a href="'.($admin ? 'admin' : 'index').'.php?section=politique&id='.$article_suivant->id.'">Article suivant</a></button>' ?>
		</li>
	</ul>
</nav>

<?php
endif;

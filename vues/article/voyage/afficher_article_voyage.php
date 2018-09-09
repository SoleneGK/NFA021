<p id="chemin_page">Articles voyage > Afficher</p>

<?php
if (!$utilisateur):
?>

<p>Aucun utilisateur trouvé.</p>

<?php
if (!$article):
?>
<p>
	Aucun article trouvé.<br />
	<a href="<?= $admin ? 'admin' : 'index' ?>.php?section=voyage">Retourner à la liste des articles</a>
</p>

<?php
else:
?>

<!-- Afficher le contenu de l'article -->
<h2><?= afficher($article->titre) ?></h2>
<p class="font-italic">
	Ajouté par <a href="<?= $admin ? 'admin' : 'index' ?>.php?section=utilisateur&id=<?= $article->utilisateur->id ?>"><?= afficher($article->utilisateur->pseudo) ?></a> le <?= date('d-m-Y', $article->date_publication) ?>
	<?= !empty($article->pays) ? '<br />Pays : <a href="'.($admin ? 'admin' : 'index').'.php?section=voyage&pays='.$article->pays->id.'">'.afficher($article->pays->nom).'</a>' : '' ?>
</p>

<?php
	if ($admin):
		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::VOYAGE] <= Utilisateur::CONTRIBUTEUR):
?>

<div class="mb-3">
	<form method="post" class="d-inline-block" action="admin.php?section=voyage&id=<?= $article->id ?>&modifier">
		<input type="submit" class="btn input" value="Modifier" />
</form>

<?php
		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::VOYAGE] <= Utilisateur::MODERATEUR):
?>

	<form method="post" class="d-inline-block" action="admin.php?section=voyage">
		<input type="hidden" name="id_article" value="<?= $article->id ?>" />
		<input type="submit" class="btn supprimer" name="supprimer_article" value="Supprimer" />
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
			<?= empty($article_precedent) ? '' : '<button class="btn"><a href="'.($admin ? 'admin' : 'index').'.php?section=voyage&id='.$article_precedent->id.'">Article précédent</a></button>' ?>
		</li>
		<li class="page-item">
			<?= empty($article_suivant) ? '' : '<button class="btn"><a href="'.($admin ? 'admin' : 'index').'.php?section=voyage&id='.$article_suivant->id.'">Article suivant</a></button>' ?>
		</li>
	</ul>
</nav>

<hr />

<?php
endif;
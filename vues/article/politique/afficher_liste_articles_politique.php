<p id="chemin_page">Articles politique > Liste</p>

<?php
if (!$articles):
?>

<p>Aucun article trouvé dans cette catégorie.</p>

<?php
else:
?>

<!-- Menu de navigation entre les pages -->
<nav>
	<ul class="pagination justify-content-center">
		<li class="page-item">
			<button class="btn pagination-bouton-gauche"><a <?= ($page > 1) ? 'href="'.($admin ? 'admin' : 'index').'.php?section=politique&page=1"' : 'disabled' ?>><<</a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu"><a <?= is_null($numeros_pages['page_precedente']) ? 'disabled' : 'href="'.($admin ? 'admin' : 'index').'.php?section=politique&page='.$numeros_pages['page_precedente'].'"' ?>><</a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu">Page <?= $page ?></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu"><a <?= is_null($numeros_pages['page_suivante']) ? 'disabled' : 'href="'.($admin ? 'admin' : 'index').'.php?section=politique&page='.$numeros_pages['page_suivante'].'"' ?>>></a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-droite"><a <?= ($numeros_pages['derniere_page'] > $page) ? 'href="'.($admin ? 'admin' : 'index').'.php?section=politique&page='.$numeros_pages['derniere_page'].'"' : 'disabled' ?>>>></a></button>
		</li>
	</ul>
</nav>

<?php
	$premier_element = true;
	// Afficher la liste des articles
	foreach($articles as $article):
		if ($premier_element)
			$premier_element = false;
		else
			echo '<hr>';
?>

<article>
	<h3><a href="<?= $admin ? 'admin' : 'index' ?>.php?section=politique&id=<?= $article->id ?>"><?= afficher($article->titre) ?></a></h3>
	<p class="font-italic">Ajouté par <a href="index.php?section=utilisateur&id=<?= $article->utilisateur->id ?>"><?= afficher($article->utilisateur->pseudo) ?></a> le <?= date('d-m-Y', $article->date_publication) ?></p>
	<p>
		<?= substr(afficher($article->contenu), 0, 500) ?>…<br>
		<a href="<?= $admin ? 'admin' : 'index' ?>.php?section=politique&id=<?= $article->id ?>">Lire la suite</a>
	</p>

<?php
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

</article>

<?php
	endforeach;
?>

<!-- Menu de navigation entre les pages -->
<nav>
	<ul class="pagination justify-content-center">
		<li class="page-item">
			<button class="btn pagination-bouton-gauche"><a <?= ($page > 1) ? 'href="'.($admin ? 'admin' : 'index').'.php?section=politique&page=1"' : 'disabled' ?>><<</a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu"><a <?= is_null($numeros_pages['page_precedente']) ? 'disabled' : 'href="'.($admin ? 'admin' : 'index').'.php?section=politique&page='.$numeros_pages['page_precedente'].'"' ?>><</a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu">Page <?= $page ?></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu"><a <?= is_null($numeros_pages['page_suivante']) ? 'disabled' : 'href="'.($admin ? 'admin' : 'index').'.php?section=politique&page='.$numeros_pages['page_suivante'].'"' ?>>></a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-droite"><a <?= ($numeros_pages['derniere_page'] > $page) ? 'href="'.($admin ? 'admin' : 'index').'.php?section=politique&page='.$numeros_pages['derniere_page'].'"' : 'disabled' ?>>>></a></button>
		</li>
	</ul>
</nav>

<?php
endif;

if ($admin & $_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::CONTRIBUTEUR):
?>

<hr>
<form method="post" action="admin.php?section=politique&ajouter">
	<input type="submit" class="btn input" value="Ajouter un article">
</form>

<?php
endif;


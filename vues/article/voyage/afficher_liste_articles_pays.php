<p id="chemin_page">Articles voyage > <?= afficher($pays_selectionne->nom) ?></p>

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
			<button class="btn pagination-bouton-gauche"><a <?= ($page > 1) ? 'href="'.($admin ? 'admin' : 'index').'.php?section=voyage&page=1"' : 'disabled' ?>><<</a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu"><a <?= is_null($numeros_pages['page_precedente']) ? 'disabled' : 'href="'.($admin ? 'admin' : 'index').'.php?section=voyage&page='.$numeros_pages['page_precedente'].'"' ?>><</a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu">Page <?= $page ?></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu"><a <?= is_null($numeros_pages['page_suivante']) ? 'disabled' : 'href="'.($admin ? 'admin' : 'index').'.php?section=voyage&page='.$numeros_pages['page_suivante'].'"' ?>>></a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-droite"><a <?= ($numeros_pages['derniere_page'] > $page) ? 'href="'.($admin ? 'admin' : 'index').'.php?section=voyage&page='.$numeros_pages['derniere_page'].'"' : 'disabled' ?>>>></a></button>
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
			echo '<hr />';
?>

<article>
	<h3><a href="<?= $admin ? 'admin' : 'index' ?>.php?section=voyage&id=<?= $article->id ?>"><?= afficher($article->titre) ?></a></h3>
	<p class="font-italic">Ajouté par <a href="<?= $admin ? 'admin' : 'index' ?>.php?section=utilisateur&id=<?= $article->utilisateur->id ?>"><?= afficher($article->utilisateur->pseudo) ?></a> le <?= date('d-m-Y', $article->date_publication) ?></p>
	<p>
		<?= substr(afficher($article->contenu), 0, 500) ?>…<br />
		<a href="<?= $admin ? 'admin' : 'index' ?>.php?section=voyage&id=<?= $article->id ?>">Lire la suite</a>
	</p>
</article>

<?php
	endforeach;
?>

<!-- Menu de navigation entre les pages -->
<nav>
	<ul class="pagination justify-content-center">
		<li class="page-item">
			<button class="btn pagination-bouton-gauche"><a <?= ($page > 1) ? 'href="'.($admin ? 'admin' : 'index').'.php?section=voyage&page=1"' : 'disabled' ?>><<</a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu"><a <?= is_null($numeros_pages['page_precedente']) ? 'disabled' : 'href="'.($admin ? 'admin' : 'index').'.php?section=voyage&page='.$numeros_pages['page_precedente'].'"' ?>><</a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu">Page <?= $page ?></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-milieu"><a <?= is_null($numeros_pages['page_suivante']) ? 'disabled' : 'href="'.($admin ? 'admin' : 'index').'.php?section=voyage&page='.$numeros_pages['page_suivante'].'"' ?>>></a></button>
		</li>
		<li class="page-item">
			<button class="btn pagination-bouton-droite"><a <?= ($numeros_pages['derniere_page'] > $page) ? 'href="'.($admin ? 'admin' : 'index').'.php?section=voyage&page='.$numeros_pages['derniere_page'].'"' : 'disabled' ?>>>></a></button>
		</li>
	</ul>
</nav>

<?php
endif;


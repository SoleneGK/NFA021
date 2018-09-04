<p><strong><?= $article->titre ?></strong></p>
<p><em>Ajouté le <?= date('d-m-Y', $article->date_publication) ?> par <?= $article->utilisateur->pseudo ?></em></p>
<p><?= $article->contenu ?></p>
<hr />
<p>Article précédent : <?= empty($article_precedent) ? 'aucun' : $article_precedent->titre ?></p>
<p>Article suivant : <?= empty($article_suivant) ? 'aucun' : $article_suivant->titre ?></p>

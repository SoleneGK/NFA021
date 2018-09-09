<p id="chemin_page">Catégories de photos > Afficher</p>

<?= isset($message) ? $message : '' ?>

<h2><?= afficher($categorie->nom) ?></h2>
<p><?= afficher($categorie->description) ?></p>


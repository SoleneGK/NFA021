<p id="chemin_page">Catégories de photos > Afficher</p>

<?= isset($message_erreur) ? '<div class="alert alert-danger">'.$message_erreur.'</div>' : '' ?>
<?= isset($message_succes) ? '<div class="alert alert-success">'.$message_succes.'</div>' : '' ?>

<h2><?= afficher($categorie->nom) ?></h2>
<p><?= afficher($categorie->description) ?></p>


<h1>Photo</h1>

<?= isset($message) ? $message : '' ?>

<p><img src="public/images/photos/<?= $photo->nom_fichier ?>" /></p>
<p>Titre : <?= $photo->titre ?></p>
<p>Catégorie : <?= $photo->categorie->nom ?></p>
<p>Ajoutée le <?= date('d-m-Y', $photo->date_ajout) ?> par <?= $photo->utilisateur->pseudo ?></p>
<p><em><?= $photo->description ?></em></p>


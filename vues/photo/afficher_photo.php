<p id="chemin_page">Photo > Afficher</p>

<?php
if (!$photo):
?>
<p>
	Aucun article trouvé.<br>
	<a href="<?= $admin ? 'admin' : 'index' ?>.php?section=politique">Retourner à la liste des articles</a>
</p>

<?php
else:
?>

<!-- Afficher la photo -->
<p class="photo">
	<a href="public/images/photos/original/<?= $photo->nom_fichier ?>">
		<picture>
			<source srcset="public/images/photos/968/<?= $photo->nom_fichier ?>" media="(min-width:576px)">
			<img src="public/images/photos/526/<?= $photo->nom_fichier ?>">
		</picture>
	</a>
</p>
<h3><?= afficher($photo->titre) ?></h3>
<p class="mb-0">Catégorie : <a href="<?= $admin ? 'admin' : 'index' ?>.php?section=photos&categorie=<?= $photo->categorie->id ?>"><?= afficher($photo->categorie->nom) ?></a></p>
<p class="font-italic">Ajoutée le <?= date('d-m-Y', $photo->date_ajout) ?> par <?= afficher($photo->utilisateur->pseudo) ?></p>
<p><?= afficher($photo->description) ?></p>

<?php
	if ($admin):
		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::CONTRIBUTEUR):
?>

<hr>

<!-- Menu de modification de photo -->
<p class="small-caps">Modifier la photo</p>

<form method="post">
	<?= isset($message_erreur) ? '<div class="alert alert-danger">'.$message_erreur.'</div>' : '' ?>
	<div class="form-group">
		<label for="titre_photo">Titre :</label>
		<input type="text" id="titre_photo" name="titre_photo" class="form-control" value="<?= afficher($photo->titre) ?>" required>
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<label class="input-group-text" for="id_categorie">Catégorie</label>
		</div>
		<select class="custom-select" id="id_categorie" name="id_categorie" required>

<?php
			foreach($categories as $categorie):
?>

			<option value="<?= $categorie->id ?>" <?= $categorie->id == $photo->categorie->id ? 'selected' : '' ?>><?= afficher($categorie->nom) ?></option>

<?php
			endforeach;
?>

		</select>
	</div>

	<div class="input-group mb-3">
		<div class="input-group-prepend">
			<label class="input-group-text" for="id_utilisateur">Ajoutée par</label>
		</div>
		<select class="custom-select" id="id_utilisateur" name="id_utilisateur" required>

<?php
			foreach($utilisateurs as $utilisateur):
?>

			<option value="<?= $utilisateur->id ?>" <?= $utilisateur->id == $photo->utilisateur->id ? 'selected' : '' ?>><?= afficher($utilisateur->pseudo) ?></option>

<?php
			endforeach;
?>

		</select>
	</div>

	<div class="form-group">
		<label for="description_photo">Description :</label>
		<textarea id="description_photo" name="description_photo" class="form-control" rows="5" required><?= htmlentities($photo->description) ?></textarea>
	</div>

	<input type="submit" class="btn input" value="Modifier">
</form>

<?php
			if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::MODERATEUR):
?>

<hr>

<!-- Menu de suppression de photo -->
<p class="small-caps">Supprimer la photo</p>

<form method="post">
	<input type="hidden" name="id_photo" value="<?= $photo->id ?>">
	<input type="hidden" name="nom_fichier" value="<?= $photo->nom_fichier ?>">
	<input type="submit" class="btn supprimer" name="supprimer_photo" value="Supprimer">
</form>

<?php
			endif;
		endif;
	endif;
endif;

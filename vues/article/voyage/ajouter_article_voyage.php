<p id="chemin_page">Articles voyage > Ajouter</p>

<form method="post">
	<div class="form-group">
		<label for="titre_article">Titre :</label>
		<input type="text" id="titre_article" name="titre_article" class="form-control" value="<?= isset($_POST['titre_article']) ? afficher($_POST['titre_article']) : '' ?>" required>
	</div>

	<div class="input-group mb-3">
	<div class="input-group-prepend">
		<label class="input-group-text" for="id_pays">Pays</label>
		</div>
		<select class="custom-select" id="id_pays" name="id_pays">
			<option value="-1">Aucun</option>

<?php
		foreach($pays as $p):
?>

			<option value="<?= $p->id ?>"><?= $p->nom ?></option>

<?php
		endforeach;
?>

		</select>
	</div>

	<div class="form-group">
		<label for="contenu_article">Contenu :</label>
		<textarea id="contenu_article" name="contenu_article" class="form-control" rows="15" required><?= isset($_POST['contenu_article']) ? htmlentities($_POST['contenu_article']) : '' ?></textarea>
	</div>
	<input type="submit" class="btn input" value="Envoyer">
</form>


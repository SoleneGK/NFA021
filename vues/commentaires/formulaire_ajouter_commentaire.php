<hr />

<?php
if (isset($_SESSION['utilisateur'])):
?>
<div class="commentaires">
	<form method="post" id="form_ajout_commentaire small-caps">
		<div class="form-group">
			<label for="pseudo_commentaire">Pseudo :</label>
			<input type="text" id="pseudo_commentaire" name="pseudo" class="form-control" value="<?= afficher($_SESSION['utilisateur']->pseudo) ?>" required disabled />
		</div>
		<div class="form-group">
			<label for="contenu_commentaire">Contenu :</label>
			<textarea id="contenu_commentaire" name="contenu" class="form-control" rows="3" required></textarea>
		</div>
		<input type="submit" class="btn input" value="Ajouter" name="ajouter_commentaire" />
	</form>
</div>

<?php
else:
?>

	<form method="post" id="form_ajout_commentaire">
		<p class="small-caps">Ajouter un commentaire</p>
		<div class="form-group">
			<label for="pseudo_commentaire">Pseudo :</label>
			<input type="text" id="pseudo_commentaire" name="pseudo" class="form-control" required />
		</div>
		<div class="form-group">
			<label for="mail_commentaire">Mail (facultatif) :</label>
			<input type="text" id="mail_commentaire" name="mail" class="form-control" />
		</div>
		<div class="form-group">
			<label for="contenu_commentaire">Contenu :</label>
			<textarea id="contenu_commentaire" name="contenu" class="form-control" rows="3" required></textarea>
		</div>
		<input type="submit" class="btn input" value="Ajouter" name="ajouter_commentaire" />
	</form>
</div>

<?php
endif;

<form method="post" action="admin.php?section=profil&modifier_mdp">
	<?= isset($message) ? '<p>'.$message.'</p>' : '' ?>
	Ancien mot de passe : <input type="password" name="ancien_mot_de_passe" /><br />
	Nouveau mot de passe : <input type="password" name="nouveau_mot_de_passe_1" /><br />
	Recopier le nouveau mot de passe : <input type="password" name="nouveau_mot_de_passe_2" /><br />
	<input type="submit" value="Modifier" />
</form>
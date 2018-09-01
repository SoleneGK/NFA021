<form method="post" action="admin.php?section=utilisateur&ajouter">
	<?= isset($message) ? $message : '' ?>
	Pseudo : <input type="text" name="pseudo" value="<?= (isset($_POST['pseudo']) && !isset($id_utilisateur_cree)) ? $_POST['pseudo'] : '' ?>" required /><br />
	Mail : <input type="text" name="mail_1" value="<?= (isset($_POST['mail_1']) && !isset($id_utilisateur_cree)) ? $_POST['mail_1'] : '' ?>" required /><br />
	Réécrire le mail : <input type="text" name="mail_2" value="<?= (isset($_POST['mail_2']) && !isset($id_utilisateur_cree)) ? $_POST['mail_2'] : '' ?>" required /><br />
	<input type="submit" value="Créer le compte" />
</form>
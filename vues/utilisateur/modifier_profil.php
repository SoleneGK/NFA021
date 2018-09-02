<form method="post" action="admin.php?section=profil&modifier">
	<?= isset($message) ? $message : '' ?>
	Pseudo : <input type="text" name="pseudo" value="<?= $_SESSION['utilisateur']->pseudo ?>" required /><br />
	Mail : <input type="text" name="mail" value="<?= $_SESSION['utilisateur']->mail ?>" required /><br />
	<input type="submit" value="Modifier" />
</form>
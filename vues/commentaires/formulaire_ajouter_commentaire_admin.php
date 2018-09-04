<form method="post">
	Pseudo : <input type="text" name="pseudo" value="<?=$_SESSION['utilisateur']->pseudo ?>" required disabled /><br />
	Mail (facultatif) : <input type="text" name="mail" disabled /><br />
	<textarea name="contenu"></textarea><br />
	<input type="submit" value="Ajouter" name="ajouter_commentaire" required />
</form>
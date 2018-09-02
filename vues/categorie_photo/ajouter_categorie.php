<?= isset($message) ? $message : '' ?>

<form method="post">
	Nom : <input type="text" name="nom" required /><br />
	Description<br />
	<textarea name="description"></textarea><br />
	<input type="submit" value="Ajouter" />
</form>


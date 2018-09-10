<p id="chemin_page">Utilisateurs > Liste</p>

<?php
foreach($utilisateurs as $u):
?>

<p><a href="admin.php?section=utilisateur&id=<?= $u->id ?>"><?= afficher($u->pseudo) ?></a></p>

<?php
endforeach;
?>

<form method="post" action="admin.php?section=utilisateur&ajouter">
	<input type="submit" class="btn input" value="Ajouter un utilisateur">
</form>

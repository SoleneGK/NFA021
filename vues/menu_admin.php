<div id="banniere">Empreinte</div>

<ul id="nav_ordinateur" class="nav">
	<li class="nav-item btn-group">
		<a class="nav-link" href="admin.php?section=photos">Photos</a>
		<button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></button>
		<div class="dropdown-menu">

<?php
		foreach ($categories as $c):
?>
			<a class="dropdown-item" href="admin.php?section=photos&categorie=<?= $c->id ?>"><?= afficher($c->nom) ?></a>
<?php
		endforeach;


		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::CONTRIBUTEUR):
?>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="admin.php?section=photos&ajouter">Ajouter une photo</a>
<?php
		endif;

		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN):
?>
			<a class="dropdown-item" href="admin.php?section=categories&ajouter">Ajouter une catégorie</a>
<?php
		endif;
?>

		</div>
	</li>
	<li class="nav-item btn-group">
		<a class="nav-link" href="admin.php?section=politique">Articles politique</a>
<?php
	if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::CONTRIBUTEUR):
?>
		<button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></button>
		<div class="dropdown-menu">
			<a class="nav-link" href="admin.php?section=politique&ajouter">Ajouter un article</a>
		</div>
<?php
	endif;
?>
	</li>
	<li class="nav-item btn-group">
		<a class="nav-link" href="admin.php?section=voyage">Articles voyage</a>
		<button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></button>
		<div class="dropdown-menu">

<?php
		foreach ($pays as $p):
?>
			<a class="dropdown-item" href="admin.php?section=voyage&pays=<?= $p->id ?>"><?= afficher($p->nom) ?></a>
<?php
		endforeach;

		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::VOYAGE] <= Utilisateur::CONTRIBUTEUR):
?>
			<div class="dropdown-divider"></div>
			<a class="dropdown-item" href="admin.php?section=voyage&ajouter">Ajouter un article</a>
<?php
		endif;
?>
		</div>
	</li>
<?php
if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN):
?>
	<li class="nav-item btn-group">
		<a class="nav-link" href="admin.php?section=pays">Pays</a>
	</li>
	<li class="nav-item btn-group">
		<a class="nav-link" href="admin.php?section=utilisateur">Utilisateurs</a>
		<button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></button>
		<div class="dropdown-menu">
			<a class="dropdown-item" href="admin.php?section=utilisateurs&ajouter">Ajouter un utilisateur</a>
		</div>
	</li>
<?php
endif;
?>
	<li class="nav-item btn-group">
		<a class="nav-link" href="admin.php?section=profil">Profil</a>
	</li>
</ul>




<nav id="nav_smartphone" class="navbar navbar-light fixed-top">
	<a class="navbar-brand" href="admin.php">Empreinte</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
		<ul class="navbar-nav mr-auto">
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown">Photos</a>
				<div class="dropdown-menu">
<?php
				foreach ($categories as $c):
?>
					<a class="dropdown-item" href="admin.php?section=photos&categorie=<?= $c->id ?>"><?= afficher($c->nom) ?></a>
<?php
				endforeach;
?>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="admin.php?section=photos">Voir toutes les catégories</a>
<?php
				if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::CONTRIBUTEUR):
?>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="admin.php?section=photos&ajouter">Ajouter une photo</a>
<?php
				endif;

				if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN):
?>
					<a class="dropdown-item" href="admin.php?section=categories&ajouter">Ajouter une catégorie</a>
<?php
				endif;
?>					
				</div>
			</li>		 
<?php
			if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::CONTRIBUTEUR):
?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown">Articles politique</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="admin.php?section=politique">Voir tous les articles</a>
					<a class="dropdown-item" href="admin.php?section=photos">Ajouter un article</a>
				</div>
<?php
			else:
?>
			<li class="nav-item">
				<a class="nav-link" href="admin.php?section=politique">Articles politique</a>
<?php
			endif;
?>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle id="navbarDropdownMenuLink" data-toggle="dropdown">Articles voyage</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="admin.php?section=voyage">Voir tous les articles</a>
					<div class="dropdown-divider"></div>

<?php
				foreach ($pays as $p):
?>
					<a class="dropdown-item" href="admin.php?section=voyage&pays=<?= $p->id ?>"><?= afficher($p->nom) ?></a>
<?php
				endforeach;

				if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::VOYAGE] <= Utilisateur::CONTRIBUTEUR):
?>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="admin.php?section=voyage&ajouter">Ajouter un article</a>
<?php
				endif;
?>

				</div>
			</li>
<?php
		if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN):
		?>
			<li class="nav-item btn-group">
				<a class="nav-link" href="admin.php?section=pays">Pays</a>
			</li>
			<li class="nav-item btn-group">
				<a class="nav-link" href="admin.php?section=utilisateur">Utilisateurs</a>
			</li>
		<?php
		endif;
		?>
			<li class="nav-item btn-group">
				<a class="nav-link" href="admin.php?section=profil">Profil</a>
			</li>
		</ul>
	</div>
</nav>

<main>

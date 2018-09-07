<div id="banniere">Empreinte</div>

<ul id="nav_ordinateur" class="nav">
	<li class="nav-item btn-group">
		<a class="nav-link" href="index.php?section=photos">Photos</a>
		<button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></button>
		<div class="dropdown-menu">

<?php
		foreach ($categories as $c):
?>
			<a class="dropdown-item" href="index.php?section=photos&categorie=<?= $c->id ?>"><?= afficher($c->nom) ?></a>
<?php
		endforeach;
?>

		</div>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="index.php?section=politique">Articles politique</a>
	</li>
	<li class="nav-item btn-group">
		<a class="nav-link" href="index.php?section=voyage">Articles voyage</a>
		<button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></button>
		<div class="dropdown-menu">

<?php
		foreach ($pays as $p):
?>
			<a class="dropdown-item" href="index.php?section=voyage&pays=<?= $p->id ?>"><?= afficher($p->nom) ?></a>
<?php
		endforeach;
?>

		</div>
	</li>
</ul>




<nav id="nav_smartphone" class="navbar navbar-light fixed-top">
	<a class="navbar-brand" href="index.php">Empreinte</a>
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
					<a class="dropdown-item" href="index.php?section=photos&categorie=<?= $c->id ?>"><?= afficher($c->nom) ?></a>
<?php
				endforeach;
?>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="index.php?section=photos">Voir toutes les catégories</a>
				</div>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="index.php?section=politique">Articles politique</a>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle id="navbarDropdownMenuLink" data-toggle="dropdown">Articles voyage</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="index.php?section=voyage">Voir tous les articles</a>
					<div class="dropdown-divider"></div>

<?php
				foreach ($pays as $p):
?>
					<a class="dropdown-item" href="index.php?section=voyage&pays=<?= $p->id ?>"><?= afficher($p->nom) ?></a>
<?php
				endforeach;
?>

				</div>
			</li>
		</ul>
	</div>
</nav>

<main>		
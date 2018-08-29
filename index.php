<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$bdd = new Bdd();

define('NOMBRE_ARTICLES_PAR_PAGE', 5);

if (!isset($_GET['section'])) {
	$accueil = new AccueilControleur();
	$accueil->index();
}
else {
	if ($_GET['section'] == 'photos') {
		echo $_GET['section'];
	}
	elseif ($_GET['section'] == 'politique') {
		echo $_GET['section'];
	}
	elseif ($_GET['section'] == 'voyages') {
		echo $_GET['section'];
	}
	elseif ($_GET['section'] == 'utilisateur') {
		echo $_GET['section'];
	}
	else {
		$accueil = new AccueilControleur();
		$accueil->index();
	}
}



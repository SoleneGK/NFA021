<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$bdd = new Bdd();

define('NOMBRE_ARTICLES_PAR_PAGE', 2);

// S'il n'y a pas d'utilisateur connecté, l'accès est autorisé uniquement à la page de connexion et à celle de récupération du mot de passe
if (!isset($_SESSION['utilisateur'])) {
	$controleur = new AccueilControleur($bdd->bdd);

	// Forme de l'URL : admin.php?mot_de_passe_perdu
	if(isset($_GET['mot_de_passe_perdu'])) {
		//Forme de l'URL : admin.php?mot_de_passe_perdu&mail=[mail]&code=[code]
		if (isset($_GET['mail']) && isset($_GET['code']))
			$controleur->afficher_formulaire_modifier_mot_de_passe_oublie();
		else
			$controleur->afficher_menu_mot_de_passe_oublie();
	}
	else
		$controleur->afficher_menu_connexion();
}
else {
	if (!isset($_GET['section'])) {
		$controleur = new AccueilControleur($bdd->bdd);
		$controleur->afficher_accueil_admin();
	}
	else {
		$section = strtolower($_GET['section']);

		if ($section = 'politique') {
			$controleur = new ArticleControleur($bdd->bdd);
		}
		elseif ($section = 'voyage') {
			$controleur = new ArticleControleur($bdd->bdd);
		}
		elseif ($section = 'pays') {
			$controleur = new PaysControleur($bdd->bdd);
		}
		elseif ($section = 'photos') {
			$controleur = new PhotoControleur($bdd->bdd);
		}
		elseif ($section = 'categories') {
			$controleur = new CategoriePhotoControleur($bdd->bdd);
		}
		elseif ($section = 'utilisateur') {
			$controleur = new UtilisateurControleur($bdd->bdd);
		}
		elseif ($section = 'profil') {
			$controleur = new UtilisateurControleur($bdd->bdd);
		}
		else {
			$controleur = new AccueilControleur($bdd->bdd);
			$controleur->afficher_accueil_admin();
		}
	}
}
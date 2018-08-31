<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$bdd = new Bdd();

define('NOMBRE_ARTICLES_PAR_PAGE', 2);

// Traitement des demandes de connexion
if (!isset($_SESSION['utilisateur']) && isset($_POST['mail_connexion']) && isset($_POST['mot_de_passe_connexion'])) {
	$connexion = new UtilisateurControleur($bdd->bdd);
	$connexion->connecter($_POST['mail_connexion'], $_POST['mot_de_passe_connexion']);
}

// Traitement des demandes de déconnexion
if (isset($_POST['deconnexion'])) {
	$connexion = new UtilisateurControleur($bdd->bdd);
	$connexion->deconnecter();
}

// S'il n'y a pas d'utilisateur connecté, l'accès est autorisé uniquement à la page de connexion et à celle de récupération du mot de passe
if (!isset($_SESSION['utilisateur'])) {
	$controleur = new AccueilControleur($bdd->bdd);

	// Forme de l'URL : admin.php?mot_de_passe_perdu
	if(isset($_GET['mot_de_passe_perdu'])) {
		//Forme de l'URL : admin.php?mot_de_passe_perdu&mail=[mail]&code=[code]
		if (isset($_GET['mail']) && isset($_GET['code']))
			$controleur->afficher_modifier_mot_de_passe_perdu();
		else
			$controleur->afficher_demander_mot_de_passe_perdu();
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

		if ($section == 'politique') {
			$controleur = new ArticleControleur($bdd->bdd);
		}

		elseif ($section == 'voyage') {
			$controleur = new ArticleControleur($bdd->bdd);
		}

		elseif ($section == 'pays') {
			$controleur = new PaysControleur($bdd->bdd);
		}

		elseif ($section == 'photos') {
			$controleur = new PhotoControleur($bdd->bdd);
		}

		elseif ($section == 'categories') {
			$controleur = new CategoriePhotoControleur($bdd->bdd);
		}

		elseif ($section == 'utilisateur') {
			$controleur = new UtilisateurControleur($bdd->bdd);
		}

		// Forme de l'URL : index.php?section=profil
		elseif ($section == 'profil') {
			$controleur = new UtilisateurControleur($bdd->bdd);

			// Forme de l'URL : index.php?section=profil&modifier
			if (isset($_GET['modifier']))
				$controleur->modifier_profil();
			// Forme de l'URL : index.php?section=profil&modifier_mdp
			elseif (isset($_GET['modifier_mdp']))
				$controleur->modifier_mot_de_passe();
			else
				$controleur->afficher_profil();
		}

		else {
			$controleur = new AccueilControleur($bdd->bdd);
			$controleur->afficher_accueil_admin();
		}
	}
}
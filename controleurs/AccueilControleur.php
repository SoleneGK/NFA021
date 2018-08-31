<?php

class AccueilControleur {
	function index() {
		include 'vues/entete.php';
		include 'vues/accueil/index.php';
		include 'vues/pieddepage.php';
	}

	function afficher_menu_connexion() {
		include 'vues/entete.php';
		// Afficher un message d'erreur en cas de tentative de connexion échouée
		if (isset($_POST['mail_connexion']) && isset($_POST['connexion_mot_de_passe']))
			include 'vues/accueil/echec_connexion.php';
		include 'vues/accueil/menu_connexion.php';
		include 'vues/pieddepage.php';
	}

	function afficher_menu_mot_de_passe_oublie() {
		var_dump(__FUNCTION__);
	}

	function afficher_formulaire_modifier_mot_de_passe_oublie() {
		var_dump(__FUNCTION__);
	}

	function afficher_accueil_admin() {
		include 'vues/entete.php';
		include 'vues/accueil/index_admin.php';
		include 'vues/pieddepage.php';
	}
}
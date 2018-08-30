<?php

class AccueilControleur {
	function index() {
		include 'vues/entete.php';
		include 'vues/accueil/index.php';
		include 'vues/pieddepage.php';
	}

	function afficher_menu_connexion() {
		var_dump(__FUNCTION__);
	}

	function afficher_menu_mot_de_passe_oublie() {
		var_dump(__FUNCTION__);
	}

	function afficher_formulaire_modifier_mot_de_passe_oublie() {
		var_dump(__FUNCTION__);
	}

	function afficher_accueil_admin() {
		var_dump(__FUNCTION__);
	}
}
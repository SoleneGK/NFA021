<?php

class CategoriePhotoControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	// Afficher la liste des catégories
	function afficher_liste() {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		include 'vues/entete.php';
		include 'vues/categorie_photo/liste_categories.php';
		include 'vues/pieddepage.php';
	}
}
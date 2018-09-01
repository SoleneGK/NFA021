<?php

class PaysControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	function afficher_pays() {
		$pays_manager = new PaysManager($this->bdd);
		// Récupérer la liste des pays

		//Ajouter
		if (isset($_POST['ajouter']) && isset($_POST['nom_pays'])) {
			$pays_manager->ajouter_pays($_POST['nom_pays']);
			$message = '<p>Pays créé</p>';
		}
		elseif (isset($_POST['modifier']) && isset($_POST['id_pays']) && isset($_POST['nom_pays'])) {
			$pays_manager->modifier_pays((int)$_POST['id_pays'], $_POST['nom_pays']);
			$message = '<p>Pays modifié</p>';
		}
		elseif (isset($_POST['supprimer']) && isset($_POST['id_pays'])) {
			$pays_manager->supprimer_pays((int)$_POST['id_pays']);
			$message = '<p>Pays supprimé</p>';
		}

		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/pays/afficher_liste.php';
		include 'vues/pieddepage.php';
	}
}
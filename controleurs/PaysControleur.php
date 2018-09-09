<?php

class PaysControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	function afficher_pays() {
		$pays_manager = new PaysManager($this->bdd);
		// Récupérer la liste des pays

		// Ajouter un pays
		if (isset($_POST['ajouter']) && isset($_POST['nom_pays'])) {
			$pays_manager->ajouter_pays(trim($_POST['nom_pays']));
			$message_succes = 'Pays créé';
		}
		// Modifier un pays
		elseif (isset($_POST['modifier']) && isset($_POST['id_pays']) && isset($_POST['nom_pays'])) {
			$pays_manager->modifier_pays((int)$_POST['id_pays'], trim($_POST['nom_pays']));
			$message_succes = 'Pays modifié';
		}
		// Supprimer un pays
		elseif (isset($_POST['supprimer']) && isset($_POST['id_pays'])) {
			// Modifier les articles associés à ce pays
			$article_manager = new ArticleManager($this->bdd);
			$article_manager->supprimer_champ_pays($_POST['id_pays']);
			$pays_manager->supprimer_pays((int)$_POST['id_pays']);
			$message_succes = 'Pays supprimé';
		}

		$pays = $pays_manager->obtenir_liste_pays();

		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';
		include 'vues/pays/afficher_liste.php';
		include 'vues/pieddepage.php';
	}
}
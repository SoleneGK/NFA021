<?php

class UtilisateurControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Connexion d'un utilisateur qui fournit son mail et son mot de passe
	 * Si l'utilisateur existe et a fourni les bonnes informations, l'objet utilisateur est stocké en variable de session
	 */
	function connecter($mail, $mot_de_passe) {
		// Récupérer les informations correspondant au pseudo entré
		$utilisateur_manager = new UtilisateurManager($this->bdd);
		$utilisateur = $utilisateur_manager->obtenir_utilisateur_mail($mail);

		// Vérifier qu'un utilisateur a été trouvé
		if ($utilisateur) {
			// Vérification du mot de passe
			if (password_verify($mot_de_passe, $utilisateur->mot_de_passe)) {
				$_SESSION['utilisateur'] = $utilisateur;
			}
		}
	}

	// Déconnexion de l'utilisateur
	function deconnecter () {
		unset($_SESSION['utilisateur']);
	}
	
}
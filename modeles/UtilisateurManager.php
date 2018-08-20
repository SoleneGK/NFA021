<?php

class UtilisateurManager {
	// Codes numériques associés aux différentes sections du site en bdd
	const TOUT = 0;
	const PHOTOS = 1;
	const POLITIQUE = 2;
	const VOYAGES = 3;

	// Codes numériques associés aux différents types de droits en bdd
	const ADMIN = 0;
	const MODERATEUR = 1;
	const CONTRIBUTEUR = 2;

	public $bdd;

	function __construct() {
		$this->bdd = Bdd::Connexion();
	}

	// Vérifier qu'il n'existe pas d'enregisterement de la table utilisateurs ayant la valeur $valeur dans le champ $champ
	function verifier_libre ($champ, $valeur) {
		$requete = 'SELECT COUNT(id) FROM utilisateurs WHERE '.$champ.' = :valeur';
		echo $requete;
		$requete = $this->bdd->prepare($requete);
		$requete->bindValue('valeur', $valeur);
		$requete->execute();
		$reponse = $requete->fetch(PDO::FETCH_NUM);
		var_dump($reponse);

		if ($reponse[0] == 0)
			$resultat = true;
		else
			$resultat = false;

		return $resultat;
	}

	// Ajouter un utilisateur
	function ajouter ($pseudo, $mail) {
		// Vérifications préalables : champs non vides, pseudo et mail non utilisés
		if (is_empty($pseudo)) {
			$reponse = 'PSEUDO_VIDE';
		}
		else if (is_empty($mail)) {
			$reponse = 'MAIL_VIDE';
		}
		else if (!verifier_libre('pseudo', $pseudo)) {
			$reponse = 'PSEUDO_UTILISE';
		}
		else if (!verifier_libre('mail', $mail)) {
			$reponse = 'MAIL_UTILISE';
		}
		else {
			// Génération d'un mot de passe aléatoire
			
		}
	}
}
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
		$requete = $this->bdd->prepare($requete);
		$requete->bindValue('valeur', $valeur);
		$requete->execute();
		$reponse = $requete->fetch(PDO::FETCH_NUM);

		if ($reponse[0] == 0)
			$resultat = true;
		else
			$resultat = false;

		return $resultat;
	}

	/* Ajouter un utilisateur
	 * Renvoie un message d'erreur ou l'id de l'utilisateur créé
	*/
	function ajouter ($pseudo, $mail) {
		// Vérifications préalables : champs non vides, pseudo et mail non utilisés
		if (empty($pseudo)) {
			$reponse = 'PSEUDO_VIDE';
		}
		else if (empty($mail)) {
			$reponse = 'MAIL_VIDE';
		}
		else if (!$this->verifier_libre('pseudo', $pseudo)) {
			$reponse = 'PSEUDO_UTILISE';
		}
		else if (!$this->verifier_libre('mail', $mail)) {
			$reponse = 'MAIL_UTILISE';
		}
		else {
			// Génération d'un mot de passe aléatoire
			$mot_de_passe = uniqid();
			$mot_de_passe_crypté = password_hash($mot_de_passe, PASSWORD_DEFAULT);

			//Création du compte en bdd
			$requete = 'INSERT INTO utilisateurs (pseudo, mot_de_passe, mail) VALUES (:pseudo, :mot_de_passe, :mail)';
			$requete = $this->bdd->prepare($requete);
			$requete->bindValue('pseudo', $pseudo);
			$requete->bindValue('mot_de_passe', $mot_de_passe_crypté);
			$requete->bindValue('mail', $mail);
			$requete->execute();

			// S'il y a eu une erreur à l'ajout en base de données
			if (!$requete)
				$reponse = 'ERREUR_CREATION';
			else {
				// Envoyer un mail contenant le mot de passe
				// TODO

				// Récupérer l'id de la ligne ajoutée
				$reponse = $this->bdd->lastInsertId();
			}
		}

		return $reponse;
	}

	// Obtenir la liste des droits d'un utilisateur sous forme de tableau associatif
	function obtenir_droits($id) {
		$requete = 'SELECT id_section, type_droit FROM liste_droits WHERE id_utilisateur = :id';
		$requete = $this->bdd->prepare($requete);
		$requete->bindValue('id', $id, PDO::PARAM_INT);
		$requete->execute();

		$droits = [];
		foreach($requete->fetchAll(PDO::FETCH_ASSOC) as $droit)
			$droits[$droit['id_section']] = $droit['type_droit'];

		return $droits;
	}

	// Obtenir les informations d'un utilisateur à partir de son id
	function afficher ($id) {
		$requete = 'SELECT pseudo, mail FROM utilisateurs WHERE id = :id';
		$requete = $this->bdd->prepare($requete);
		$requete->bindValue('id', $id, PDO::PARAM_INT);
		$requete->execute();
		$requete = $requete->fetch(PDO::FETCH_ASSOC);

		// Vérifier qu'il y a bien un utilisateur avec cette id, si oui créer un objet
		if (!$requete)
			$utilisateur = false;
		else {
			$utilisateur = new Utilisateur();
			$utilisateur->id = $id;
			$utilisateur->pseudo = $requete['pseudo'];
			$utilisateur->mail = $requete['mail'];
			$utilisateur->droits = $this->obtenir_droits($id);
		}

		return $utilisateur;
	}

	// Obtenir la liste complète des utilisateurs
	// TODO mettre valeur min et valeur max
	// TODO choisir critère de tri
	// TODO faire une recherche sur le pseudo
	function afficherListe () {
		$requete = 'SELECT id, pseudo, mail FROM utilisateurs';
		$requete = $this->bdd->prepare($requete);
		$requete->execute();

		$utilisateurs = [];
		foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
			$u = new Utilisateur();
			$u->id = $ligne['id'];
			$u->pseudo = $ligne['pseudo'];
			$u->mail = $ligne['mail'];
			$u->droits = $this->obtenir_droits($ligne['id']);

			$utilisateurs[] = $u;
		}

		return $utilisateurs;
	}
}
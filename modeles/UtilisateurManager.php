<?php

class UtilisateurManager {
	const ADMIN = 0;
	const MODERATEUR = 10;
	const CONTRIBUTEUR = 20;
	const SANS_DROIT = 30;

	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Récupérer le pseudo et le mail d'un utilisateur identifié par son id
	 * Renvoie un objet Utilisateur s'il existe
	 * Renvoie false sinon
	 */
	function obtenir_utilisateur ($id) {
		$req = 'SELECT pseudo, mail FROM utilisateurs WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		// Vérifier qu'il y a bien un utilisateur avec cette id, si oui créer un objet
		if (!$req)
			$utilisateur = false;
		else
			$utilisateur = new Utilisateur($id, $req['pseudo'], $req['mail']);

		return $utilisateur;
	}


}
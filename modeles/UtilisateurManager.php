<?php

class UtilisateurManager {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Récupérer le pseudo et le mail d'un utilisateur identifié par son id
	 * Renvoie un objet Utilisateur s'il existe
	 * Renvoie false sinon
	 */
	function obtenir_utilisateur($id) {
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

	/* Récupérer l'id, le pseudo et le mail d'un utilisateur identifié par son mail
	 * Renvoie un objet Utilisateur s'il existe
	 * Renvoie false sinon
	 */
	function obtenir_utilisateur_mail($mail) {
		$req = 'SELECT id, pseudo, mot_de_passe FROM utilisateurs WHERE mail = :mail';
		$req = $this->bdd->prepare($req);
		$req->bindValue('mail', $mail);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		// Vérifier qu'il y a bien un utilisateur avec cette id, si oui créer un objet
		if (!$req)
			$utilisateur = false;
		else
			$utilisateur = new Utilisateur($req['id'], $req['pseudo'], $mail, null, $req['mot_de_passe']);

		return $utilisateur;
	}

	/* Modifier le code de récupération de mot de passe et la date limite d'un utilisateur identifié par son mail
	 * Renvoie le nombre de lignes modifiées
	 */
	function modifier_code_recuperation($mail, $code, $date_expiration) {
		$req = 'UPDATE utilisateurs SET code_recuperation = :code, date_expiration_code = :date_expiration WHERE mail = :mail';
		$req = $this->bdd->prepare($req);
		$req->bindValue('code', $code);
		$req->bindValue('date_expiration', $date_expiration, PDO::PARAM_INT);
		$req->bindValue('mail', $mail);
		$req->execute();

		return $req->rowCount();
	}
}
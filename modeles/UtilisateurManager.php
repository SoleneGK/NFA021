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

	/* Récupérer l'id, le code de récupération de mot de passe et la date limite d'un utilisateur identifié par son mail
	 * Renvoie un array associatif avec les clés id, code_recuperation et date_expiration_code s'il existe
	 * Renvoie null sinon
	 */
	function obtenir_code_recuperation($mail) {
		$req = 'SELECT id, code_recuperation, date_expiration_code FROM utilisateurs WHERE mail = :mail';
		$req = $this->bdd->prepare($req);
		$req->bindValue('mail', $mail);
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	/* Modifier le mot de passe d'un utilisateur identifié par son id
	 * Le mot de passe en paramètre doit être crypté
	 * Renvoie un booléen
	 */
	function changer_mot_de_passe($id, $mot_de_passe) {
		$req = 'UPDATE utilisateurs SET mot_de_passe = :mot_de_passe WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('mot_de_passe', $mot_de_passe);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		return $req->execute();
	}

	/* Modifier le pseudo et le mail d'un utilisateur identifié par son id
	 * Renvoie un booléen
	 */
	function changer_pseudo_mail($id, $pseudo, $mail) {
		$req = 'UPDATE utilisateurs SET pseudo = :pseudo, mail = :mail WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('pseudo', $pseudo);
		$req->bindValue('mail', $mail);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		return $req->execute();
	}

	/* Vérifier qu'un pseudo et un mot de passe sont disponibles
	 * Renvoie un array associatif de booleens
	 * Clés : pseudo_dispo, mail_dispo
	 */
	function verifier_dispo_pseudo_mail($pseudo, $mail) {
		$req = 'SELECT
					!(SELECT COUNT(id) FROM utilisateurs WHERE pseudo = :pseudo) AS pseudo_dispo,
					!(SELECT COUNT(id) FROM utilisateurs WHERE mail = :mail) AS mail_dispo';
		$req = $this->bdd->prepare($req);
		$req->bindValue('pseudo', $pseudo);
		$req->bindValue('mail', $mail);
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC);
	}

	/* Obtenir le mot de passe d'un utilisateur identifié par son id
	 * Renvoie un string
	 */
	function obtenir_mot_de_passe($id) {
		$req = 'SELECT mot_de_passe FROM utilisateurs WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		return $req->fetch(PDO::FETCH_ASSOC)['mot_de_passe'];
	}
}
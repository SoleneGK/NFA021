<?php

class CommentaireManager {
	public $bdd;

	function __construct() {
		$this->bdd = Bdd::Connexion();
	}

	/* Ajouter un commentaire
	 * Soit un utilisateur est connecté, auquel cas son id est utilisée, soit un pseudo doit être fourni par l'internaute
	 */
	function ajouter($id_utilisateur = null, $pseudo = null, $mail = null, $id_article, $contenu) {
		// Vérifier que le contenu du commentaire n'est pas vide
		if (empty($contenu))
			$reponse = 'CONTENU_VIDE';
		else {
			// S'il n'y a pas d'utilisateur connecté, vérifier que le pseudo est fourni
			if (empty($id_utilisateur)) {
				$id_utilisateur = null;
				if (empty($pseudo))
					$reponse = 'PSEUDO_VIDE';
			}
			// Si un utilisateur est connecté, vérifier qu'il existe
			else {
				$req = 'SELECT pseudo, mail FROM utilisateurs WHERE id = :id';
				$req = $this->bdd->prepare($req);
				$req->bindValue('id', $id_utilisateur, PDO::PARAM_INT);
				$req->execute();
				$req = $req->fetch(PDO::FETCH_ASSOC);

				if (!$req)
					$reponse = 'UTILISATEUR_INCONNU';
				else {
					$pseudo = $req['pseudo'];
					$mail = $req['mail'];
				}
			}

			// Si les informations sur la personne qui a posté le commentaire sont correctes, suite des vérifications
			if (!isset($reponse)) {
				// Vérifier que l'article existe
				$req = 'SELECT COUNT(id) FROM articles WHERE id = :id';
				$req = $this->bdd->prepare($req);
				$req->bindValue('id', $id_article, PDO::PARAM_INT);
				$req->execute();
				$req = $req->fetch(PDO::FETCH_NUM);

				if ($req[0] == 0)
					$reponse = 'ARTICLE_INCONNU';
				else {
					// Ajout du commentaire en bdd
					$req = 'INSERT INTO commentaires(id_utilisateur, pseudo, mail, id_article, contenu, date_ajout) VALUES (:id_utilisateur, :pseudo, :mail, :id_article, :contenu, :date_ajout)';
					$req = $this->bdd->prepare($req);
					$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
					$req->bindValue('pseudo', $pseudo);
					$req->bindValue('mail', $mail);
					$req->bindValue('id_article', $id_article, PDO::PARAM_INT);
					$req->bindValue('contenu', $contenu);
					$req->bindValue('date_ajout', time(), PDO::PARAM_INT);
					$req = $req->execute();

					if ($req)
						$reponse = 'OK';
					else
						$reponse = 'ERREUR_CREATION';
				}
			}
		}

		return $reponse;
	}

	// Afficher un commentaire en fonction de son id
	function afficher($id) {

	}	
}
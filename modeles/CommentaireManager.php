<?php

class CommentaireManager {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Afficher la liste des commentaires d'un article
	 * Renvoie un array d'objets Commentaire
	 */
	function obtenir_commentaires_article($id_article) {
		$req = 'SELECT id, id_utilisateur, pseudo, mail, contenu, date_ajout FROM commentaires WHERE id_article = :id_article ORDER BY id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_article', $id_article, PDO::PARAM_INT);
		$req->execute();

		$commentaires = [];
		foreach($req->fetchAll(PDO::FETCH_ASSOC) as $c) {
			$u = new Utilisateur($c['id_utilisateur'], $c['pseudo'], $c['mail']);
			$commentaire = new Commentaire($c['id'], $u, $c['contenu'], $c['date_ajout']);

			$commentaires[] = $commentaire;
		}

		return $commentaires;
	}

	/* Ajouter un commentaire
	 * Renvoie un array
	 */
	function ajouter_commentaire($id_utilisateur, $pseudo, $mail, $id_article, $contenu) {
		$req = 'INSERT INTO commentaires(id_utilisateur, pseudo, mail, id_article, contenu, date_ajout) VALUES (:id_utilisateur, :pseudo, :mail, :id_article, :contenu, :date_ajout)';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
		$req->bindValue('pseudo', $pseudo);
		$req->bindValue('mail', $mail);
		$req->bindValue('id_article', $id_article, PDO::PARAM_INT);
		$req->bindValue('contenu', $contenu);
		$req->bindValue('date_ajout', time(), PDO::PARAM_INT);
		return $req->execute();
	}

	function modifier_commentaire($id, $pseudo, $mail, $contenu) {
		$req = 'UPDATE commentaires SET pseudo = :pseudo, mail = :mail, contenu = :contenu WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('pseudo', $pseudo);
		$req->bindValue('mail', $mail);
		$req->bindValue('contenu', $contenu);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		return $req->execute();
	}

	function supprimer_commentaire($id) {
		$req = 'DELETE FROM commentaires WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		return $req->execute();
	}
}
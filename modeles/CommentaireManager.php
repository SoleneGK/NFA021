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
		$req = 'SELECT c.id AS id,
					c.id_utilisateur AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur_enregistre,
					c.pseudo AS pseudo_utilisateur,
					c.mail AS mail,
					c.contenu AS contenu,
					c.date_ajout AS date_ajout
				FROM commentaires AS c
				LEFT JOIN utilisateurs AS u ON c.id_utilisateur = u.id
				WHERE c.id_article = :id_article
				ORDER BY c.id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_article', $id_article, PDO::PARAM_INT);
		$req->execute();

		$commentaires = [];
		foreach($req->fetchAll(PDO::FETCH_ASSOC) as $c) {
			if ($c['pseudo_utilisateur_enregistre'])
				$u = new Utilisateur($c['id_utilisateur'], $c['pseudo_utilisateur_enregistre'], null);
			else
				$u = new Utilisateur($c['id_utilisateur'], $c['pseudo_utilisateur'], $c['mail']);

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

	function modifier_commentaire($id, $pseudo, $contenu) {
		$req = 'UPDATE commentaires SET pseudo = :pseudo, contenu = :contenu WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('pseudo', $pseudo);
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

	function supprimer_commentaires_article($id_article) {
		$req = 'DELETE FROM commentaires WHERE id_article = :id_article';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_article', $id_article, PDO::PARAM_INT);
		return $req->execute();
	}
}
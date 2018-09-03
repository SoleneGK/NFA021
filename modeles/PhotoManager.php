<?php

class PhotoManager {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Obtenir les informations d'une photo
	 * Renvoie un objet Photo si elle existe
	 * Renvoie false sinon
	 */
	function obtenir_photo($id) {
		$req = 'SELECT p.titre AS titre,
					p.nom_fichier AS nom_fichier,
					p.description AS description,
					p.date_ajout AS date_ajout,
					c.id AS id_categorie,
					c.nom AS nom_categorie,
					c.description AS description_categorie,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur,
					u.mail AS mail_utilisateur
				FROM photos AS p
				JOIN utilisateurs AS u ON p.id_utilisateur = u.id
				LEFT JOIN categories_photos AS c ON p.id_categorie = c.id
				WHERE p.id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		if(!$req)
			$photo = false;
		else {
			$u = new Utilisateur($req['id_utilisateur'], $req['pseudo_utilisateur'], $req['mail_utilisateur']);
			$c = new CategoriePhoto($req['id_categorie'], $req['nom_categorie'], $req['description_categorie']);
			$photo = new Photo($id, $req['titre'], $u, $req['nom_fichier'], $c, $req['description'], $req['date_ajout']);
		}

		return $photo;
	}

	/* Obtenir les informations des photos appartenant à une catégorie
	 * Renvoie un array d'objets Photos
	 */
	function obtenir_photos_categorie(CategoriePhoto $categorie) {
		$req = 'SELECT p.id AS id,
					p.titre AS titre,
					p.nom_fichier AS nom_fichier,
					p.description AS description,
					p.date_ajout AS date_ajout,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur,
					u.mail AS mail_utilisateur
				FROM photos AS p
				JOIN utilisateurs AS u ON p.id_utilisateur = u.id
				JOIN categories_photos AS c ON p.id_categorie = c.id
				WHERE c.id = :id
				ORDER BY p.id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $categorie->id, PDO::PARAM_INT);
		$req->execute();

		$photos = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $photo) {
			$u = new Utilisateur($photo['id_utilisateur'], $photo['pseudo_utilisateur'], $photo['mail_utilisateur']);
			$p = new Photo($photo['id'], $photo['titre'], $u, $photo['nom_fichier'], $categorie, $photo['description'], $photo['date_ajout']);

			$photos[] = $p;
		}

		return $photos;
	}

	/* Renvoie les noms des fichiers des photos appartenant à une catégorie
	 * Renvoie un array
	 */
	function obtenir_fichiers_photos_categorie($id_categorie) {
		$req = 'SELECT nom_fichier FROM photos WHERE id_categorie = :id_categorie';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_categorie', $id_categorie, PDO::PARAM_INT);
		$req->execute();
		return $req->fetchAll(PDO::FETCH_ASSOC);
	}

	/* Supprimer les photos appartenant à une catégorie
	 * Renvoie un booléen
	 */
	function supprimer_photos_categorie($id_categorie) {
		$req = 'DELETE FROM photos WHERE id_categorie = :id_categorie';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_categorie', $id_categorie, PDO::PARAM_INT);
		return $req->execute();
	}

	/* Ajouter une photo en base de données
	 * Renvoie un booléen
	 */
	function ajouter_photo($titre, $id_utilisateur, $nom_image, $id_categorie, $description) {
		$req = 'INSERT INTO photos(titre, id_utilisateur, nom_fichier, id_categorie, description, date_ajout) VALUES (:titre, :id_utilisateur, :nom_fichier, :id_categorie, :description, :date_ajout)';
		$req = $this->bdd->prepare($req);
		$req->bindValue('titre', $titre);
		$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
		$req->bindValue('nom_fichier', $nom_image);
		$req->bindValue('id_categorie', $id_categorie, PDO::PARAM_INT);
		$req->bindValue('description', $description);
		$req->bindValue('date_ajout', time(), PDO::PARAM_INT);
		return $req->execute();
	}

	/* Modifier une photo
	 * Renvoie un booléen
	 */
	function modifier_photo($id, $titre, $id_categorie, $id_utilisateur, $description) {
		$req = 'UPDATE photos SET titre = :titre, id_categorie = :id_categorie, id_utilisateur = :id_utilisateur, description = :description WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('titre', $titre);
		$req->bindValue('id_categorie', $id_categorie, PDO::PARAM_INT);
		$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
		$req->bindValue('description', $description);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		return $req->execute();
	}
}
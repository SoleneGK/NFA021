<?php

class PhotoManager {
	public $bdd;

	function __construct() {
		$this->bdd = Bdd::Connexion();
	}

	// Ajouter une photo
	function ajouter($titre, $id_utilisateur, $image, $id_categorie, $description) {
		// Vérifier que le titre n'est pas vide, qu'un fichier a été fourni et que la catégorie n'est pas vide
		if (empty($titre))
			$reponse = 'TITRE_VIDE';
		elseif (!isset($_FILES[$image]))
			$reponse = 'IMAGE_VIDE';
		elseif (empty($id_categorie))
			$reponse = 'CATEGORIE_VIDE';
		else {
			// Vérifier que la catégorie existe
			$req = 'SELECT COUNT(id) FROM categories_photos WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('id', $id_categorie, PDO::PARAM_INT);
			$req->execute();
			$req = $req->fetch(PDO::FETCH_NUM);

			if ($req[0] == 0)
				$reponse = 'CATEGORIE_INEXISTANTE';
			else {
				// Vérifier que l'extension du fichier correspond à celle d'une image
				$extension = strtolower(pathinfo($_FILES[$image]['name'], PATHINFO_EXTENSION));
				$extensions_autorisees = ['jpg', 'jpeg', 'gif', 'png'];
				if (!in_array($extension, $extensions_autorisees))
					$reponse = 'PAS_UNE_IMAGE';
				else {
					// Création d'un nom de fichier unique pour le fichier et déplacemant dans le dossier définitif
					$nom_image = md5(uniqid()).'.'.$extension;
					
					if (!move_uploaded_file($_FILES[$image]['tmp_name'], 'public/images/photos/'.$nom_image))
						$reponse = 'ERREUR_COPIE_IMAGE';
					else {
						// Insertion des informations en bdd
						$req = 'INSERT INTO photos(titre, id_utilisateur, nom_fichier, id_categorie, description, date_ajout) VALUES (:titre, :id_utilisateur, :nom_fichier, :id_categorie, :description, :date_ajout)';
						$req = $this->bdd->prepare($req);
						$req->bindValue('titre', $titre);
						$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
						$req->bindValue('nom_fichier', $nom_image);
						$req->bindValue('id_categorie', $id_categorie, PDO::PARAM_INT);
						$req->bindValue('description', $description);
						$req->bindValue('date_ajout', time(), PDO::PARAM_INT);
						$req = $req->execute();

						if ($req)
							$reponse = $this->bdd->lastInsertId();
						else
							$reponse = 'ERREUR_AJOUT_BDD';
					}
				}
			}
		}
		
		return $reponse;
	}

	// Afficher une photo
	function afficher($id) {
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

	// Afficher les photos appartenant à une catégorie
	function afficher_categorie($id_categorie) {
		$req = 'SELECT p.id AS id,
					p.titre AS titre,
					p.nom_fichier AS nom_fichier,
					p.description AS description,
					p.date_ajout AS date_ajout,
					c.nom AS nom_categorie,
					c.description AS description_categorie,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur,
					u.mail AS mail_utilisateur
				FROM photos AS p
				JOIN utilisateurs AS u ON p.id_utilisateur = u.id
				JOIN categories_photos AS c ON p.id_categorie = c.id
				WHERE c.id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id_categorie, PDO::PARAM_INT);
		$req->execute();

		$photos = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $photo) {
			$u = new Utilisateur($photo['id_utilisateur'], $photo['pseudo_utilisateur'], $photo['mail_utilisateur']);
			$c = new CategoriePhoto($id_categorie, $photo['nom_categorie'], $photo['description_categorie']);
			$p = new Photo($photo['id'], $photo['titre'], $u, $photo['nom_fichier'], $c, $photo['description'], $photo['date_ajout']);

			$photos[] = $p;
		}

		return $photos;
	}

	// Afficher toutes les photos
	function afficher_tout() {
		$req = 'SELECT p.id AS id,
					p.titre AS titre,
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
				LEFT JOIN categories_photos AS c ON p.id_categorie = c.id';
		$req = $this->bdd->prepare($req);
		$req->execute();

		$photos = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $photo) {
			$u = new Utilisateur($photo['id_utilisateur'], $photo['pseudo_utilisateur'], $photo['mail_utilisateur']);
			$c = new CategoriePhoto($photo['id_categorie'], $photo['nom_categorie'], $photo['description_categorie']);
			$p = new Photo($photo['id'], $photo['titre'], $u, $photo['nom_fichier'], $c, $photo['description'], $photo['date_ajout']);

			$photos[] = $p;
		}

		return $photos;
	}

	function modifier($id, $titre, $id_categorie, $id_utilisateur, $description) {
		// Vérifie que la catégorie existe
		$req = 'SELECT COUNT(id) FROM categories_photos WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id_categorie, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_NUM);

		if ($req[0] == 0)
			$reponse = 'CATEGORIE_INEXISTANTE';
		else {
			// Vérifier que l'utilisateur existe
			$req = 'SELECT COUNT(id) FROM utilisateurs WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('id', $id_utilisateur, PDO::PARAM_INT);
			$req->execute();
			$req = $req->fetch(PDO::FETCH_NUM);

			if ($req[0] == 0)
				$reponse = 'UTILISATEUR_INEXISTANT';
			else {
				$req = 'UPDATE photos SET titre = :titre, id_categorie = :id_categorie, id_utilisateur = :id_utilisateur, description = :description WHERE id = :id';
				$req = $this->bdd->prepare($req);
				$req->bindValue('titre', $titre);
				$req->bindValue('id_categorie', $id_categorie, PDO::PARAM_INT);
				$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
				$req->bindValue('description', $description);
				$req->bindValue('id', $id, PDO::PARAM_INT);
				$resultat = $req->execute();

				if ($resultat) {
					if ($req->rowCount() == 1)
						$reponse = 'OK';
					else
						$reponse = 'AUCUNE_LIGNE_MODIFIEE';
				}
				else
					$reponse = 'ERREUR_MODIFICATION';
			}
		}

		return $reponse;
	}

	function supprimer($id) {
		// Obtenir le nom de fichier de la photo
		$req = 'SELECT nom_fichier FROM photos WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		// Supprimer le fichier
		$suppression = unlink('public/images/photos/'.$req['nom_fichier']);

		if (!$suppression)
			$reponse = 'ERREUR_SUPPRESSION_FICHIER';
		else {
			// Supprimer l'enregistement en bdd
			$req = 'DELETE FROM photos WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('id', $id, PDO::PARAM_INT);
			$resultat = $req->execute();

			if ($resultat) {
				if ($req->rowCount() == 1)
					$reponse = 'OK';
				else
					$reponse = 'AUCUNE_LIGNE_SUPPRIMEE';
			}
			else
				$reponse = 'ERREUR_SUPPRESSION';
		}

		return $reponse;
	}
}
<?php

class PaysManager {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Obtenir les informations d'un pays
	 * Renvoie un objet de type pays s'il existe
	 * Renvoie false sinon
	 */
	function obtenir_pays($id) {
		$req = 'SELECT nom FROM pays WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		if(!$req)
			$pays = false;
		else
			$pays = new Pays($id, $req['nom']);

		return $pays;
	}

	/* Obtenir la liste des pays
	 * Renvoie un array d'objets Pays
	 */
	function obtenir_liste_pays() {
		$req = 'SELECT id, nom FROM pays ORDER BY id';
		$req = $this->bdd->prepare($req);
		$req->execute();

		$pays = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
			$p = new Pays($ligne['id'], $ligne['nom']);
			$pays[] = $p;
		}

		return $pays;
	}

	function ajouter_pays($nom) {
		$req = 'INSERT INTO pays(nom) VALUES (:nom)';
		$req = $this->bdd->prepare($req);
		$req->bindValue('nom', $nom);
		return $req->execute();
	}

	function modifier_pays($id, $nom) {
		$req = 'UPDATE pays SET nom = :nom WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('nom', $nom);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$resultat = $req->execute();
}

	function supprimer_pays($id) {
		$req = 'DELETE FROM pays WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		return $req->execute();
	}
}
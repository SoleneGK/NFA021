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
}
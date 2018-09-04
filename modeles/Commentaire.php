<?php

class Commentaire {
	public $id;
	public $utilisateur;
	public $contenu;
	public $date_ajout;

	function __construct($id, $utilisateur, $contenu, $date_ajout) {
		$this->id = $id;
		$this->utilisateur = $utilisateur;
		$this->contenu = $contenu;
		$this->date_ajout = $date_ajout;
	}
}
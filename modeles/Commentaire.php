<?php

class Commentaire {
	public $id;
	public $utilisateur;
	public $article;
	public $contenu;
	public $date_ajout;

	function __construct($id, $utilisateur, $article, $contenu, $date_ajout) {
		$this->id = $id;
		$this->utilisateur = $utilisateur;
		$this->article = $article;
		$this->contenu = $contenu;
		$this->date_ajout = $date_ajout;
	}
}
<?php

class Article {
	public $id;
	public $titre;
	public $section;
	public $contenu;
	public $date_publication;
	public $utilisateur;
	public $pays;

	function __construct($id, $titre, $section, $contenu = null, $date_publication = null, $utilisateur = null, $pays = null) {
		$this->id = $id;
		$this->titre = $titre;
		$this->section = $section;
		$this->contenu = $contenu;
		$this->date_publication = $date_publication;
		$this->utilisateur = $utilisateur;
		$this->pays = $pays;
	}
}
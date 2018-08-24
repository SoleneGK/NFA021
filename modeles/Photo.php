<?php

class Photo {
	public $id;
	public $titre;
	public $utilisateur;
	public $nom_fichier;
	public $categorie;
	public $description;
	public $date_ajout;

	function __construct($id, $titre, $utilisateur, $nom_fichier, $categorie, $description, $date_ajout) {
		$this->id = $id;
		$this->titre = $titre;
		$this->utilisateur = $utilisateur;
		$this->nom_fichier = $nom_fichier;
		$this->categorie = $categorie;
		$this->description = $description;
		$this->date_ajout = $date_ajout;
	}
}
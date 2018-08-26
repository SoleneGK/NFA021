<?php

class Commentaire {
	public $id;
	public $id_utilisateur;
	public $pseudo;
	public $mail;
	public $article;
	public $contenu;
	public $date_ajout;

	function __construct($id, $id_utilisateur, $pseudo, $mail, $article, $contenu, $date_ajout) {
		$this->id = $id;
		$this->id_utilisateur = $id_utilisateur;
		$this->pseudo = $pseudo;
		$this->mail = $mail;
		$this->article = $article;
		$this->contenu = $contenu;
		$this->date_ajout = $date_ajout;
	}
}
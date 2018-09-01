<?php

class Utilisateur {
	const ADMIN = 0;
	const MODERATEUR = 10;
	const CONTRIBUTEUR = 20;
	const SANS_DROIT = 30;
	
	public $id;
	public $pseudo;
	public $mail;
	public $mot_de_passe;

	function __construct($id, $pseudo, $mail, $droits = null, $mot_de_passe = null) {
		$this->id = $id;
		$this->pseudo = $pseudo;
		$this->mail = $mail;
		$this->mot_de_passe = $mot_de_passe;
	}
}
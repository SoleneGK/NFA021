<?php

class Utilisateur {
	const ADMIN = 0;
	const MODERATEUR = 10;
	const CONTRIBUTEUR = 20;
	const SANS_DROIT = 30;
	
	public $id;
	public $pseudo;
	public $mail;
	public $droits;

	function __construct($id, $pseudo, $mail, $droits = null) {
		$this->id = $id;
		$this->pseudo = $pseudo;
		$this->mail = $mail;
		$this->droits = $droits;
	}
}
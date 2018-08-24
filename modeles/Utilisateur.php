<?php

class Utilisateur {
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
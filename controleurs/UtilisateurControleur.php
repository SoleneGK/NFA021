<?php

class UtilisateurControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	
}
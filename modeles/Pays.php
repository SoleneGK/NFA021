<?php

class Pays {
	public $id;
	public $nom;

	function __construct($id, $nom) {
		$this->id = $id;
		$this->nom = $nom;
	}
}
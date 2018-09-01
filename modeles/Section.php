<?php

class Section {
	const TOUT = 0;
	const PHOTOS = 1;
	const POLITIQUE = 2;
	const VOYAGE = 3;

	public $id;
	public $nom;

	function __construct($id, $nom) {
		$this->id = $id;
		$this->nom = $nom;
	}
}
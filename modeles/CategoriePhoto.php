<?php

class CategoriePhoto {
	public $id;
	public $nom;
	public $description;

	function __construct($id, $nom, $description) {
		$this->id = $id;
		$this->nom = $nom;
		$this->description = $description;
	}
}
<?php

class Bdd {
	public $bdd;

	function __construct() {
		try {
			$host = "127.0.0.1";
			$user = "root";
			$pass = "";
			$base = "nfa021"; 

			$this->bdd = new PDO('mysql:host='.$host.';dbname='.$base, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
			$this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			echo 'Erreur : ', $e->getMessage(), '<br>';
		}
	}
}
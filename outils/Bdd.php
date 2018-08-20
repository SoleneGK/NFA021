<?php

class Bdd {
	public static function Connexion() {
		try {
			require_once '/outils/bdd_data.php';

			$bdd = new PDO("mysql:host=".$host.";dbname=".$base, $user, $pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $bdd;

		}
		catch (PDOException $e) {
			echo 'Erreur : ', $e->getMessage(), '<br />';
		}
	}
}
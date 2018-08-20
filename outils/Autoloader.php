<?php

class Autoloader {

	public static function enregistrer() {
		spl_autoload_register([__CLASS__, 'chargerControleurs']);
		spl_autoload_register([__CLASS__, 'chargerModeles']);
		spl_autoload_register([__CLASS__, 'chargerOutils']);
	}

	// Charger les classes du dossier controleurs
	private static function chargerControleurs($classe) {
		$fichier = "controleurs/".ucfirst($classe).".php";
		if (file_exists($fichier)) {
			require_once $fichier;
		}
	}

	// Charger les classes du dossier modeles
	private static function chargerModeles($classe) {
		$fichier = "modeles/".ucfirst($classe).".php";
		if (file_exists($fichier)) {
			require_once $fichier;
		}
	}

	// Charger les classes du dossier outils
	private static function chargerOutils($classe) {
		$fichier = "outils/".ucfirst($classe).".php";
		if (file_exists($fichier)) {
			require_once $fichier ;
		}
	}
}
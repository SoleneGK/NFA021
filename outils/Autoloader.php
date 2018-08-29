<?php

class Autoloader {

	public static function enregistrer() {
		spl_autoload_register([__CLASS__, 'charger_controleurs']);
		spl_autoload_register([__CLASS__, 'charger_modeles']);
		spl_autoload_register([__CLASS__, 'charger_outils']);
	}

	// Charger les classes du dossier controleurs
	private static function charger_controleurs($classe) {
		$fichier = "controleurs/".ucfirst($classe).".php";
		if (file_exists($fichier)) {
			require_once $fichier;
		}
	}

	// Charger les classes du dossier modeles
	private static function charger_modeles($classe) {
		$fichier = "modeles/".ucfirst($classe).".php";
		if (file_exists($fichier)) {
			require_once $fichier;
		}
	}

	// Charger les classes du dossier outils
	private static function charger_outils($classe) {
		$fichier = "outils/".ucfirst($classe).".php";
		if (file_exists($fichier)) {
			require_once $fichier ;
		}
	}
}
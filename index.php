<?php
require_once("outils/Autoloader.php");
Autoloader::enregistrer();

session_start();

$util = new UtilisateurManager();


var_dump($util->connexion('solene', 'bépo'));
//var_dump($_SESSION);
//var_dump($util->modifier_mot_de_passe ('5b7dcbf60a976', 'bépo', 'bépo'));

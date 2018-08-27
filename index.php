<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$a = new UtilisateurManager();
var_dump($a->modifier_mot_de_passe_perdu('cancerso@hotmail.fr', '19776c542da1c26ffcae6bf7c420bd61', 'bépo', 'bépo'));

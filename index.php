<?php

require_once("outils/Autoloader.php");
Autoloader::enregistrer();

$util = new UtilisateurManager();

var_dump($util->afficherListe());
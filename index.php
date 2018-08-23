<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$util = new UtilisateurManager();


var_dump($util->afficherListe());


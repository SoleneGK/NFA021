<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$util = new UtilisateurManager();


$util->ajouter('cancerso', 'cancerso@hotmail.fr', 'cancerso@hotmail.fr');


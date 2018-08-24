<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$p = new PaysManager();
var_dump($p->supprimer(4));



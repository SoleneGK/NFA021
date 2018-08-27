<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$a = new CommentaireManager();
var_dump($a->supprimer(1));

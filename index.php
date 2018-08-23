<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$cat = new CategoriePhotoManager();

var_dump($cat->supprimer(1));



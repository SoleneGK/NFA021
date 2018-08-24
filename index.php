<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$p = new CategoriePhotoManager();
var_dump($p->afficher_tout());



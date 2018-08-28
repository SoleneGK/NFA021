<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$bdd = new Bdd();

$a = new ArticleManager($bdd->bdd);
$b = new CategoriePhotoManager($bdd->bdd);

var_dump($b->afficher_tout());



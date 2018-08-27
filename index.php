<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$a = new ArticleManager();
var_dump($a->afficher_liste_pays(0, 2, 1));

//$titre, $id_section, $contenu, $id_utilisateur, $id_pays = null

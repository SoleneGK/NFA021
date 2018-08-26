<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$c = new CommentaireManager();
var_dump($c->supprimer(1));


//function ajouter($id_utilisateur = null, $pseudo = null, $mail = null, $id_article, $contenu)
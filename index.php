<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$util = new UtilisateurManager();


$util->modifier_droit(6, 2, 20);


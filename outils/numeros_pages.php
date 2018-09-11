<?php

/* Obtenir les numéros des pages pour la navigation dans la liste des articles
 * Renvoie un array associatif
 * page_precedente => null si la page actuelle est la 1e, ou le numéro de la page
 * page_suivante => null si la page actuelle est la dernière, ou le numéro de la page
 * derniere_page => le numéro de la page
 */
function obtenir_numeros_pages($numero_page_actuelle, $nombre_items, $nombre_items_par_page) {
	if ($numero_page_actuelle == 1)
		$numeros['page_precedente'] = null;
	else
		$numeros['page_precedente'] = $numero_page_actuelle - 1;

	$numeros['derniere_page'] = (int)ceil($nombre_items / $nombre_items_par_page);

	if ($numero_page_actuelle < $numeros['derniere_page'])
		$numeros['page_suivante'] = $numero_page_actuelle + 1;
	else
		$numeros['page_suivante'] = null;

	return $numeros;
}


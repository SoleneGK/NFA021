<?php

function afficher($texte) {
	return htmlspecialchars(nl2br($texte));
}
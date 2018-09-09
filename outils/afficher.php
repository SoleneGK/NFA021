<?php

function afficher($texte) {
	return nl2br(htmlspecialchars($texte));
}
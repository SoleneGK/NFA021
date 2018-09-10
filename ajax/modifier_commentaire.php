<?php
require_once '../outils/afficher.php';
require_once '../outils/AutoloaderAjax.php';
AutoloaderAjax::enregistrer();

session_start();

$id_commentaire = (int)$_POST['id_commentaire'];
$id_section = (int)$_POST['id_section'];
$id_utilisateur = (int)$_POST['id_utilisateur'];

$bdd = new Bdd();

$utilisateur_manager = new UtilisateurManager($bdd->bdd);
$_SESSION['utilisateur']->droits = $utilisateur_manager->obtenir_droits($_SESSION['utilisateur']->id);

if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || 
		($id_section == Section::POLITIQUE && $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::MODERATEUR) || 
		($id_section == Section::VOYAGE && $_SESSION['utilisateur']->droits[Section::VOYAGE] <= Utilisateur::MODERATEUR)) {

	$commentaire_manager = new CommentaireManager($bdd->bdd);

	if (!empty($_POST['id_utilisateur']))
		$reponse = $commentaire_manager->modifier_commentaire($id_commentaire, null, $_POST['contenu']);
	else
		$reponse = $commentaire_manager->modifier_commentaire($id_commentaire, trim($_POST['pseudo']), trim($_POST['contenu']));

	if ($reponse) {
		$retour['status'] = true;
		$retour['pseudo'] = afficher(trim($_POST['pseudo']));
		$retour['contenu'] = afficher(trim($_POST['contenu']));
	}
	else
		$retour['status'] = false;
}
else {
	$retour['status'] = false;
}

header("Content-type: application/json;charset=UTF-8");
echo json_encode($retour);

<?php

class UtilisateurControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Connexion d'un utilisateur qui fournit son mail et son mot de passe
	 * Si l'utilisateur existe et a fourni les bonnes informations, l'objet utilisateur est stocké en variable de session
	 */
	function connecter($mail, $mot_de_passe) {
		// Récupérer les informations correspondant au pseudo entré
		$utilisateur_manager = new UtilisateurManager($this->bdd);
		$utilisateur = $utilisateur_manager->obtenir_utilisateur_mail($mail);

		// Vérifier qu'un utilisateur a été trouvé
		if ($utilisateur) {
			// Vérification du mot de passe
			if (password_verify($mot_de_passe, $utilisateur->mot_de_passe)) {
				$_SESSION['utilisateur'] = $utilisateur;
			}
		}
	}

	// Déconnexion de l'utilisateur
	function deconnecter () {
		unset($_SESSION['utilisateur']);
	}
	
	function afficher_profil() {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';
		include 'vues/utilisateur/afficher_profil.php';
		include 'vues/pieddepage.php';
	}

	function modifier_profil() {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';

		if (isset($_POST['pseudo']) && isset($_POST['mail'])) {
			$message = '';
			$effectuer_requete = false;
			$nouveau_pseudo = $_SESSION['utilisateur']->pseudo;
			$nouveau_mail = $_SESSION['utilisateur']->mail;

			$utilisateur_manager = new UtilisateurManager($this->bdd);
			$dispo = $utilisateur_manager->verifier_dispo_pseudo_mail($_POST['pseudo'], $_POST['mail']);

			if ($_POST['pseudo'] != $nouveau_pseudo) {
				if ($dispo['pseudo_dispo']) {
					$message .= '<p>Le pseudo a été modifié</p>';
					$nouveau_pseudo = $_POST['pseudo'];
					$effectuer_requete = true;
				}
				else
					$message .= '<p>Pseudo non disponible</p>';
			}

			if ($_POST['mail'] != $nouveau_mail) {
				if ($dispo['mail_dispo']) {
					$message .= '<p>Le mail a été modifié</p>';
					$nouveau_mail = $_POST['mail'];
					$effectuer_requete = true;
				}
				else
					$message .= '<p>Mail non disponible</p>';
			}

			if ($effectuer_requete) {
				$utilisateur_manager->changer_pseudo_mail($_SESSION['utilisateur']->id, $nouveau_pseudo, $nouveau_mail);
				$_SESSION['utilisateur'] = $utilisateur_manager->obtenir_utilisateur($_SESSION['utilisateur']->id);
			}
		}

		include 'vues/utilisateur/modifier_profil.php';
		include 'vues/pieddepage.php';
	}

	function modifier_mot_de_passe() {
		include 'vues/entete.php';

		if (isset($_POST['ancien_mot_de_passe']) && isset($_POST['nouveau_mot_de_passe_1']) && isset($_POST['nouveau_mot_de_passe_2'])) {
			// Vérifier que l'ancien mot de passe est le bon
			$utilisateur_manager = new UtilisateurManager($this->bdd);
			$mot_de_passe = $utilisateur_manager->obtenir_mot_de_passe($_SESSION['utilisateur']->id);

			if (!password_verify($_POST['ancien_mot_de_passe'], $mot_de_passe))
				$message = 'Ancien mot de passe incorrect';
			elseif ($_POST['nouveau_mot_de_passe_1'] != $_POST['nouveau_mot_de_passe_2'])
				$message = 'Les mots de passe sont différents';
			else {
				$utilisateur_manager->changer_mot_de_passe($_SESSION['utilisateur']->id, password_hash($_POST['nouveau_mot_de_passe_1'], PASSWORD_DEFAULT));
				$message = 'Mot de passe modifié';
			}
		}

		include 'vues/utilisateur/modifier_mot_de_passe.php';
		include 'vues/pieddepage.php';
	}

	function ajouter_utilisateur() {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';

		if (isset($_POST['pseudo']) && isset($_POST['mail_1']) && isset($_POST['mail_2'])) {

			if ($_POST['mail_1'] != $_POST['mail_2'])
				$message = '<p>Les mails sont différents</p>';
			else {
				$utilisateur_manager = new UtilisateurManager($this->bdd);
				$dispo = $utilisateur_manager->verifier_dispo_pseudo_mail($_POST['pseudo'], $_POST['mail_1']);

				if (!$dispo['pseudo_dispo'])
					$message = '<p>Pseudo non disponible</p>';
				elseif (!$dispo['mail_dispo'])
					$message = '<p>Mail non disponible</p>';
				else {
					$message = '<p>Compte créé</p>';

					$mot_de_passe = uniqid();
					$mot_de_passe_crypte = password_hash($mot_de_passe, PASSWORD_DEFAULT);

					$id_utilisateur_cree = $utilisateur_manager->ajouter_utilisateur($_POST['pseudo'], $_POST['mail_1'], $mot_de_passe_crypte);

					if ($id_utilisateur_cree) {
						$objet = 'Création de compte sur le site projet NFA021';
						$contenu = '<p>Votre compte sur le site projet NFA021 vient d\'être créé.</p>
							<p>Voici le mot de passe pour vous connecter : '.$mot_de_passe.'<br />
							Il est fortement conseillé d\'en changer dès votre première connexion.</p>
							<p><a href="http://localhost/nfa021/admin.php">Accès au site</a></p>';
						Mail::envoyer_mail($_POST['mail_1'], $objet, $contenu);

						$utilisateur_manager->ajouter_droits($id_utilisateur_cree);				
					}
				}
			}
		}

		include 'vues/utilisateur/ajouter_utilisateur.php';
		include 'vues/pieddepage.php';
	}

	function afficher_liste_utilisateurs($page = 1) {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';

		$page = (int)$page;
		if ($page <= 0)
			$utilisateurs = [];
		else {
			$utilisateur_manager = new UtilisateurManager($this->bdd);
			// Position du 1er utilisateur = (n° page - 1) × nombre d'articles par page
			$utilisateurs = $utilisateur_manager->obtenir_liste_utilisateurs(($page - 1) * NOMBRE_UTILISATEURS_PAR_PAGE);

			// Obtenir les numéros de page pour la navigation
			$numeros_pages = ArticleControleur::obtenir_numeros_pages($page, $utilisateur_manager->nombre_utilisateurs());
		}

		include 'vues/utilisateur/affcher_liste.php';
		include 'vues/pieddepage.php';
	}

	function afficher_utilisateur($id) {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';
		
		// Récupérer les informations de l'utilisateur
		$utilisateur_manager = new UtilisateurManager($this->bdd);
		$utilisateur = $utilisateur_manager->obtenir_utilisateur($id);

		if (!$utilisateur)
			include 'vues/utilisateur/aucun_utilisateur_admin.php';
		else {
			$droits = $utilisateur_manager->obtenir_droits($id);
			$message = '';

			// Modification du pseudo et du mot de passe si demandé
			if (isset($_POST['pseudo']) && isset($_POST['mail'])) {
				$nouveau_pseudo = $utilisateur->pseudo;
				$nouveau_mail = $utilisateur->mail;
				$effectuer_requete = false;

				$dispo = $utilisateur_manager->verifier_dispo_pseudo_mail($_POST['pseudo'], $_POST['mail']);

				if ($_POST['pseudo'] != $nouveau_pseudo) {
					if ($dispo['pseudo_dispo']) {
						$message .= '<p>Le pseudo a été modifié</p>';
						$nouveau_pseudo = $_POST['pseudo'];
						$effectuer_requete = true;
					}
					else
						$message .= '<p>Pseudo non disponible</p>';
				}

				if ($_POST['mail'] != $nouveau_mail) {
					if ($dispo['mail_dispo']) {
						$message .= '<p>Le mail a été modifié</p>';
						$nouveau_mail = $_POST['mail'];
						$effectuer_requete = true;
					}
					else
						$message .= '<p>Mail non disponible</p>';
				}

				if ($effectuer_requete) {
					$utilisateur_manager->changer_pseudo_mail($utilisateur->id, $nouveau_pseudo, $nouveau_mail);
					$utilisateur->pseudo = $nouveau_pseudo;
					$utilisateur->mail = $nouveau_mail;
				}
			}

			// Modification des droits si demandé
			elseif (isset($_POST[0]) && isset($_POST[1]) && isset($_POST[2]) && isset($_POST[3])) {
				$types_droits = [Utilisateur::MODERATEUR, Utilisateur::CONTRIBUTEUR, Utilisateur::SANS_DROIT];
				$effectuer_requete = false;

				$nouveaux_droits[0] = $droits[0];
				if ($_POST[0] != $droits[0]) {
					if ($_POST[0] == Utilisateur::ADMIN || $_POST[0] == Utilisateur::SANS_DROIT) {
						$nouveaux_droits[0] = $_POST[0];
						$effectuer_requete = true;
					}
					else {
						$message .= '<p>Valeur non trouvée, arrêtez de faire joujou avec ce formulaire</p>';
					}
				}

				for ($i = 1 ; $i < 4 ; $i++) {
					$nouveaux_droits[$i] = $droits[$i];

					if ($_POST[$i] != $droits[$i]) {
						if (in_array($_POST[$i], $types_droits)) {
							$nouveaux_droits[$i] = $_POST[$i];
							$effectuer_requete = true;
						}
						else
							$message .= '<p>Valeur non trouvée, arrêtez de faire joujou avec ce formulaire</p>';
					}
				}

				if ($effectuer_requete) {
					$utilisateur_manager->modifier_droits($utilisateur->id, $nouveaux_droits);
					$droits = $nouveaux_droits;
					$message .= '<p>Droits mis à jour</p>';
				}
			}

			include 'vues/utilisateur/afficher_utilisateur.php';
		}


		include 'vues/pieddepage.php';
	}
}
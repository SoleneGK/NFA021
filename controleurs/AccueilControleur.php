<?php

class AccueilControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	function index() {
		include 'vues/entete.php';
		include 'vues/accueil/index.php';
		include 'vues/pieddepage.php';
	}

	function afficher_menu_connexion() {
		include 'vues/entete.php';
		// Afficher un message d'erreur en cas de tentative de connexion échouée
		if (isset($_POST['mail_connexion']) && isset($_POST['connexion_mot_de_passe']))
			include 'vues/accueil/echec_connexion.php';
		include 'vues/accueil/menu_connexion.php';
		include 'vues/pieddepage.php';
	}

	function afficher_demander_mot_de_passe_perdu() {
		include 'vues/entete.php';

		if (isset($_POST['mail_mdp_perdu'])) {
			include 'vues/accueil/mdp_perdu_envoi_mail.php';

			$utilisateurManager = new UtilisateurManager($this->bdd);

			// Générer un code, et lui mettre 24h de validité
			$code = md5(uniqid());
			$date_expiration = time() + 60 * 60 * 24;

			// Enregistrer ces codes en bdd et vérifier qu'un utilisateur avec ce mail existe
			$mail_existe = $utilisateurManager->modifier_code_recuperation($_POST['mail_mdp_perdu'], $code, $date_expiration);
			if ($mail_existe) {
				$objet = 'Mot de passe perdu';
				$contenu = '<p>Code à fournir pour modifier le mot de passe : '.$code.'<br />Il est valable 24h.</p><p><a href="http://localhost/nfa021/admin.php?mot_de_passe_perdu&mail='.$_POST['mail_mdp_perdu'].'&code='.$code.'">Il suffit de cliquer sur ce lien</a></p>';
				Mail::envoyer_mail($_POST['mail_mdp_perdu'], $objet, $contenu);
			}

		}
		else {
			include 'vues/accueil/mdp_perdu_entrer_mail.php';
		}

		include 'vues/pieddepage.php';
	}

	function afficher_modifier_mot_de_passe_perdu() {
		// Récupérer les informations correspondants à l'email fourni
		$utilisateurManager = new UtilisateurManager($this->bdd);
		$infos = $utilisateurManager->obtenir_code_recuperation($_GET['mail']);

		include 'vues/entete.php';

		// S'il n'y a pas d'utilisateur avec ce mail ou que le code n'est pas bon
		if (!$infos || $infos['code_recuperation'] != $_GET['code'])
			include 'vues/accueil/infos_recuperation_incorrectes.php';
		elseif ($infos['date_expiration_code'] < time())
			include 'vues/accueil/infos_recuperation_expirees.php';
		// Si un mot de passe a été saisi avec le formulaire
		elseif (isset($_POST['mot_de_passe_1']) && isset($_POST['mot_de_passe_2'])) {
			if ($_POST['mot_de_passe_1'] == $_POST['mot_de_passe_2']) {
				$utilisateurManager->changer_mot_de_passe($infos['id'], password_hash($_POST['mot_de_passe_1'], PASSWORD_DEFAULT));
				$utilisateurManager->modifier_code_recuperation($_GET['mail'], null, null);
				include 'vues/accueil/mdp_perdu_change.php';
				include 'vues/accueil/menu_connexion.php';
			}
			else {
				include 'vues/accueil/mpd_perdu_differents.php';
				include 'vues/accueil/mdp_perdu_saisir_mdp.php';
			}
		}
		else
			include 'vues/accueil/mdp_perdu_saisir_mdp.php';

		include 'vues/pieddepage.php';
	}

	function afficher_accueil_admin() {
		include 'vues/entete.php';
		include 'vues/accueil/index_admin.php';
		include 'vues/pieddepage.php';
	}
}
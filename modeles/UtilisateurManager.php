<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UtilisateurManager {
	// Codes numériques associés aux différentes sections du site en bdd
	const TOUT = 0;
	const PHOTOS = 1;
	const POLITIQUE = 2;
	const VOYAGES = 3;

	// Codes numériques associés aux différents types de droits en bdd
	const ADMIN = 0;
	const MODERATEUR = 10;
	const CONTRIBUTEUR = 20;
	const SANS_DROIT = 30;

	public $bdd;

	function __construct() {
		$this->bdd = Bdd::Connexion();
	}

	// Vérifier qu'il n'existe pas d'enregisterement de la table utilisateurs ayant la valeur $valeur dans le champ $champ
	function verifier_libre ($champ, $valeur) {
		$req = 'SELECT COUNT(id) FROM utilisateurs WHERE '.$champ.' = :valeur';
		$req = $this->bdd->prepare($req);
		$req->bindValue('valeur', $valeur);
		$req->execute();
		$reponse = $req->fetch(PDO::FETCH_NUM);

		if ($reponse[0] == 0)
			$resultat = true;
		else
			$resultat = false;

		return $resultat;
	}

	// Envoyer un mail à un utilisateur
	private function envoyer_mail($adresse_mail, $objet, $contenu, $contenu_sans_html) {
		require 'outils/PHPMailer/Exception.php';
		require 'outils/PHPMailer/PHPMailer.php';
		require 'outils/PHPMailer/SMTP.php';

		require 'outils/mail_data.php';

		$mail = new PHPMailer(true); 

		//Server settings
		//$mail->SMTPDebug = 2;
		$mail->isSMTP();
		$mail->Host = 'ssl0.ovh.net';
		$mail->SMTPAuth = true;
		$mail->Username = MAIL_USERNAME;
		$mail->Password = MAIL_PASSWORD;
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;

		//Recipients
		$mail->setFrom(MAIL_USERNAME, 'Projet NFA021');
		$mail->addAddress($adresse_mail);
		$mail->addReplyTo(MAIL_USERNAME, 'Projet NFA021');

		//Content
		$mail->isHTML(true);
		$mail->Subject = utf8_decode($objet);

		$mail->Body = utf8_decode($contenu);
		$mail->AltBody = utf8_decode($contenu_sans_html);

		$mail->send();
	}

	/* Ajouter un utilisateur
	 * Renvoie un message d'erreur ou l'id de l'utilisateur créé
	*/
	function ajouter ($pseudo, $mail_1, $mail_2) {
		// Vérifications préalables : champs non vides, pseudo et mail non utilisés
		if (empty($pseudo)) {
			$reponse = 'PSEUDO_VIDE';
		}
		elseif (empty($mail_1)) {
			$reponse = 'MAIL_VIDE';
		}
		elseif ($mail_1 != $mail_2)
			$reponse = 'MAILS_DIFFERENTS';
		elseif (!$this->verifier_libre('pseudo', $pseudo)) {
			$reponse = 'PSEUDO_UTILISE';
		}
		elseif (!$this->verifier_libre('mail', $mail_1)) {
			$reponse = 'MAIL_UTILISE';
		}
		else {
			// Génération d'un mot de passe aléatoire
			$mot_de_passe = uniqid();
			var_dump($mot_de_passe);
			$mot_de_passe_crypté = password_hash($mot_de_passe, PASSWORD_DEFAULT);

			//Création du compte en bdd
			$req = 'INSERT INTO utilisateurs (pseudo, mot_de_passe, mail) VALUES (:pseudo, :mot_de_passe, :mail)';
			$req = $this->bdd->prepare($req);
			$req->bindValue('pseudo', $pseudo);
			$req->bindValue('mot_de_passe', $mot_de_passe_crypté);
			$req->bindValue('mail', $mail_1);
			$req->execute();

			// S'il y a eu une erreur à l'ajout en base de données
			if (!$req)
				$reponse = 'ERREUR_CREATION';
			else {
				// Récupérer l'id de la ligne ajoutée
				$reponse = $this->bdd->lastInsertId();

				// Envoyer un mail contenant le mot de passe
				$mail_objet = 'Création de compte sur le site projet NFA021';
				$contenu_mail = '<p>Votre compte sur le site projet NFA021 vient d\'être créé.</p>
					<p>Voici le mot de passe pour vous connecter : '.$mot_de_passe.'<br />
					Il est fortement conseillé d\'en changer dès votre première connexion.</p>';
				$contenu_mail_sans_html = 'Votre compte sur le site projet NFA021 vient d\'être créé.
					Voici le mot de passe pour vous connecter : '.$mot_de_passe.'
					Il est fortement conseillé d\'en changer dès votre première connexion.';
				$this->envoyer_mail($mail_1, $mail_objet, $contenu_mail, $contenu_mail_sans_html);

				// Ajout des droits par défaut : aucun droit sur toutes les sessions
				$req = 'INSERT INTO liste_droits (id_utilisateur, id_section, type_droit) VALUES (:id, :section, '.self::SANS_DROIT.')';
				$req = $this->bdd->prepare($req);
				$req->bindValue('id', $reponse, PDO::PARAM_INT);

				for ($i = 0 ; $i < 4 ; $i++) {
					$req->bindValue('section', $i, PDO::PARAM_INT);
					$req->execute();
				}
			}
		}

		return $reponse;
	}

	// Obtenir la liste des droits d'un utilisateur sous forme de tableau associatif
	function obtenir_droits($id) {
		$req = 'SELECT id_section, type_droit FROM liste_droits WHERE id_utilisateur = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();

		$droits = [];
		foreach($req->fetchAll(PDO::FETCH_ASSOC) as $droit)
			$droits[$droit['id_section']] = $droit['type_droit'];

		return $droits;
	}

	// Obtenir les informations d'un utilisateur à partir de son id
	function afficher ($id) {
		$req = 'SELECT pseudo, mail FROM utilisateurs WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		// Vérifier qu'il y a bien un utilisateur avec cette id, si oui créer un objet
		if (!$req)
			$utilisateur = false;
		else
			$utilisateur = new Utilisateur($id, $req['pseudo'], $req['mail'], $this->obtenir_droits($id));

		return $utilisateur;
	}

	// Obtenir la liste complète des utilisateurs
	function afficherListe () {
		$req = 'SELECT id, pseudo, mail FROM utilisateurs';
		$req = $this->bdd->prepare($req);
		$req->execute();

		$utilisateurs = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
			$u = new Utilisateur($ligne['id'], $ligne['pseudo'], $ligne['mail'], $this->obtenir_droits($ligne['id']));
			$utilisateurs[] = $u;
		}

		return $utilisateurs;
	}

	// Connexion d'un utilisateur qui fournit son pseudo et son mot de passe
	function connexion ($pseudo, $mot_de_passe) {
		// Récupérer les informations correspondant au pseudo entré
		$req = 'SELECT id, mot_de_passe, mail FROM utilisateurs WHERE pseudo = :pseudo';
		$req = $this->bdd->prepare($req);
		$req->bindValue('pseudo', $pseudo);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		// Vérifier qu'un utilisateur a été trouvé dans la bdd
		if (!$req)
			$reponse = false;
		// Comparer les mots de passe
		elseif (!password_verify($mot_de_passe, $req['mot_de_passe']))
			$reponse = false;
		else {
			// Créer un objet utilisateur et le stocker en variable de session
			$utilisateur = new Utilisateur($req['id'], $pseudo, $req['mail']);
			
			$_SESSION['utilisateur'] = $utilisateur;

			$reponse = true;
		}

		return $reponse;
	}

	// Déconnexion de l'utilisateur
	function deconnexion () {
		unset($_SESSION['utilisateur']);
	}

	// Modifier mot de passe
	function modifier_mot_de_passe ($ancien_mot_de_passe, $nouveau_mot_de_passe_1, $nouveau_mot_de_passe_2) {
		// Vérifier qu'un utilisateur est connecté
		if (!isset($_SESSION['utilisateur']))
			$reponse = 'UTILISATEUR_NON_CONNECTE';
		else {
			// Vérifier que le mot de passe est correct
			$req = 'SELECT mot_de_passe FROM utilisateurs WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('id', $_SESSION['utilisateur']->id);
			$req->execute();
			$req = $req->fetch(PDO::FETCH_ASSOC);

			if (!password_verify($ancien_mot_de_passe, $req['mot_de_passe']))
				$reponse = 'ANCIEN_MPD_INCORRECT';
			elseif ($nouveau_mot_de_passe_1 != $nouveau_mot_de_passe_2)
				$reponse = 'MDP_DIFFERENTS';
			else {
				// Modifier le mot de passe en base de données
				$req = 'UPDATE utilisateurs SET mot_de_passe = :mot_de_passe WHERE id = :id';
				$req = $this->bdd->prepare($req);
				$req->bindValue('mot_de_passe', password_hash($nouveau_mot_de_passe_1, PASSWORD_DEFAULT));
				$req->bindValue('id', $_SESSION['utilisateur']->id);
				$reponse = $req->execute();

				if ($reponse)
					$reponse = 'OK';
				else
					$reponse = 'ERREUR_MODIFICATION';
			}
		}

		return $reponse;
	}

	// Modifie un droit d'un utilisateur, renvoie la réussite ou l'échec de la modification de la base de données
	function modifier_droit ($utilisateur, $section, $droit) {
		$req = 'UPDATE liste_droits SET type_droit = :droit WHERE id_utilisateur = :utilisateur AND id_section = :section';
		$req = $this->bdd->prepare($req);
		$req->bindValue('droit', $droit, PDO::PARAM_INT);
		$req->bindValue('utilisateur', $utilisateur, PDO::PARAM_INT);
		$req->bindValue('section', $section, PDO::PARAM_INT);
		$status = $req->execute();
		return $status;
	}
}
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
	static function envoyer_mail($adresse_mail, $objet, $contenu) {
		try {
			require_once 'outils/PHPMailer/Exception.php';
			require_once 'outils/PHPMailer/PHPMailer.php';
			require_once 'outils/PHPMailer/SMTP.php';

			require_once 'outils/mail_data.php';

			$mail = new PHPMailer(true); 

			// Paramètres du serveur
			//$mail->SMTPDebug = 2;
			$mail->isSMTP();
			$mail->Host = 'ssl0.ovh.net';
			$mail->SMTPAuth = true;
			$mail->Username = MAIL_USERNAME;
			$mail->Password = MAIL_PASSWORD;
			$mail->SMTPSecure = 'tls';
			$mail->Port = 587;

			// En-tête
			$mail->setFrom(MAIL_USERNAME, 'Empreinte');
			$mail->addAddress($adresse_mail);
			$mail->addReplyTo(MAIL_USERNAME, 'Empreinte');

			// Contenu
			$mail->isHTML(true);
			$mail->Subject = utf8_decode($objet);

			$mail->Body = utf8_decode($contenu);

			$mail->send();
			$reponse = true;
		}
		catch (Exception $e) {
			$reponse = false;
		}

		return $reponse;
	}
}
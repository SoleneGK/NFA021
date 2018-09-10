<p id="chemin_page">Accueil</p>

<p>
	Bienvenue sur les pages d'administration du site.<br>
	Avec vos droits actuels, vous pouvez :
	<ul>
		<li>Parcourir tout le contenu du site</li>
		<li>Afficher et modifier votre profil</li>
	</ul>


<?php
// Photos
if ($_SESSION['utilisateur']->droits[Section::TOUT] != Utilisateur::ADMIN && $_SESSION['utilisateur']->droits[Section::PHOTOS] == Utilisateur::CONTRIBUTEUR):
?>
	<span class="accueil_categories">Photos</span>
	<ul>
		<li>Ajouter des photos</li>
		<li>Modifier les photos que vous avez ajoutées</li>
	</ul>

<?php
endif;

if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::MODERATEUR):
?>
	
	<span class="accueil_categories">Photos</span>
	<ul>
		<li>Ajouter des photos</li>
		<li>Modifier les photos</li>
		<li>Supprimer des photos</li>
	</ul>

<?php
endif;


// Catégories de photos
if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN):
?>
	<span class="accueil_categories">Catégories de photos</span>
	<ul>
		<li>Ajouter une catégorie</li>
		<li>Modifier les catégories</li>
		<li>Supprimer des catégories</li>
	</ul>

<?php
endif;


// Articles politiques
if ($_SESSION['utilisateur']->droits[Section::TOUT] != Utilisateur::ADMIN && $_SESSION['utilisateur']->droits[Section::POLITIQUE] == Utilisateur::CONTRIBUTEUR):
?>
	<span class="accueil_categories">Articles politique</span>
	<ul>
		<li>Ajouter un article</li>
		<li>Modifier les articles que vous avez ajoutés</li>
	</ul>

<?php
endif;

if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::MODERATEUR):
?>
	
	<span class="accueil_categories">Articles politique</span>
	<ul>
		<li>Ajouter un article</li>
		<li>Modifier les articles</li>
		<li>Supprimer des articles</li>
		<li>Modifier et supprimer les commentaires</li>
	</ul>

<?php
endif;


// Articles voyage
if ($_SESSION['utilisateur']->droits[Section::TOUT] != Utilisateur::ADMIN && $_SESSION['utilisateur']->droits[Section::VOYAGE] == Utilisateur::CONTRIBUTEUR):
?>
	<span class="accueil_categories">Articles voyage</span>
	<ul>
		<li>Ajouter un article</li>
		<li>Modifier les articles que vous avez ajoutés</li>
	</ul>

<?php
endif;

if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::VOYAGE] <= Utilisateur::MODERATEUR):
?>
	
	<span class="accueil_categories">Articles voyage</span>
	<ul>
		<li>Ajouter un article</li>
		<li>Modifier les articles</li>
		<li>Supprimer des articles</li>
		<li>Modifier et supprimer les commentaires</li>
	</ul>

<?php
endif;


if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN):
?>
	<!-- Pays -->
	<span class="accueil_categories">Pays</span>
	<ul>
		<li>Ajouter un pays</li>
		<li>Modifier les pays</li>
		<li>Supprimer des pays</li>
	</ul>

	<!-- Utilisateurs -->
	<span class="accueil_categories">Utilisateurs</span>
	<ul>
		<li>Afficher la liste des utilisateurs</li>
		<li>Ajouter un utilisateur</li>
		<li>Modifier le profil et les droits des utilisateurs</li>
	</ul>

<?php
endif;

?>
</p>

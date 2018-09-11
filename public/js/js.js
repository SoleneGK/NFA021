$(function() {
	// Demander une confirmation avant d'envoyer une demande de suppression d'un élément
	$('form:not(.supprimer_commentaire_form)').has('input[class~=supprimer]').each(function() {
		$(this).on('submit', function() {
			if (!confirm('Supprimer ?'))
				event.preventDefault();
		})
	});

	// Commentaires
	// Afficher les champs de modification lors du clic sur le bouton "Modifier"
	$('.afficher_modification').each(function() {
		var parent = $(this).parent();

		$(this).on('click', function(event) {
			event.preventDefault();

			// Masquer le commentaire et le bouton, afficher les éléments du formulaire
			$(this).hide();
			parent.children('.modifier_commentaire_elements').show();
			parent.children('.p_commentaire').hide();
		})
	});

	// Envoi du formulaire : données envoyées par ajax
	$('.modifier_commentaire_form').each(function() {
		$(this).submit(function(event) {
			event.preventDefault();

			element = $(this);

			var data = {
				id_commentaire: $(this).find('[name=id_commentaire]').val(),
				id_section: $(this).find('[name=id_section]').val(),
				id_utilisateur: $(this).find('[name=id_utilisateur]').val(),
				pseudo: $(this).find('[name=pseudo_commentaire]').val(),
				contenu: $(this).find('[name=contenu_commentaire]').val()
			}

			$.post('ajax/modifier_commentaire.php', data, function(retour) {
				if (retour.status === true) {
					// Masquer le formulaire
					element.find('.afficher_modification').show();
					element.find('.modifier_commentaire_elements').hide();
					element.find('.p_commentaire').show();

					// Mettre à jour le contenu des éléments
					element.find('input[class~=pseudo_commentaire]').val(retour.pseudo);
					element.find('span[class~=pseudo_commentaire]').text(retour.pseudo);
					element.find('input[class~=contenu_commentaire]').val(retour.contenu);
					element.find('span[class~=contenu_commentaire]').text(retour.contenu);
				}
			});	
		})
	});
	
	// Suppression d'un commentaire
	$('.supprimer_commentaire_form').each(function() {
		var parent = $(this).parent();

		$(this).submit(function(event) {
			event.preventDefault();

			if (confirm('Supprimer ?')) {
				var data = {
					id_commentaire: $(this).children('[name=id_commentaire]').val(),
					id_section: $(this).children('[name=id_section]').val()
				};
				
				$.post('ajax/supprimer_commentaire.php', data, function(retour) {
					if (retour.status === true) {

						parent.remove();
					}
				});
			}
		})
	});
});


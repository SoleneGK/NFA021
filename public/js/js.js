$(function() {
	$('form').has('input[class~=supprimer]').submit(function() {
		if (!confirm('Supprimer ?'))
			event.preventDefault();
	});
});
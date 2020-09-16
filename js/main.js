// Script de commande d'ouverture, après 1500ms
window.addEventListener('load', function () {
	
	// Si on est sur la Home page
	if ($('body').hasClass('home')) {
		$("#PopupModal").delay(1500).queue(function(next){
			$(this).modal('show');
		    next();
		});
	
	}

});
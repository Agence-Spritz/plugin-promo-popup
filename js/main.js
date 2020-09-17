// Script de commande d'ouverture, après 1500ms
window.addEventListener('load', function () {
	
	// Si on est sur la Home page
	if (jQuery('body').hasClass('home')) {
		jQuery("#PopupModal").delay(1500).queue(function(next){
			jQuery(this).modal('show');
		    next();
		});
	
	}

});
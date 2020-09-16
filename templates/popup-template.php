<?php 
	
/* ========================================= 
	Templates : création de la Popup en HTML
========================================= */

		
// On créée le SHortcode
function promo_popup_create_shortcode($atts) {
	
		$Content  = '<div class="modal fade" id="PopupModal" tabindex="-1" role="dialog" aria-labelledby="PopupModalCenterTitle" aria-hidden="true">';
		$Content .= '  	<div class="modal-dialog modal-dialog-centered" role="document">';
		$Content .= '      <div class="modal-content">';
		$Content .= '        <div class="modal-header">';
		$Content .= '          <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>';
		$Content .= '          <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
		$Content .= '            <span aria-hidden="true">&times;</span>';
		$Content .= '          </button>';
		$Content .= '        </div>';
		$Content .= '        <div class="modal-body">';
		$Content .= 			get_option("text_popup");
		$Content .= '        </div>';
		$Content .= '        <div class="modal-footer">';
		$Content .= '          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
		$Content .= '        </div>';
		$Content .= '      </div>';
		$Content .= '   </div>';
		$Content .= '</div>';
	 
    return $Content;
}

add_shortcode('promo-popup-shortcode', 'promo_popup_create_shortcode');
	
?>
<?php 
	
/* ========================================= 
	Templates : création de la Popup en HTML
========================================= */

		
// On créée le SHortcode
function promo_popup_create_shortcode($atts) {
	
		$Content  = '<div class="modal fade" id="PopupModal" tabindex="-1" role="dialog" aria-hidden="true">';
		$Content .= '  	<div class="modal-dialog modal-dialog-centered" role="document">';
		$Content .= '      <div class="modal-content" style="background: url('.get_option("fond-popup").'); ">';

		$Content .= '          <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
		$Content .= '            <span aria-hidden="true">&times;</span>';
		$Content .= '          </button>';

		$Content .= '        <div class="modal-body">';
		$Content .= '        	<div class="logo">';
		$Content .= '        		<img src="'.get_option("logo-popup").'" alt="'.bloginfo("name").'" />';
		$Content .= '        	</div>';
		$Content .= '        	<div class="text">';
		$Content .= 				get_option("text_popup");
		$Content .= '        	</div>';
		$Content .= '        	<div class="code">';
		$Content .= 				get_option("code_promo");
		$Content .= '        	</div>';

		$Content .= '        </div>';
		$Content .= '      </div>';
		$Content .= '   </div>';
		$Content .= '</div>';
	 
    echo $Content;
}

	
?>
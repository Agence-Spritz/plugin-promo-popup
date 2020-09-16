<?php
/*
Plugin Name: Promo popup
Plugin URI: http://www.agence-spritz.com.com/
Description: Plugin permettant la création d'une popup promotionnelle
Version: 1.0
Author: Agence Spritz
Author URI: http://www.agence-spritz.com.com/
License: GPLv2
*/

// Requiert Bootstrap, Woocommerce

if ( ! defined( 'ABSPATH' ) ) { 
    exit;
}

define( 'PROMO_POPUP_VERSION', '1.0' );
define( 'PROMO_POPUP_PLUGIN_ABSPATH', dirname( __FILE__ ) );
define( 'PROMO_POPUP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once( PROMO_POPUP_PLUGIN_ABSPATH . '/templates/popup-template.php');
require_once( PROMO_POPUP_PLUGIN_ABSPATH . '/inc/functions.php');

/* ========================================= 
	Administration : Options générales	
========================================= */

// à l'initialisation de l'administration
// on informe WordPress des options de notre thème
add_action( 'admin_init', 'PopupRegisterSettings' );

// Enregistrement des options
function PopupRegisterSettings( )
{
	register_setting( 'popup_options', 'activation_popup' );
	register_setting( 'popup_options', 'text_popup' );
	register_setting( 'popup_options', 'code_promo' );
	register_setting( 'popup_options', 'fond-popup' );
}

// Déclaration des Scripts
function add_admin_scripts()
{
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'add-admin-scripts', plugin_dir_url( __FILE__ ) . 'js/main.js', array( 'jquery' ) );
	
	// Feuille de style de la popup
	wp_enqueue_style( 'popup_options_styles', plugin_dir_url( __FILE__ ) . 'css/styles.css', array('spritz_bootstrap'), PROMO_POPUP_VERSION, 'all' );

}
add_action('wp_enqueue_scripts', 'add_admin_scripts');

// Déclaration feuille de style admin
function popup_admin_styles(){
    
        wp_enqueue_style( 'admin_options_styles', plugin_dir_url( __FILE__ ) . 'css/admin_styles.css' );
    
}
add_action( 'admin_print_styles', 'popup_admin_styles' );



// Augmentation des droits pour les Editeurs
// get the the role object
$role_object = get_role( 'editor' );

// add $cap capability to this role object
$role_object->add_cap( 'edit_theme_options' );

// Ici on autorise le rôle qui a edit_theme_options a enregistrer les options
add_filter( 'option_page_capability_spritz_options', 'add_popup_options_capability' );
function add_popup_options_capability( $cap ) {
    return 'edit_theme_options';
}

// On Crée le menu, on ajoute le lien dans la sidebar
add_action( 'admin_menu', 'PopupMenu' );

function PopupMenu( )
{
	add_menu_page(
		'Configuration de la Popup', // le titre de la page
		'Popup promotionnelle',            // le nom de la page dans le menu d'admin
		'edit_theme_options',        // le rôle d'utilisateur requis pour voir cette page
		'popup-page',        // un identifiant unique de la page
		'PopupSettingsPage'   // le nom d'une fonction qui affichera la page
	);
}

// Création HTML de la page de configuration
function PopupSettingsPage( )
{
?>
	<div class="wrap">
		<h2>Configuration générale</h2>
		<hr>
		
		<p style="font-weight: bold">Bienvenue sur le panneau de gestion du module Promo Popup (by Spritz) <br />
		Une fois activée, la popup se lancera automatiquement à l'ouverture de la page d'accueil du site, après 1,5sd.</p>
		
		<?php 
			if ( class_exists( 'WooCommerce' ) ) {
	
				$code = 'test123';
				$coupon = new WC_Coupon($code);
				$coupon_post = get_post($coupon->id);
				$coupon_data = array(
				    'id' => $coupon->id,
				    'code' => $coupon->code,
				    'type' => $coupon->type,
				    'created_at' => $coupon_post->post_date_gmt,
				    'updated_at' => $coupon_post->post_modified_gmt,
				    'amount' => wc_format_decimal($coupon->coupon_amount, 2),
				    'individual_use' => ( 'yes' === $coupon->individual_use ),
				    'product_ids' => array_map('absint', (array) $coupon->product_ids),
				    'exclude_product_ids' => array_map('absint', (array) $coupon->exclude_product_ids),
				    'usage_limit' => (!empty($coupon->usage_limit) ) ? $coupon->usage_limit : null,
				    'usage_count' => (int) $coupon->usage_count,
				    'expiry_date' => (!empty($coupon->expiry_date) ) ? date('Y-m-d', $coupon->expiry_date) : null,
				    'enable_free_shipping' => $coupon->enable_free_shipping(),
				    'product_category_ids' => array_map('absint', (array) $coupon->product_categories),
				    'exclude_product_category_ids' => array_map('absint', (array) $coupon->exclude_product_categories),
				    'exclude_sale_items' => $coupon->exclude_sale_items(),
				    'minimum_amount' => wc_format_decimal($coupon->minimum_amount, 2),
				    'maximum_amount' => wc_format_decimal($coupon->maximum_amount, 2),
				    'customer_emails' => $coupon->customer_email,
				    'description' => $coupon_post->post_excerpt,
				);
				
				$usage_left = $coupon_data['usage_limit'] - $coupon_data['usage_count'];
				
				if ($usage_left > 0) {
				    echo "<div class='alert alert-success' role='alert'>Code promo valide</div>";
				} 
				else {
				    echo "<div class='alert alert-danger' role='alert'>Code promo invalide</div>";
				}
			} else {
				echo "<div class='alert alert-warning' role='alert'>Attention, ce plugin requiert l'installation de Woocommerce</div>";
			}
		?>
		<hr>

		<form method="post" action="options.php">
			<?php
				// cette fonction ajoute plusieurs champs cachés au formulaire
				// pour vous faciliter le travail.
				// elle prend en paramètre le nom du groupe d'options
				// que nous avons défini plus haut.
				settings_fields( 'popup_options' );
			?>
			
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="activation_popup">Activation de la Popup</label></th>
					<td><input type="checkbox" id="activation_popup" name="activation_popup" <?php if(get_option( 'activation_popup' )==true) { echo 'checked'; } ?>>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="text_popup">Texte promotionnel<br /> <span style="font-size: 10px;">(250 caract. max)</span></label></th>
					<td>
						<textarea id="text_popup" name="text_popup" maxlength="250"><?php echo get_option( 'text_popup' ); ?></textarea>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="code_promo">Code promo<br /> <span style="font-size: 10px;">(issu de Woocommerce)</span></label></th>
					<td><input type="text" id="code_promo" name="code_promo" class="small-text" value="<?php echo get_option( 'code_promo' ); ?>" /></td>
				</tr>
				
				<?php wp_enqueue_media();?>
				<tr valign="top" >
					<th scope="row"><label for="fond-popup">Visuel fond Popup</label></th>
					<td class="image">
						
						<img style="margin-top: 10px; margin-bottom: 10px; max-width:200px;height:auto;" id="fond-popup-preview" src="<?php if(!empty(get_option( 'fond-popup' ))) { echo get_option( 'fond-popup' ); }  ?>" />
						<input type="text" name="fond-popup" id="fond-popup" class="meta_image" value="<?php if(!empty(get_option( 'fond-popup' ))) { echo get_option( 'fond-popup' ); }  ?>" />
						<input type="button" id="fond-popup-button" class="button" value="Choisir ou télécharger une image" />
							
					</td>
				</tr>
			
				<script>
				jQuery('#fond-popup-button').click(function() {
				
				    var send_attachment_bkp = wp.media.editor.send.attachment;
				
				    wp.media.editor.send.attachment = function(props, attachment) {
				
					    jQuery('#fond-popup').val(attachment.url);
						jQuery('#fond-popup-preview').attr('src',attachment.url);
					        wp.media.editor.send.attachment = send_attachment_bkp;
					    }
				
				    wp.media.editor.open();
				
				    return false;
				});
				</script>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="Mettre à jour" />
			</p>
		</form>
	</div>
<?php
}

?>
<?php
/**
 * This code adapted from the Custom Field Redirect
 * plugin by Nathan Rice, http://www.nathanrice.net/plugins
 *
 */

if(!function_exists('custom_field_redirect')) {

//Hook the redirect function into the template_redirect action
//This part actually does the redirect, if necessary

add_action('template_redirect', 'custom_field_redirect');
function custom_field_redirect() {
	//globalize vars
	global $wp_query;
	
	$redirect = isset( $wp_query->post->ID ) ? get_post_meta($wp_query->post->ID, 'redirect', true) : '';
	
	if ( !empty( $redirect ) && is_singular() ) {
		//And do the redirect.
		wp_redirect( esc_url( $redirect ), 301);
		exit();
	}
}

}
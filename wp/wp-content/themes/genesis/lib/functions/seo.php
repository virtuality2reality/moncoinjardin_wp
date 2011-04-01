<?php
/**
 * This file essentially checks for the existence of 3rd party
 * SEO plugins, and disables the Genesis SEO features if they
 * are present.
 *
 * @package Genesis
 * @author Nathan Rice
 **/

add_action('init', 'genesis_seo_compatibility_check', 15);
function genesis_seo_compatibility_check() {
	
	//	Disable all SEO functions if a popular SEO plugin is active
	if ( class_exists('All_in_One_SEO_Pack') || class_exists('HeadSpace_Plugin') || class_exists('Platinum_SEO_Pack') || defined('WPSEO_VERSION') ) {
		remove_filter('wp_title', 'genesis_default_title', 10, 3);
		remove_action('get_header', 'genesis_doc_head_control');
		remove_action('genesis_meta','genesis_seo_meta_description');
		remove_action('genesis_meta','genesis_seo_meta_keywords');
		remove_action('genesis_meta','genesis_robots_meta');
		remove_action('wp_head','genesis_canonical');
		add_action('wp_head', 'rel_canonical');
		
		remove_action('admin_menu', 'genesis_add_inpost_seo_box');
		remove_action('save_post', 'genesis_inpost_seo_save', 1, 2);
		
		remove_action('admin_init', 'genesis_add_taxonomy_seo_options');
		remove_action('edit_term', 'genesis_term_meta_save', 10, 2);
		
		remove_action('show_user_profile', 'genesis_user_seo_fields');
		remove_action('edit_user_profile', 'genesis_user_seo_fields');
		remove_action('personal_options_update', 'genesis_user_meta_save');
		remove_action('edit_user_profile_update', 'genesis_user_meta_save');
		
		remove_theme_support('genesis-seo-settings-menu');
		add_filter('pre_option_' . GENESIS_SEO_SETTINGS_FIELD, '__return_empty_array');
	}
	
	//	disable Genesis <title> generation if SEO Title Tag is active
	if (function_exists('seo_title_tag')) {
		remove_filter('wp_title', 'genesis_default_title', 10, 3);
		remove_action('genesis_title', 'wp_title');
		add_action('genesis_title', 'seo_title_tag');
	}
	
}

/**
 * Display nag for Scribe SEO Copywriting tool.
 */
add_action('admin_notices', 'genesis_scribe_nag');
function genesis_scribe_nag() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'seo-settings' )
		return;
	
	if ( class_exists('Ecordia') || get_option('genesis-scribe-nag-disabled') )
		return;
		
	printf( '<div class="updated" style="overflow: hidden;"><p class="alignleft">Have you tried our Scribe SEO software? Do keyword research, content optimization, and link building without leaving WordPress. <b>Genesis owners save over 50&#37; using the promo code FIRST when you sign up</b>. <a href="%s" target="_blank">Click here for more info</a>.</p> <p class="alignright"><a href="%s">Dismiss</a></p></div>', 'http://scribeseo.com/genesis-owners-only', admin_url( 'admin.php?page=seo-settings&amp;dismiss-scribe=true' ) );
	
}

/**
 * This function detects a query flag, and disables the Scribe nag,
 * then redirects the user back to the SEO settings page.
 */
add_action('admin_init', 'genesis_disable_scribe_nag');
function genesis_disable_scribe_nag() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'seo-settings' )
		return;
		
	if ( !isset($_REQUEST['dismiss-scribe']) || $_REQUEST['dismiss-scribe'] !== 'true' )
		return;
		
	update_option( 'genesis-scribe-nag-disabled', 1 );
	
	wp_redirect( admin_url( 'admin.php?page=seo-settings' ) );
	exit;
	
}
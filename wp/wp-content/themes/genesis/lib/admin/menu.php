<?php
/*
 * @todo Document this file
 */

//	This function adds the top-level menu
add_action('admin_menu', 'genesis_add_admin_menu');
function genesis_add_admin_menu() {
	
	global $menu;
	
	// Disable if programatically disabled
	if ( !current_theme_supports('genesis-admin-menu') ) return;
	
	// Disable if disabled for current user
	$user = wp_get_current_user();
	if ( !get_the_author_meta( 'genesis_admin_menu', $user->ID ) ) return;
	
	// Create the new separator
	$menu['58.995'] = array( '', 'manage_options', 'separator-genesis', '', 'wp-menu-separator' );
	
	// Create the new top-level Menu
	add_menu_page('Genesis', 'Genesis', 'manage_options', 'genesis', 'genesis_theme_settings_admin', PARENT_URL.'/images/genesis.gif', '58.996');
}

// This function adds the submenus
add_action('admin_menu', 'genesis_add_admin_submenus');
function genesis_add_admin_submenus() {
	
	global	$_genesis_theme_settings_pagehook,
			$_genesis_seo_settings_pagehook;
			
	if( !current_theme_supports('genesis-admin-menu') ) return;
	
	$user = wp_get_current_user();
	
	// Add "Theme Settings" submenu
	$_genesis_theme_settings_pagehook = add_submenu_page('genesis', __('Theme Settings','genesis'), __('Theme Settings','genesis'), 'manage_options', 'genesis', 'genesis_theme_settings_admin');
	
	// Add "SEO Settings" submenu
	if ( current_theme_supports('genesis-seo-settings-menu') && get_the_author_meta( 'genesis_seo_settings_menu', $user->ID ) ) {
		$_genesis_seo_settings_pagehook = add_submenu_page('genesis', __('SEO Settings','genesis'), __('SEO Settings','genesis'), 'manage_options', 'seo-settings', 'genesis_seo_settings_admin');
	}
	
	// Add "Import/Export" submenu
	if ( current_theme_supports('genesis-import-export-menu') && get_the_author_meta( 'genesis_import_export_menu', $user->ID ) ) {
		add_submenu_page('genesis', __('Import/Export','genesis'), __('Import/Export','genesis'), 'manage_options', 'genesis-import-export', 'genesis_import_export_admin');
	}
	
	// Add README.txt file submenu, if it exists
	if ( current_theme_supports('genesis-readme-menu') ) {
		$_genesis_readme_menu_pagehook = file_exists( CHILD_DIR . '/README.txt' ) ? add_submenu_page('genesis', __('README', 'genesis'), __('README', 'genesis'), 'manage_options', 'readme', 'genesis_readme_menu_admin') : null;
	}
	
}
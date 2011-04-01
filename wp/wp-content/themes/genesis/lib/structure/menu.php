<?php
/**
 * @todo document this file
 */

/**
 * The following registers the Nav Menu Locations.
 * These locations are used as places to where Nav
 * Menus can be placed/associated
 */
register_nav_menus( array(
	'primary' => __('Primary Navigation Menu', 'genesis'),
	'secondary' => __('Secondary Navigation Menu', 'genesis')
) );


add_action('genesis_after_header', 'genesis_do_nav');
/**
 * This function is responsible for displaying the "Primary Navigation" bar.
 *
 * @uses genesis_nav(), genesis_get_option(), wp_nav_menu()
 * @since 1.0
 */
function genesis_do_nav() {
	if ( genesis_get_option('nav') ) {
		
		if ( genesis_get_option('nav_type') == 'nav-menu' && function_exists('wp_nav_menu') ) {
			
			$nav = wp_nav_menu(array(
				'theme_location' => 'primary',
				'container' => '',
				'menu_class' => genesis_get_option('nav_superfish') ? 'nav superfish' : 'nav',
				'echo' => 0
			));
			
		} else {
			
			$nav = genesis_nav(array(
				'theme_location' => 'primary',
				'menu_class' => genesis_get_option('nav_superfish') ? 'nav superfish' : 'nav',
				'show_home' => genesis_get_option('nav_home'),
				'type' => genesis_get_option('nav_type'),
				'sort_column' => genesis_get_option('nav_pages_sort'),
				'orderby' => genesis_get_option('nav_categories_sort'),
				'depth' => genesis_get_option('nav_depth'),
				'exclude' => genesis_get_option('nav_exclude'),
				'include' => genesis_get_option('nav_include'),
				'echo' => false
			));
			
		}
		
		echo '<div id="nav"><div class="wrap">' . $nav . '</div></div>';
		
	}
}


add_action('genesis_after_header', 'genesis_do_subnav');
/**
 * This function  is responsible for displaying the "Secondary Navigation" bar.
 *
 * @uses genesis_nav(), genesis_get_option(), wp_nav_menu
 * @since 1.0.1
 *
 */
function genesis_do_subnav() {
	if ( genesis_get_option('subnav') ) {
		
		if ( genesis_get_option('subnav_type') == 'nav-menu' && function_exists('wp_nav_menu') ) {
			
			$subnav = wp_nav_menu(array(
				'theme_location' => 'secondary',
				'container' => '',
				'menu_class' => genesis_get_option('subnav_superfish') ? 'nav superfish' : 'nav',
				'echo' => 0
			));
			
		} else {
			
			$subnav = genesis_nav(array(
				'theme_location' => 'secondary',
				'menu_class' => genesis_get_option('subnav_superfish') ? 'nav superfish' : 'nav',
				'show_home' => genesis_get_option('subnav_home'),
				'type' => genesis_get_option('subnav_type'),
				'sort_column' => genesis_get_option('subnav_pages_sort'),
				'orderby' => genesis_get_option('subnav_categories_sort'),
				'depth' => genesis_get_option('subnav_depth'),
				'exclude' => genesis_get_option('subnav_exclude'),
				'include' => genesis_get_option('subnav_include'),
				'echo' => false
			));
		
		}
		
		echo '<div id="subnav"><div class="wrap">' . $subnav . '</div></div>';
	}
}


add_filter('genesis_nav_items', 'genesis_nav_right', 10, 2);
add_filter('wp_nav_menu_items', 'genesis_nav_right', 10, 2);
/**
 * This function filters the Primary Navigation menu items, appending
 * either RSS links, search form, twitter link, or today's date.
 *
 * @uses genesis_get_option(), get_bloginfo(), get_search_form(),
 * @since 1.0
 */
function genesis_nav_right($menu, $args) {
	
	$args = (array)$args;
	
	if ( !genesis_get_option('nav_extras_enable') || $args['theme_location'] != 'primary' )
		return $menu;
	
	if ( genesis_get_option('nav_extras') == 'rss' ) {
		$rss = '<a rel="nofollow" href="'.get_bloginfo('rss_url').'">'.__('Posts', 'genesis').'</a>';
		$rss .= '<a rel="nofollow" href="'.get_bloginfo('comments_rss2_url').'">'.__('Comments', 'genesis').'</a>';
		
		$menu .= '<li class="right rss">'.$rss.'</li>';
	}
	elseif ( genesis_get_option('nav_extras') == 'search' ) {
		// I hate output buffering, but I have no choice
		ob_start();
		get_search_form();
		$search = ob_get_clean();
		
		$menu .= '<li class="right search">'.$search.'</li>';
	}
	elseif ( genesis_get_option('nav_extras') == 'twitter' ) {
		
		$menu .= sprintf( '<li class="right twitter"><a href="%s">%s</a></li>', esc_url( 'http://twitter.com/' . genesis_get_option('nav_extras_twitter_id') ), esc_html( genesis_get_option('nav_extras_twitter_text') ) );
	
	}
	elseif ( genesis_get_option('nav_extras') == 'date' ) {
		
		$menu .= '<li class="right date">'.date_i18n(get_option('date_format')).'</li>';
		
	}
	
	return $menu;
	
}
<?php
/**
 * Outputs the structural markup for the footer
 *
 * @since 1.2
 */
add_action('genesis_footer', 'genesis_footer_markup_open', 5);
function genesis_footer_markup_open() {
	
	echo '<div id="footer"><div class="wrap">' . "\n";
	
}
add_action('genesis_footer', 'genesis_footer_markup_close', 15);
function genesis_footer_markup_close() {
	
	echo '</div><!-- end .wrap --></div><!-- end #footer -->' . "\n";
	
}


/**
 * Output the contents of the footer
 * Execute any shortcodes that might be present
 *
 * @since 1.0.1
 */
add_filter('genesis_footer_output', 'do_shortcode', 20);
add_action('genesis_footer', 'genesis_do_footer');
function genesis_do_footer() {
	
	// Build the filterable text strings. Includes shortcodes.
	$backtotop_text = apply_filters('genesis_footer_backtotop_text', '[footer_backtotop]');
	$creds_text = apply_filters('genesis_footer_creds_text', __('Copyright', 'genesis') . ' [footer_copyright] [footer_childtheme_link] &middot; [footer_genesis_link] [footer_studiopress_link] &middot; [footer_wordpress_link] &middot; [footer_loginout]');
	
	// For backward compatibility (pre-1.1 filter)
	if( apply_filters('genesis_footer_credits', FALSE) ) {
		$filtered = apply_filters('genesis_footer_credits', '[footer_childtheme_link] &middot; [footer_genesis_link] &middot; [footer_wordpress_link]');
		$creds_text = __('Copyright', 'genesis') . ' [footer_copyright] '. $filtered .' &middot; [footer_loginout]';
	}
	
	$output = '<div class="gototop"><p>' . $backtotop_text . '</p></div>' . '<div class="creds"><p>' . $creds_text . '</p></div>';
	
	echo apply_filters('genesis_footer_output', $output, $backtotop_text, $creds_text);
	
}

/**
 * Output the footer scripts, defined in Theme Settings
 */
add_filter('genesis_footer_scripts', 'do_shortcode');
add_action('wp_footer', 'genesis_footer_scripts');
function genesis_footer_scripts() {
	
	echo apply_filters('genesis_footer_scripts', genesis_option('footer_scripts'));

}
<?php
/**
 * @todo Document this file
 *
 **/

/**
 * This function loads front-end JS files
 *
 */
add_action('get_header', 'genesis_load_scripts');
function genesis_load_scripts() {
	if (is_singular() && get_option('thread_comments') && comments_open())
		wp_enqueue_script('comment-reply');
		
	// Load superfish and our common JS (in the footer, and only if necessary)
	if( genesis_get_option('nav_superfish') || genesis_get_option('subnav_superfish') || 
		is_active_widget(0,0, 'menu-categories') || is_active_widget(0,0, 'menu-pages') ) {
			
			wp_enqueue_script('superfish', GENESIS_JS_URL.'/menu/superfish.js', array('jquery'), '1.4.8', TRUE);
			wp_enqueue_script('superfish-args', GENESIS_JS_URL.'/menu/superfish.args.js', array('superfish'), PARENT_THEME_VERSION, TRUE);
			
	}
}

/**
 * Hook this function to wp_head() and you'll be able to use many of
 * the new IE8 functionality. Not loaded by default.
 *
 * @link http://ie7-js.googlecode.com/svn/test/index.html
 */
function genesis_ie8_js() {
	$output = '
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
<![endif]-->
	';
	
	echo "\n".$output."\n";
}

/**
 * This function loads the admin JS files
 *
 */
add_action('admin_init', 'genesis_load_admin_scripts');
function genesis_load_admin_scripts() {
	add_thickbox();
	wp_enqueue_script('theme-preview');
	wp_enqueue_script('genesis_admin_js', GENESIS_JS_URL.'/admin.js');	
}
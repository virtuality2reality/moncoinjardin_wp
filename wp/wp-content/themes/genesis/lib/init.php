<?php
/**
 * WARNING: This file is part of the core Genesis framework. DO NOT edit
 * this file under any circumstances. Please do all modifications
 * in the form of a child theme.
 *
 * This file initializes the framework by doing some
 * basic things like defining constants, and loading
 * framework components from the /lib directory.
 *
 * @package Genesis
 *
 **/

//	Run the genesis_pre Hook
do_action('genesis_pre');

/**
 * Activate Theme features
 */
add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
add_theme_support('genesis-inpost-layouts');
add_theme_support('genesis-archive-layouts');
add_theme_support('genesis-admin-menu');
add_theme_support('genesis-seo-settings-menu');
add_theme_support('genesis-import-export-menu');
add_theme_support('genesis-readme-menu');
add_theme_support('genesis-auto-updates');

/**
 * Define Theme Name/Version Constants
 * 
 **/
define('PARENT_THEME_NAME', 'Genesis');
define('PARENT_THEME_VERSION', '1.4');
define('PARENT_THEME_RELEASE_DATE', date_i18n('F j, Y', '1289973600'));

/**
 * Define Directory Location Constants
 */
define('PARENT_DIR', TEMPLATEPATH);
define('CHILD_DIR', STYLESHEETPATH);
define('GENESIS_IMAGES_DIR', PARENT_DIR.'/images');
define('GENESIS_LIB_DIR', PARENT_DIR.'/lib');
define('GENESIS_ADMIN_DIR', GENESIS_LIB_DIR.'/admin');
define('GENESIS_ADMIN_IMAGES_DIR', GENESIS_LIB_DIR.'/admin/images');
define('GENESIS_JS_DIR', GENESIS_LIB_DIR.'/js');
define('GENESIS_CSS_DIR', GENESIS_LIB_DIR.'/css');
define('GENESIS_FUNCTIONS_DIR', GENESIS_LIB_DIR.'/functions');
define('GENESIS_SHORTCODES_DIR', GENESIS_LIB_DIR.'/shortcodes');
define('GENESIS_STRUCTURE_DIR', GENESIS_LIB_DIR.'/structure');
if( !defined('GENESIS_LANGUAGES_DIR') ) // So we can define with a child theme
	define('GENESIS_LANGUAGES_DIR', GENESIS_LIB_DIR.'/languages');
define('GENESIS_TOOLS_DIR', GENESIS_LIB_DIR.'/tools');
define('GENESIS_WIDGETS_DIR', GENESIS_LIB_DIR.'/widgets');

/**
 * Define URL Location Constants
 */
define('PARENT_URL', get_bloginfo('template_directory'));
define('CHILD_URL', get_bloginfo('stylesheet_directory'));
define('GENESIS_IMAGES_URL', PARENT_URL.'/images');
define('GENESIS_LIB_URL', PARENT_URL.'/lib');
define('GENESIS_ADMIN_URL', GENESIS_LIB_URL.'/admin');
define('GENESIS_ADMIN_IMAGES_URL', GENESIS_LIB_URL.'/admin/images');
define('GENESIS_JS_URL', GENESIS_LIB_URL.'/js');
define('GENESIS_CSS_URL', GENESIS_LIB_URL.'/css');
define('GENESIS_FUNCTIONS_URL', GENESIS_LIB_URL.'/functions');
define('GENESIS_SHORTCODES_URL', GENESIS_LIB_URL.'/shortcodes');
define('GENESIS_STRUCTURE_URL', GENESIS_LIB_URL.'/structure');
if( !defined('GENESIS_LANGUAGES_URL') ) // So we can predefine to child theme
	define('GENESIS_LANGUAGES_URL', GENESIS_LIB_URL.'/languages');
define('GENESIS_TOOLS_URL', GENESIS_LIB_URL.'/tools');
define('GENESIS_WIDGETS_URL', GENESIS_LIB_URL.'/widgets');

/**
 * Define Settings Field Constants (for DB storage)
 */
define('GENESIS_SETTINGS_FIELD', apply_filters('genesis_settings_field', 'genesis-settings'));
define('GENESIS_SEO_SETTINGS_FIELD', apply_filters('genesis_seo_settings_field', 'genesis-seo-settings'));

//	Run the genesis_pre_framework Hook
do_action('genesis_pre_framework');

/**
 * Load Framework Components, unless a child theme says not to
 *
 **/
if ( !defined('GENESIS_LOAD_FRAMEWORK') || GENESIS_LOAD_FRAMEWORK !== false ) :

//	Load Framework
require_once(GENESIS_LIB_DIR . '/framework.php');

//	Load Functions
require_once(GENESIS_FUNCTIONS_DIR . '/hooks.php');
require_once(GENESIS_FUNCTIONS_DIR . '/upgrade.php');
require_once(GENESIS_FUNCTIONS_DIR . '/general.php');
require_once(GENESIS_FUNCTIONS_DIR . '/options.php');
require_once(GENESIS_FUNCTIONS_DIR . '/image.php');
require_once(GENESIS_FUNCTIONS_DIR . '/admin.php');
require_once(GENESIS_FUNCTIONS_DIR . '/menu.php');
require_once(GENESIS_FUNCTIONS_DIR . '/layout.php');
require_once(GENESIS_FUNCTIONS_DIR . '/formatting.php');
require_once(GENESIS_FUNCTIONS_DIR . '/seo.php');
require_once(GENESIS_FUNCTIONS_DIR . '/widgetize.php');
require_once(GENESIS_FUNCTIONS_DIR . '/feed.php');
require_once(GENESIS_FUNCTIONS_DIR . '/I18n.php');
require_once(GENESIS_FUNCTIONS_DIR . '/deprecated.php');

//	Load Shortcodes
require_once(GENESIS_SHORTCODES_DIR . '/post.php');
require_once(GENESIS_SHORTCODES_DIR . '/footer.php');

//	Load Structure
require_once(GENESIS_STRUCTURE_DIR . '/header.php');
require_once(GENESIS_STRUCTURE_DIR . '/footer.php');
require_once(GENESIS_STRUCTURE_DIR . '/menu.php');
require_once(GENESIS_STRUCTURE_DIR . '/layout.php');
require_once(GENESIS_STRUCTURE_DIR . '/post.php');
require_once(GENESIS_STRUCTURE_DIR . '/loops.php');
require_once(GENESIS_STRUCTURE_DIR . '/comments.php');
require_once(GENESIS_STRUCTURE_DIR . '/sidebar.php');
require_once(GENESIS_STRUCTURE_DIR . '/archive.php');
require_once(GENESIS_STRUCTURE_DIR . '/search.php');

//	Load Admin
require_once(GENESIS_ADMIN_DIR . '/menu.php');
require_once(GENESIS_ADMIN_DIR . '/theme-settings.php');
require_once(GENESIS_ADMIN_DIR . '/seo-settings.php');
require_once(GENESIS_ADMIN_DIR . '/import-export.php');
require_once(GENESIS_ADMIN_DIR . '/readme-menu.php');
require_once(GENESIS_ADMIN_DIR . '/inpost-metaboxes.php');
require_once(GENESIS_ADMIN_DIR . '/term-meta.php');
require_once(GENESIS_ADMIN_DIR . '/user-meta.php');
require_once(GENESIS_ADMIN_DIR . '/editor.php');

//	Load Javascript
require_once(GENESIS_JS_DIR . '/load-scripts.php');

//	Load CSS
require_once(GENESIS_CSS_DIR . '/load-styles.php');
 
//	Load Widgets
require_once(GENESIS_WIDGETS_DIR . '/user-profile-widget.php');
require_once(GENESIS_WIDGETS_DIR . '/enews-widget.php');
require_once(GENESIS_WIDGETS_DIR . '/featured-post-widget.php');
require_once(GENESIS_WIDGETS_DIR . '/featured-page-widget.php');
require_once(GENESIS_WIDGETS_DIR . '/latest-tweets-widget.php');
require_once(GENESIS_WIDGETS_DIR . '/menu-pages-widget.php');
require_once(GENESIS_WIDGETS_DIR . '/menu-categories-widget.php');

//	Load Tools
require_once(GENESIS_TOOLS_DIR . '/custom-field-redirect.php');
require_once(GENESIS_TOOLS_DIR . '/breadcrumb.php');
if( current_theme_supports('post-templates') ) {
	require_once(GENESIS_TOOLS_DIR . '/post-templates.php');
}

endif; // end conditional loading of framework components

/**
 * Allowed formatting tags, used by wp_kses().
 * Filterable.
 */
$_genesis_formatting_allowedtags = apply_filters('genesis_formatting_allowedtags', array(
	//	<p>, <span>, <div>
	'p' => array( 'align' => array(), 'class' => array(), 'style' => array() ),
	'span' => array( 'align' => array(), 'class' => array(), 'style' => array() ),
	'div' => array( 'align' => array(), 'class' => array(), 'style' => array() ),
	
	// <img src="" class="" alt="" title="" width="" height="" />
	//'img' => array( 'src' => array(), 'class' => array(), 'alt' => array(), 'title' => array(), 'width' => array(), 'height' => array() ),
	
	//	<a href="" title="">Text</a>
	'a' => array( 'href' => array(), 'title' => array() ),
	
	//	<b>, </i>, <em>, <strong>
	'b' => array(), 'strong' => array(),
	'i' => array(), 'em' => array(),
	
	//	<blockquote>, <br />
	'blockquote' => array(),
	'br' => array()
) );

/**
 * Run the genesis_init() action hook
 *
 **/
genesis_init();
<?php
/**
 * This function handles the doctype. If you are going to replace the
 * doctype with a custom one, you must remember to include the opening
 * <html> and <head> elements too, along with the proper properties.
 *
 * It would be beneficial to also include the <meta> tag for Content Type.
 *
 * The default doctype is xHTML v1.0 Transitional.
 *
 * @since 1.3
 */
add_action('genesis_doctype', 'genesis_do_doctype');
function genesis_do_doctype() { 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes('xhtml'); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<?php
}

/**
 * Remove unnecessary code that WordPress puts in the <head>
 *
 * @since 1.3
 * @uses remove_action(), genesis_get_seo_option()
 */
add_action('get_header', 'genesis_doc_head_control');
function genesis_doc_head_control() {
	
	remove_action( 'wp_head', 'wp_generator' );
		
	if ( !genesis_get_seo_option('head_index_rel_link') )
		remove_action( 'wp_head', 'index_rel_link' );
	
	if ( !genesis_get_seo_option('head_parent_post_rel_link') )
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	
	if ( !genesis_get_seo_option('head_start_post_rel_link') )
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	
	if ( !genesis_get_seo_option('head_adjacent_posts_rel_link') )
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		
	if ( !genesis_get_seo_option('head_wlwmanifest_link') )
		remove_action( 'wp_head', 'wlwmanifest_link' );
	
	if ( !genesis_get_seo_option('head_shortlink') )
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

	if ( is_single() && !genesis_get_option('comments_posts') )
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		
	if ( is_page() && !genesis_get_option('comments_pages') )
		remove_action( 'wp_head', 'feed_links_extra', 3 );

}

/**
 * This function outputs our site title in the #header.
 * Depending on the SEO option set by the user, this will
 * either be wrapped in <h1> or <p> tags.
 */
add_action('genesis_site_title', 'genesis_seo_site_title');
function genesis_seo_site_title() {
	// Set what goes inside the wrapping tags
	$inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( get_bloginfo('url') ), esc_attr( get_bloginfo('name') ), get_bloginfo('name') );
	
	// Determine which wrapping tags to use
	$wrap = is_home() && genesis_get_seo_option('home_h1_on') == 'title' ? 'h1' : 'p';
	
	// A little fallback, in case an SEO plugin is active
	$wrap = is_home() && !genesis_get_seo_option('home_h1_on') ? 'h1' : $wrap;

	// Build the Title
	$title = sprintf('<%s id="title">%s</%s>', $wrap, $inside, $wrap);
	
	// Return (filtered)
	echo apply_filters('genesis_seo_title', $title, $inside, $wrap);
}

/**
 * This function outputs our site description in the #header.
 * Depending on the SEO option set by the user, this will
 * either be wrapped in <h1> or <p> tags.
 */
add_action('genesis_site_description', 'genesis_seo_site_description');
function genesis_seo_site_description() {
	// Set what goes inside the wrapping tags
	$inside = esc_html ( get_bloginfo( 'description' ) );
	
	// Determine which wrapping tags to use
	$wrap = is_home() && genesis_get_seo_option('home_h1_on') == 'description' ? 'h1' : 'p';

	// Build the Description
	$description = sprintf('<%s id="description">%s</%s>', $wrap, $inside, $wrap);
	
	// Return (filtered)
	echo apply_filters('genesis_seo_description', $description, $inside, $wrap);
}

/**
 * This function wraps the doctitle in <title></title> tags
 */
add_filter('wp_title', 'genesis_doctitle_wrap', 20);
function genesis_doctitle_wrap( $title ) {
	return is_feed() ? $title : sprintf( "<title>%s</title>\n", $title );
}

/**
 * This function does 3 things:
 * 1. Pulls the values for $sep and $seplocation, uses defaults if necessary
 * 2. Determines if the site title should be appended
 * 3. Allows the user to set a custom title on a per-page/post basis
 *
 * @since 0.1.3
 */
add_action('genesis_title', 'wp_title');
add_filter('wp_title', 'genesis_default_title', 10, 3);
function genesis_default_title($title, $sep, $seplocation) {
	
	if ( is_feed() ) return trim( $title );
	
	$sep = genesis_get_seo_option('doctitle_sep') ? genesis_get_seo_option('doctitle_sep') : '–';
	$seplocation = genesis_get_seo_option('doctitle_seplocation') ? genesis_get_seo_option('doctitle_seplocation') : 'right';
	
	//	if viewing the homepage
	if ( is_front_page() ) {
		// determine the doctitle
		$title = genesis_get_seo_option('home_doctitle') ? genesis_get_seo_option('home_doctitle') : get_bloginfo('name');
		
		// append site description, if necessary
		$title = genesis_get_seo_option('append_description_home') ? $title." $sep ".get_bloginfo('description') : $title;
	}
	
	//	if viewing a post/page/attachment
	if ( is_singular() ) {
		//	The User Defined Title (Genesis)
		if ( genesis_get_custom_field('_genesis_title') ) {
			$title = genesis_get_custom_field('_genesis_title');
		}
		//	All-in-One SEO Pack Title (latest, vestigial)
		elseif ( genesis_get_custom_field('_aioseop_title') ) {
			$title = genesis_get_custom_field('_aioseop_title');
		}
		//	Headspace Title (vestigial)	
		elseif ( genesis_get_custom_field('_headspace_page_title') ) {
			$title = genesis_get_custom_field('_headspace_page_title');
		}
		//	Thesis Title (vestigial)	
		elseif ( genesis_get_custom_field('thesis_title') ) {
			$title = genesis_get_custom_field('thesis_title');
		}
		//	SEO Title Tag (vestigial)
		elseif ( genesis_get_custom_field('title_tag') ) {
			$title = genesis_get_custom_field('title_tag');
		}
		//	All-in-One SEO Pack Title (old, vestigial)
		elseif ( genesis_get_custom_field('title') ) {
			$title = genesis_get_custom_field('title');
		}
	}
	
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		
		$title = !empty( $term->meta['doctitle'] ) ? $term->meta['doctitle'] : $title;
	}
	
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		
		$title = !empty( $term->meta['doctitle'] ) ? $term->meta['doctitle'] : $title;
	}
	
	if ( is_tax() ) {
		global $wp_query;
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$title = !empty( $term->meta['doctitle'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['doctitle'] ) ) : $title;
	}
	
	if ( is_author() ) {
		$user_title = get_the_author_meta( 'doctitle', (int)get_query_var('author') );
		
		$title = $user_title ? $user_title : $title;
	}
	
	//	if we don't want site name appended, or if we're on the homepage
	if ( !genesis_get_seo_option('append_site_title') || is_front_page() )
		return esc_html ( trim( $title ) );
	
	// else
	$title = $seplocation == 'right' ? $title." $sep ".get_bloginfo('name') : get_bloginfo('name')." $sep ".$title;
		return esc_html( trim( $title ) );
}

/**
 * This function generates the <code>META</code> Description based
 * on contextual criteria. Outputs nothing if description isn't there.
 *
 * @since 1.2
 * @todo Add vestigial Thesis support (in 1.3)
 */
add_action('genesis_meta','genesis_seo_meta_description');
function genesis_seo_meta_description() {
	global $post;
	
	$description = '';
	
	// if we're on the homepage
	if ( is_front_page() ) {
		$description = genesis_get_seo_option('home_description') ? genesis_get_seo_option('home_description') : get_bloginfo('description');
	}
	
	// if we're on a single post/page/attachment
	if ( is_singular() ) {
		// else if description is set via custom field
		if ( genesis_get_custom_field('_genesis_description') ) {
			$description = genesis_get_custom_field('_genesis_description');
		}
		// else if the user used All-in-One SEO Pack (latest, vestigial)
		elseif ( genesis_get_custom_field('_aioseop_description') ) {
			$description = genesis_get_custom_field('_aioseop_description');
		}
		// else if the user used Headspace2 (vestigial)
		elseif ( genesis_get_custom_field('_headspace_description') ) {
			$description = genesis_get_custom_field('_headspace_description');
		}
		// else if the user used Thesis (vestigial)
		elseif ( genesis_get_custom_field('thesis_description') ) {
			$description = genesis_get_custom_field('thesis_description');
		}
		// else if the user used All-in-One SEO Pack (old, vestigial)
		elseif ( genesis_get_custom_field('description') ) {
			$description = genesis_get_custom_field('description');
		}
	}
	
	// if we're on a category archive
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		
		$description = !empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}
	
	// if we're on a tag archive
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		
		$description = !empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}
	
	// if we're on a taxonomy archive
	if ( is_tax() ) {
		global $wp_query;
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$description = !empty( $term->meta['description'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['description'] ) ) : '';
	}
	
	// if we're on an author archive
	if ( is_author() ) {
		$user_description = get_the_author_meta( 'meta_description', (int)get_query_var('author') );
		
		$description = $user_description ? $user_description : '';
	}
	
	// Add the description, but only if one exists
	if ( !empty($description) ) {
		echo '<meta name="description" content="'.esc_attr( $description ).'" />'."\n";
	}

}

/**
 * This function generates the <code>META</code> Keywords based
 * on contextual criteria. Outputs nothing if keywords aren't there.
 * 
 * @since 1.2
 * @todo Add vestigial Thesis support (in 1.3)
 */
add_action('genesis_meta', 'genesis_seo_meta_keywords');
function genesis_seo_meta_keywords() {
	global $post;
	
	$keywords = '';
	
	// if we're on the homepage
	if( is_front_page() ) {
		
		$keywords = genesis_get_seo_option('home_keywords');
		
	}
	
	// if we're on a single post/page/attachment
	if ( is_singular() ) {
		
		// if keywords are set via custom field
		if ( genesis_get_custom_field('_genesis_keywords') ) {
			$keywords = genesis_get_custom_field('_genesis_keywords');
		}
		// else if keywords are set via All-in-One SEO Pack (latest, vestigial)
		elseif ( genesis_get_custom_field('_aioseop_keywords') ) {
			$keywords = genesis_get_custom_field('_aioseop_keywords');
		}
		// else if keywords are set via Thesis (vestigial)
		elseif ( genesis_get_custom_field('thesis_keywords') ) {
			$keywords = genesis_get_custom_field('thesis_keywords');
		}
		// else if keywords are set via All-in-One SEO Pack (old, vestigial)
		elseif ( genesis_get_custom_field('keywords') ) {
			$keywords = genesis_get_custom_field('keywords');
		}

	}
	
	// if we're on a category archive
	if ( is_category() ) {
		
		$term = get_term( get_query_var('cat'), 'category' );
		
		$keywords = !empty( $term->meta['keywords'] ) ? $term->meta['keywords'] : '';
		
	}
	
	// if we're on a tag archive
	if ( is_tag() ) {
		
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		
		$keywords = !empty( $term->meta['keywords'] ) ? $term->meta['keywords'] : '';
		
	}
	
	// if we're on a taxonomy archive
	if ( is_tax() ) {
		
		global $wp_query;
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$keywords = !empty( $term->meta['keywords'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['keywords'] ) ) : '';
		
	}
	
	// if we're on an author archive
	if ( is_author() ) {
		$user_keywords = get_the_author_meta( 'meta_keywords', (int)get_query_var('author') );
		
		$keywords = $user_keywords ? $user_keywords : '';
	}
	
	// return nothing, if no keywords set
	if ( empty( $keywords ) )
		return;
	
	// Add the keywords, but only if they exist	
	echo '<meta name="keywords" content="'.esc_attr( $keywords ).'" />'."\n";
	
}

/**
 * This function generates the index/follow/noodp/noydir/noarchive code in the document <head>
 *
 * @uses genesis_get_seo_option, genesis_get_custom_field
 *
 * @since 0.1.3
 */
add_action('genesis_meta','genesis_robots_meta');
function genesis_robots_meta() {
	global $post;
	
	// if the user wants the blog private, then follow logic
	// is unnecessary. WP will insert noindex and nofollow
	if ( get_option('blog_public') == 0 ) return;
	
	// defaults
	$meta = array(
		'noindex' => '',
		'nofollow' => '',
		'noarchive' => genesis_get_seo_option('noarchive') ? 'noarchive' : '',
		'noodp' => genesis_get_seo_option('noodp') ? 'noodp' : '',
		'noydir' => genesis_get_seo_option('noydir') ? 'noydir' : ''
	);
	
	// Check homepage SEO settings, set noindex/nofollow/noarchive
	if ( is_front_page() ) {
		
		$meta['noindex'] = genesis_get_seo_option('home_noindex') ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = genesis_get_seo_option('home_nofollow') ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = genesis_get_seo_option('home_noarchive') ? 'noarchive' : $meta['noarchive'];
		
	}

	// Check category META, set noindex/nofollow/noarchive
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		
		$meta['noindex'] = $term->meta['noindex'] ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = $term->meta['nofollow'] ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = $term->meta['noarchive'] ? 'noarchive' : $meta['noarchive'];
		
		$meta['noindex'] = genesis_get_seo_option('noindex_cat_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = genesis_get_seo_option('noarchive_cat_archive') ? 'noarchive' : $meta['noarchive'];
		
		//	noindex paged archives, if canonical archives is off
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$meta['noindex'] = $paged > 1 && !genesis_get_seo_option('canonical_archives') ? 'noindex' : $meta['noindex'];

	}
	
	// Check tag META, set noindex/nofollow/noarchive
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		
		$meta['noindex'] = $term->meta['noindex'] ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = $term->meta['nofollow'] ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = $term->meta['noarchive'] ? 'noarchive' : $meta['noarchive'];
		
		$meta['noindex'] = genesis_get_seo_option('noindex_tag_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = genesis_get_seo_option('noarchive_tag_archive') ? 'noarchive' : $meta['noarchive'];
		
		//	noindex paged archives, if canonical archives is off
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$meta['noindex'] = $paged > 1 && !genesis_get_seo_option('canonical_archives') ? 'noindex' : $meta['noindex'];
		
	}
	
	// Check term META, set noindex/nofollow/noarchive
	if ( is_tax() ) {
		global $wp_query;
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$meta['noindex'] = $term->meta['noindex'] ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = $term->meta['nofollow'] ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = $term->meta['noarchive'] ? 'noarchive' : $meta['noarchive'];
		
		//	noindex paged archives, if canonical archives is off
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$meta['noindex'] = $paged > 1 && !genesis_get_seo_option('canonical_archives') ? 'noindex' : $meta['noindex'];
		
	}
	
	// Check author META, set noindex/nofollow/noarchive
	if ( is_author() ) {
		
		$meta['noindex'] = get_the_author_meta( 'noindex', (int)get_query_var('author') ) ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = get_the_author_meta( 'nofollow', (int)get_query_var('author') ) ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = get_the_author_meta( 'noarchive', (int)get_query_var('author') ) ? 'noarchive' : $meta['noarchive'];
		
		$meta['noindex'] = genesis_get_seo_option('noindex_author_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = genesis_get_seo_option('noarchive_author_archive') ? 'noarchive' : $meta['noarchive'];
		
		//	noindex paged archives, if canonical archives is off
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$meta['noindex'] = $paged > 1 && !genesis_get_seo_option('canonical_archives') ? 'noindex' : $meta['noindex'];
		
	}
	
	if ( is_date() ) {
		$meta['noindex'] = genesis_get_seo_option('noindex_date_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = genesis_get_seo_option('noarchive_date_archive') ? 'noarchive' : $meta['noarchive'];
	}
	if ( is_search() ) {
		$meta['noindex'] = genesis_get_seo_option('noindex_search_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = genesis_get_seo_option('noarchive_search_archive') ? 'noarchive' : $meta['noarchive'];
	}

	// Check post/page META, set noindex/nofollow/noarchive
	if ( is_singular() ) {
		
		$meta['noindex'] = genesis_get_custom_field('_genesis_noindex') ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = genesis_get_custom_field('_genesis_nofollow') ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = genesis_get_custom_field('_genesis_noarchive') ? 'noarchive' : $meta['noarchive'];
		
	}
		
	// return nothing, unless we're supposed to noindex OR nofollow
	if ( !$meta['noindex'] && !$meta['nofollow'] && !$meta['noodp'] && !$meta['noydir'] && !$meta['noarchive'] )
		return;

	printf( '<meta name="robots" content="%s" />' . "\n", implode( ",", array_filter( $meta ) ) );
}

/**
 * Show Parent and Child information in the document head if specified by the user.
 * This can be helpful for diagnosing problems with the theme, because you can
 * easily determine if anything is out of date, needs to be updated.
 *
 * @since 1.0
 */
add_action('genesis_meta', 'genesis_show_theme_info_in_head');
function genesis_show_theme_info_in_head() {
	if ( !genesis_get_option( 'show_info' ) ) return;
	
	// Show Parent Info
	echo "\n".'<!-- Theme Information -->'."\n";
	echo '<meta name="wp_template" content="'. esc_attr( PARENT_THEME_NAME ) .' '. esc_attr( PARENT_THEME_VERSION ) .'" />'."\n";
	
	// If there is no child theme, don't continue
	if ( CHILD_DIR == PARENT_DIR ) return;
	
	// Show Child Info
	$child_info = get_theme_data(CHILD_DIR.'/style.css');
	echo '<meta name="wp_theme" content="'. esc_attr( $child_info['Name'] ) .' '. esc_attr( $child_info['Version'] ) .'" />'."\n";
}

/**
 * This function adds the pingback meta tag to the <head> so that other
 * sites can know how to send a pingback to our site.
 * 
 * @since 1.3
 */
add_action('wp_head', 'genesis_do_meta_pingback');
function genesis_do_meta_pingback() {
	
	if ( get_option('default_ping_status') == 'open' ) {
		echo '<link rel="pingback" href="' . get_bloginfo('pingback_url') . '" />' . "\n";
	}
	
}

/**
 * Remove the default WordPress canonical tag, and use our custom 
 * one. Gives us more flexibility and effectiveness.
 *
 * @uses genesis_get_seo_option, genesis_get_custom_field
 *
 * @since 0.1.3
 */
remove_action('wp_head', 'rel_canonical');
add_action('wp_head','genesis_canonical');
function genesis_canonical() {
	global $wp_query;
	
	$canonical = '';
	
	if ( is_front_page() ) {
		$canonical = get_bloginfo('url');
	}
		
	if ( is_singular() ) {
		
		if ( !$id = $wp_query->get_queried_object_id() )
			return;
		
		$cf = genesis_get_custom_field('_genesis_canonical_uri');
		
		$canonical = $cf ? $cf : get_permalink( $id );
		
	}
	
	if ( is_category() || is_tag() || is_tax() ) {
		
		if ( !$id = $wp_query->get_queried_object_id() )
			return;
			
		$taxonomy = $wp_query->queried_object->taxonomy;
		
		$canonical = genesis_get_seo_option('canonical_archives') ? get_term_link( (int)$id, $taxonomy ) : 0;
		
	}
	
	if ( is_author() ) {
		
		if ( !$id = $wp_query->get_queried_object_id() )
			return;
		
		$canonical = genesis_get_seo_option('canonical_archives') ? get_author_posts_url( $id ) : 0;
		
	}
	
	if ( !$canonical ) return;
		
	printf('<link rel="canonical" href="%s" />'."\n", esc_url( $canonical ) );
	
}

/**
 * This function looks for a favicon. If it finds
 * one, it will output the proper code in the <head>
 *
 * @since 0.2.2
 */
add_action('genesis_meta', 'genesis_load_favicon');
function genesis_load_favicon() {
	
	// Allow child theme to short-circuit this function
	$pre = apply_filters('genesis_pre_load_favicon', false);
	
	if ( $pre !== false )
		$favicon = $pre;
	elseif ( file_exists(CHILD_DIR.'/images/favicon.ico') )
		$favicon = CHILD_URL.'/images/favicon.ico';
	elseif ( file_exists(CHILD_DIR.'/images/favicon.gif') )
		$favicon = CHILD_URL.'/images/favicon.gif';
	elseif ( file_exists(CHILD_DIR.'/images/favicon.png') )
		$favicon = CHILD_URL.'/images/favicon.png';
	elseif ( file_exists(CHILD_DIR.'/images/favicon.jpg') )
		$favicon = CHILD_URL.'/images/favicon.jpg';
	else
		$favicon = PARENT_URL.'/images/favicon.ico';

	$favicon = apply_filters('genesis_favicon_url', $favicon);

	if ( $favicon )
	echo '<link rel="Shortcut Icon" href="'. esc_url( $favicon ). '" type="image/x-icon" />'."\n";
}

/**
 * Output header scripts in to <code>wp_head()</code>
 * Allow shortcodes
 *
 * @since 0.2.3
 */
add_filter('genesis_header_scripts', 'do_shortcode');
add_action('wp_head', 'genesis_header_scripts');
function genesis_header_scripts() {
	
	echo apply_filters('genesis_header_scripts', genesis_get_option('header_scripts'));
	
	// If singular, echo scripts from custom field
	if ( is_singular() ) {
		genesis_custom_field('_genesis_scripts');
	}
	
}

/**
 * Outputs the structural markup for the header
 *
 * @since 1.2
 */
add_action('genesis_header', 'genesis_header_markup_open', 5);
function genesis_header_markup_open() {
	
	echo '<div id="header"><div class="wrap">';
	
}
add_action('genesis_header', 'genesis_header_markup_close', 15);
function genesis_header_markup_close() {

	echo '</div><!-- end .wrap --></div><!--end #header-->' . "\n";

}

/**
 * This function outputs the default header, including the #title-area div,
 * along with #title and #description, as well as the .widget-area.
 *
 * @since 1.0.2
 */
add_action('genesis_header', 'genesis_do_header');
function genesis_do_header() {
		
	echo '<div id="title-area">';
		genesis_site_title();
		genesis_site_description();
	echo '</div><!-- end #title-area -->';
	
	if ( genesis_get_option('header_right') ) {
		echo '<div class="widget-area">';
			dynamic_sidebar('Header Right');
		echo '</div><!-- end .widget_area -->';
	}
}
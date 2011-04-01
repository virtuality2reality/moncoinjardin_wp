<?php
/**
 * undocumented 
 *
 * @package Genesis
 */

/**
 * Display Breadcrumbs above the Loop
 * Concedes priority to popular breadcrumb plugins
 *
 * @since 0.1.6
 */
add_action('genesis_before_loop', 'genesis_do_breadcrumbs');
function genesis_do_breadcrumbs() {
	
	// Conditional Checks
	if ( is_front_page() && !genesis_get_option( 'breadcrumb_home' ) ) return;
	if ( is_single() && !genesis_get_option( 'breadcrumb_single' ) ) return;
	if ( is_page() && !genesis_get_option( 'breadcrumb_page' ) ) return;
	if ( ( is_archive() || is_search() ) && !genesis_get_option('breadcrumb_archive')) return;
	if ( is_404() && !genesis_get_option('breadcrumb_404') ) return;

	if ( function_exists( 'bcn_display' ) ) {
		echo '<div class="breadcrumb">'; bcn_display(); echo '</div>';
	}
	elseif ( function_exists( 'yoast_breadcrumb' ) ) {
		yoast_breadcrumb('<div class="breadcrumb">','</div>');
	}
	elseif ( function_exists( 'breadcrumbs' ) ) {
		breadcrumbs();
	}
	elseif ( function_exists( 'crumbs' ) ) {
		crumbs();
	}
	else {
		genesis_breadcrumb();
	}
}

/**
 * This function displays a breadcrumb navigation trail.
 *
 * @author Joost de Valk
 * @since 0.1
 */
function genesis_breadcrumb( $args = array() ) {
	global $wp_query, $post;

	$defaults = array(
		'home'	=> __('Home', 'genesis'),
		'sep'	 => '/',
		'prefix'  => '<div class="breadcrumb">',
		'suffix'  => '</div>',
		'display' => true,
		'labels'  => array(
			'prefix' => __('You are here: ', 'genesis'),
			'author' => __('Archives for ','genesis'),
			'tag'	=> __('Archives for ','genesis'),
			'date'   => __('Archives for ','genesis'),
			'search' => __('Search for ','genesis'),
			'tax'	=> __('Archives for ','genesis')
		)
	);
	$args = wp_parse_args($args, $defaults);
	$args = apply_filters('genesis_breadcrumb_args', $args);

	// add whitespace to the separator only once
	$args['sep'] = ' ' . trim($args['sep']) . ' ';

	// Cache common format for sprintf
	$link_format = '<a href="%s">%s</a>';

	// Cache get_option call
	$on_front = get_option('show_on_front');

	if ( 'page' == $on_front ) {
		$homelink = sprintf( $link_format, get_permalink(get_option('page_on_front')), $args['home'] );
		$bloglink = get_option('page_for_posts');

		if ( $bloglink )
			$bloglink = sprintf( '%s%s' . $link_format, $homelink, $args['sep'], get_permalink($bloglink), get_the_title($bloglink) );
		else
			$bloglink = $homelink;

	} else {
		$homelink = sprintf( $link_format, get_bloginfo('url'), $args['home'] );
		$bloglink = $homelink;
	}

	$homelink = apply_filters('genesis_breadcrumb_homelink', $homelink);
	$bloglink = apply_filters('genesis_breadcrumb_bloglink', $bloglink);

	/**
	 * Returns merged array.
	 *
	 * @param array $crumbs the current crumbs array.
	 * @param int $parent_id parent id.
	 * @param boolean $link true to return a category link for last item or false to return category name for last item.
	 * @param arrray $visited list of visited parents.
	 * @returns array list of category links.
	 */
	function sp_get_category_parents( $crumbs, $parent_id, $link = false, $visited = array() ) {

		$parent = &get_category( (int) $parent_id );
		if ( is_wp_error( $parent ) )
			return array();

		if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
			$visited[] = $parent->parent;
			$chain = sp_get_category_parents(array(), $parent->parent, true, $visited );
		}

		if ( $link && ! is_wp_error(get_category_link( $parent->term_id )) )
			$chain[] = sprintf( '<a href="%s" title="%s">%s</a>', get_category_link( $parent->term_id ), esc_attr( sprintf( __( "View all posts in %s" ), $parent->cat_name ) ), esc_html( $parent->cat_name ) );
		else
			$chain[] = $parent->cat_name;

		return array_merge($crumbs, $chain);
	}

	if ( ('page' == $on_front && is_front_page()) || ('posts' == $on_front && is_home()) ) {
		$crumbs[] = $args['home'];

	} elseif ( 'page' == $on_front && is_home() ) {
		$crumbs[] = $homelink;
		$crumbs[] = get_the_title( get_option('page_for_posts') );

	} elseif ( !is_page() ) {
		$crumbs[] = $bloglink;
		if ( is_singular('post') ) {
			$cats = get_the_category();

			// get_the_category() can return a wp_error object
			if ( !is_wp_error($cats) ) {
				$cat = $cats[0];
				if ( $cat->parent != 0 ) {
					$crumbs = sp_get_category_parents($crumbs, $cat->term_id, true);

				// fixes known bug
				} elseif ( !is_wp_error( get_category_link($cat->term_id) ) ) {
					$crumbs[] = sprintf( $link_format, get_category_link($cat->term_id), esc_html( $cat->name ) );
				} else {
					$crumbs[] = esc_html( $cat->name );
				}
			}
		}

		if ( is_category() ) {
			$crumbs = sp_get_category_parents($crumbs, get_query_var('cat'), false );

		} elseif ( is_tag() ) {
			$crumbs[] = $args['labels']['tag'] . single_cat_title('',false);

		} elseif ( is_date() ) {
			$crumbs[] = $args['labels']['date'] . single_month_title(' ',false);

		} elseif ( is_author() ) { // fixes bug in last breadcrumb version
			$crumbs[] = $args['labels']['author'] . esc_html($wp_query->queried_object->display_name);

		} elseif ( is_search() ) {
			$crumbs[] = $args['labels']['search'] . '"' . esc_html(apply_filters('the_search_query', get_search_query())) . '"';

		} elseif( is_tax() ) {
			$crumbs[] = $args['labels']['tax'] . esc_html($wp_query->queried_object->name);

		} else {
			$crumbs[] = get_the_title();
		}

	} else {
		// This is a page

		$post = $wp_query->get_queried_object();

		// If this is a top level Page, it's simple to output the breadcrumb
		if ( 0 == $post->post_parent ) {
			$crumbs = array($homelink, get_the_title());
		} else {
			if (isset($post->ancestors)) {
				if (is_array($post->ancestors))
					$ancestors = array_values($post->ancestors);
				else
					$ancestors = array($post->ancestors);
			} else {
				$ancestors = array($post->post_parent);
			}

			$crumbs = array();
			foreach ( $ancestors as $ancestor ) {
				array_unshift($crumbs, sprintf($link_format, get_permalink($ancestor), strip_tags(get_the_title($ancestor))));
			}

			// Add home link
			array_unshift($crumbs, $homelink);

			// Add the current page title
			$crumbs[] = strip_tags(get_the_title($post->ID));
		}
	}

	// Add separators between crumbs
	$output = join($args['sep'], $crumbs);

	if ('' !== trim($args['labels']['prefix']))
		$output = $args['labels']['prefix'] . $output;

	if ($args['display'])
		echo $args['prefix'] . $output . $args['suffix'];

	else
		return $args['prefix'] . $output . $args['suffix'];
}
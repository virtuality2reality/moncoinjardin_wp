<?php
/**
 * This function/filter adds a custom post class based on
 * the value stored as a custom field.
 * 
 * @since 1.4
 */
add_filter('post_class', 'genesis_custom_post_class', 15);
function genesis_custom_post_class( $classes ) {
	
	$new_class = genesis_get_custom_field( '_genesis_custom_post_class' );
	
	if ( $new_class ) $classes[] = esc_attr( sanitize_html_class( $new_class ) );
	
	return $classes;
	
}

/**
 * Post format icon.
 * Adds an image, corresponding to the post format, before the post title
 * 
 * @since 1.4
 */
add_action( 'genesis_before_post_title', 'genesis_do_post_format_image' );
function genesis_do_post_format_image() {
	global $post;
	
	// do nothing if post formats aren't supported, or function doesn't exist
	if ( !current_theme_supports( 'post-formats' ) || !function_exists( 'get_post_format' ) )
		return;
	
	$post_format = get_post_format( $post );

	if ( file_exists( sprintf( '%s/images/post-formats/%s.png', CHILD_DIR, $post_format ) ) ) {
		printf( '<a href="%s" title="%s" rel="bookmark"><img src="%s" class="post-format-image" alt="%s" /></a>', get_permalink(), the_title_attribute('echo=0'), sprintf( '%s/images/post-formats/%s.png', CHILD_URL, $post_format ), $post_format );
	}
	elseif ( file_exists( sprintf( '%s/images/post-formats/default.png', CHILD_DIR ) ) ) {
		printf( '<a href="%s" title="%s" rel="bookmark"><img src="%s/images/post-formats/default.png" class="post-format-image" alt="%s" /></a>', get_permalink(), the_title_attribute('echo=0'), CHILD_URL, 'post' );
	}
	
}

/**
 * Post Title
 */
add_action('genesis_post_title', 'genesis_do_post_title');
function genesis_do_post_title() {
	
	if ( is_singular() ) {
		$title = sprintf( '<h1 class="entry-title">%s</h1>', apply_filters( 'genesis_post_title_text', get_the_title() ) );
	}
	
	else {
		$title = sprintf( '<h2 class="entry-title"><a href="%s" title="%s" rel="bookmark">%s</a></h2>', get_permalink(), the_title_attribute('echo=0'), apply_filters( 'genesis_post_title_text', get_the_title() ) );
	}
	
	echo apply_filters('genesis_post_title_output', $title) . "\n";
	
}

/**
 * Post Image
 */
add_action('genesis_post_content', 'genesis_do_post_image');
function genesis_do_post_image() {
	
	if ( !is_singular() && genesis_get_option('content_archive_thumbnail') ) {
		$img = genesis_get_image( array( 'format' => 'html', 'size' => genesis_get_option('image_size'), 'attr' => array( 'class' => 'alignleft post-image' ) ) );
		printf( '<a href="%s" title="%s">%s</a>', get_permalink(), the_title_attribute('echo=0'), $img );
	}
	
}

/**
 * Post Content
 */
add_action('genesis_post_content', 'genesis_do_post_content');
function genesis_do_post_content() {
	
	if ( is_singular() ) {
		the_content(); // display content on posts/pages
		wp_link_pages( array( 'before' => '<p class="pages">' . __( 'Pages:', 'genesis' ), 'after' => '</p>' ) );
		
		if ( is_single() && get_option('default_ping_status') == 'open' ) {
			echo '<!--'; trackback_rdf(); echo '-->' ."\n";
		}
		
		if ( is_page() ) {
			edit_post_link(__('(Edit)', 'genesis'), '', '');
		}
		
	}
	elseif ( genesis_get_option('content_archive') == 'excerpts' ) {
		the_excerpt();
	}
	else {
		if ( genesis_get_option('content_archive_limit') )
			the_content_limit( (int)genesis_get_option('content_archive_limit'), __('[Read more...]', 'genesis') );
		else
			the_content(__('[Read more...]', 'genesis'));
	}
	
}

/**
 * No Posts
 */
add_action('genesis_loop_else', 'genesis_do_noposts');
function genesis_do_noposts() {
	
	printf( '<p>%s</p>', apply_filters( 'genesis_noposts_text', __('Sorry, no posts matched your criteria.', 'genesis') ) );
	
}

/**
 * Add the post info (byline) under the title
 *
 * @since 0.2.3
 */
add_filter('genesis_post_info', 'do_shortcode', 20);
add_action('genesis_before_post_content', 'genesis_post_info');
function genesis_post_info() {
	
	if ( is_page() )
		return; // don't do post-info on pages
	
	$post_info = '[post_date] ' . __('By', 'genesis') . ' [post_author_posts_link] [post_comments] [post_edit]';
	printf( '<div class="post-info">%s</div>', apply_filters('genesis_post_info', $post_info) );
	
}

/**
 * Add the post meta after the post content 
 *
 * @since 0.2.3
 */
add_filter('genesis_post_meta', 'do_shortcode', 20);
add_action('genesis_after_post_content', 'genesis_post_meta');
function genesis_post_meta() {
	
	if ( is_page() )
		return; // don't do post-meta on pages
	
	$post_meta = '[post_categories] [post_tags]';
	printf( '<div class="post-meta">%s</div>', apply_filters('genesis_post_meta', $post_meta) );
	
}

/**
 * This function runs some conditional code and calls the
 * genesis_author_box() function, if necessary.
 * 
 * @uses genesis_author_box()
 * 
 * @since 1.0
 */
add_action('genesis_after_post', 'genesis_do_author_box_single');
function genesis_do_author_box_single() {
	
	if ( !is_single() )
		return;

	if ( get_the_author_meta( 'genesis_author_box_single', get_the_author_meta('ID') ) ) {
		genesis_author_box( 'single' );
	}
	
}

/**
 * This function outputs the content of the author box.
 * The title is filterable, and the description is dynamic.
 * 
 * @uses get_the_author_meta(), get_the_author
 *
 * @since 1.3
 */
function genesis_author_box( $context = '' ) {
	
	global $authordata;
	$authordata = is_object( $authordata ) ? $authordata : get_userdata( get_query_var('author') );
	
	$gravatar_size = apply_filters('genesis_author_box_gravatar_size', '70', $context);
	$gravatar = get_avatar( get_the_author_meta('email'), $gravatar_size );
	$title = apply_filters( 'genesis_author_box_title', sprintf( '<strong>%s %s</strong>', __('About', 'genesis'), get_the_author() ), $context );
	$description = wpautop( get_the_author_meta('description') );
	
	// The author box markup, contextual.
	$pattern = $context == 'single' ? '<div class="author-box"><p>%s %s<br />%s</p></div><!-- end .authorbox-->' : '<div class="author-box">%s<h1>%s</h1><p>%s</p></div><!-- end .authorbox-->';
	
	printf($pattern, $gravatar, $title, $description);
	
}

/**
 * The Genesis-specific post date
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_date($format = '', $label = '') {
	// pull the $format, or use the default
	$format = $format ? $format : get_option('date_format');
	
	$label = $label ? trim( $label ).' ' : '';

	printf('<span class="time published" title="%3$s">%1$s%2$s</span> ', $label, get_the_time($format), get_the_time('Y-m-d\TH:i:sO'));
}

/**
 * The Genesis-specific post author link
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_author_posts_link($label = '') {
	global $authordata;
	$id = $authordata->ID;
	$nicename = $authordata->user_nicename;
	$display_name = get_the_author();
	
	$url = get_author_posts_url($id, $nicename);
	$title = sprintf(__('Posts by %s', 'genesis'), $display_name);
	
	$link = sprintf( '<a href="%1$s" title="%2$s" class="url fn">%3$s</a>', esc_url( $url ), esc_attr( $title ), esc_html( $display_name ) );
	
	// nofollow the link, if necessary
	$link = (genesis_get_seo_option('nofollow_author_link')) ? genesis_rel_nofollow($link) : $link;
	
	$label = (!empty($label)) ? trim($label).' ' : '';
	
	echo sprintf('<span class="author vcard">%1$s%2$s</span> ', esc_html( $label ), $link);
}

/**
 * The Genesis-specific post comments link
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_comments_link($zero = false, $one = false, $more = false) {
	global $post, $id;
	
	if ( 0 == genesis_get_option('comments_posts') or 'closed' == $post->comment_status )
	        return;

	$number = get_comments_number($id);

	if ( $number > 1 )
		$output = str_replace('%', number_format_i18n($number), ( false === $more ) ? __('% Comments', 'genesis') : $more);
	elseif ( $number == 0 )
		$output = ( false === $zero ) ? __('No Comments', 'genesis') : $zero;
	else // must be one
		$output = ( false === $one ) ? __('1 Comment', 'genesis') : $one;
	
	$link = sprintf('<a href="%s">%s</a>', get_permalink().'#respond', $output);
	
	// nofollow the link, if necessary
	$link = genesis_get_seo_option('nofollow_comments_link') ? genesis_rel_nofollow($link) : $link;
	
	echo sprintf('<span class="post-comments">%s</span> ', $link);
}

/**
 * The Genesis-specific post categories link
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_categories_link($sep = ', ', $label = '') {
	$links = get_the_category_list($sep);
	$links = (genesis_get_seo_option('nofollow_cat_link')) ? genesis_rel_nofollow($links) : $links;
	
	$label = (!empty($label)) ? trim($label).' ' : '';
	
	echo sprintf('<span class="categories">%1$s%2$s</span> ', $label, $links);
}

/**
 * The Genesis-specific post tags link
 *
 * @since 0.2.3
 * @todo deprecate
 */
function genesis_post_tags_link($sep = ', ', $label = '') {
	$label = (!empty($label)) ? trim($label).' ' : '';
	
	$links = get_the_tag_list($label, $sep);
	$links = (genesis_get_seo_option('nofollow_tag_link')) ? genesis_rel_nofollow($links) : $links;
	
	echo sprintf('<span class="tags">%s</span> ', $links);
}

/**
 * The default post navigation, hooked to genesis_after_endwhile
 *
 * @since 0.2.3
 */
add_action('genesis_after_endwhile', 'genesis_posts_nav');
function genesis_posts_nav() {
	$nav = genesis_get_option('posts_nav');
	
	if($nav == 'prev-next')
		genesis_prev_next_posts_nav();
	elseif($nav == 'numeric')
		genesis_numeric_posts_nav();
	else
		genesis_older_newer_posts_nav();
}

/**
 * Display older/newer posts navigation
 * 
 * @since 0.2.2
 */
function genesis_older_newer_posts_nav() {
	
	$older_link = get_next_posts_link('&laquo; ' . __('Older Posts', 'genesis'));
	$newer_link = get_previous_posts_link(__('Newer Posts', 'genesis') . ' &raquo;');
	
	$older = $older_link ? '<div class="alignleft">' . $older_link . '</div>' : '';
	$newer = $newer_link ? '<div class="alignright">' . $newer_link . '</div>' : '';
	
	$nav = '<div class="navigation">' . $older . $newer . '</div><!-- end .navigation -->';
	
	if ( !empty( $older ) || !empty( $newer ) )
		echo $nav;
}

/**
 * Display prev/next posts navigation
 * 
 * @since 0.2.2
 */
function genesis_prev_next_posts_nav() {
	
	$prev_link = get_previous_posts_link();
	$next_link = get_next_posts_link();
	
	$prev = $prev_link ? '<div class="alignleft">' . $prev_link . '</div>' : '';
	$next = $next_link ? '<div class="alignright">' . $next_link . '</div>' : '';
	
	$nav = '<div class="navigation">' . $prev . $next . '</div><!-- end .navigation -->';
	
	if ( !empty( $prev ) || !empty( $next ) )
		echo $nav;
}

/**
 * Display numeric posts navigation (similar to WP-PageNavi)
 *
 * @since 0.2.3
 */
function genesis_numeric_posts_nav() {
	if( is_singular() ) return; // do nothing
	
	global $wp_query;
	
	// Stop execution if there's only 1 page
	if( $wp_query->max_num_pages <= 1 ) return;
	
	$paged = get_query_var('paged') ? absint( get_query_var('paged') ) : 1;
	$max = intval( $wp_query->max_num_pages );
	
	echo '<div class="navigation"><ul>' . "\n";
		
	//	add current page to the array
	if ( $paged >= 1 )
		$links[] = $paged;
	
	//	add the pages around the current page to the array
	if ( $paged >= 3 ) {
		$links[] = $paged - 1; $links[] = $paged - 2;
	}
	if ( ($paged + 2) <= $max ) { 
		$links[] = $paged + 2; $links[] = $paged + 1;
	}
	
	//	Previous Post Link
	if ( get_previous_posts_link() )
		printf( '<li>%s</li>' . "\n", get_previous_posts_link( __('&laquo; Previous', 'genesis') ) );
	
	//	Link to first Page, plus ellipeses, if necessary
	if ( !in_array( 1, $links ) ) {
		if ( $paged == 1 ) $current = ' class="active"'; else $current = null;
		printf( '<li %s><a href="%s">%s</a></li>' . "\n", $current, get_pagenum_link(1), '1' );
		
		if ( !in_array( 2, $links ) )
			echo '<li>&hellip;</li>';
	}
	
	//	Link to Current page, plus 2 pages in either direction (if necessary).
	sort( $links );
	foreach( (array)$links as $link ) {
		$current = ( $paged == $link ) ? 'class="active"' : '';
		printf( '<li %s><a href="%s">%s</a></li>' . "\n", $current, get_pagenum_link($link), $link );
	}
	
	//	Link to last Page, plus ellipses, if necessary
	if ( !in_array( $max, $links ) ) {
		if ( !in_array( $max - 1, $links ) )
			echo '<li>&hellip;</li>' . "\n";
		
		$current = ( $paged == $max ) ? 'class="active"' : '';
		printf( '<li %s><a href="%s">%s</a></li>' . "\n", $current, get_pagenum_link($max), $max );
	}
	
	//	Next Post Link
	if ( get_next_posts_link() )
		printf( '<li>%s</li>' . "\n", get_next_posts_link(__('Next &raquo;', 'genesis')) );
	
	echo '</ul></div>' . "\n";
}
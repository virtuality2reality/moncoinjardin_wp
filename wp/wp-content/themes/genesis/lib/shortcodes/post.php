<?php
/**
 * This file defines return functions to be used as shortcodes
 * in the post-info and post-meta sections.
 * 
 * @example <code>[post_something]</code>
 * @example <code>[post_something before="<b>" after="</b>" foo="bar"]</code>
 */

/**
 * This function produces the date of post publication
 * 
 * @example <code>[post_date]</code> is the default usage
 * @example <code>[post_date format="F j, Y" before="<b>" after="</b>"]</code>
 */
add_shortcode('post_date', 'genesis_post_date_shortcode');
function genesis_post_date_shortcode($atts) {
	
	$defaults = array(
		'format' => get_option('date_format'),
		'before' => '',
		'after' => '',
		'label' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$output = sprintf( '<span class="date time published" title="%5$s">%1$s%3$s%4$s%2$s</span> ', $atts['before'], $atts['after'], $atts['label'], get_the_time($atts['format']), get_the_time('Y-m-d\TH:i:sO') );
	
	return apply_filters('genesis_post_date_shortcode', $output, $atts);
	
}

/**
 * This function produces the time of post publication
 * 
 * @example <code>[post_time]</code> is the default usage
 * @example <code>[post_time format="g:i a" before="<b>" after="</b>"]</code>
 */
add_shortcode('post_time', 'genesis_post_time_shortcode');
function genesis_post_time_shortcode($atts) {
	
	$defaults = array( 
		'format' => get_option('time_format'),
		'before' => '',
		'after' => '',
		'label' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$output = sprintf( '<span class="time published" title="%5$s">%1$s%3$s%4$s%2$s</span> ', $atts['before'], $atts['after'], $atts['label'], get_the_time($atts['format']), get_the_time('Y-m-d\TH:i:sO') );
	
	return apply_filters('genesis_post_time_shortcode', $output, $atts);
	
}

/**
 * This function produces the author of the post (display name)
 * 
 * @example <code>[post_author]</code> is the default usage
 * @example <code>[post_author before="<b>" after="</b>"]</code>
 */
add_shortcode('post_author', 'genesis_post_author_shortcode');
function genesis_post_author_shortcode($atts) {
	
	$defaults = array(
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$output = sprintf('<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', esc_html( get_the_author() ), $atts['before'], $atts['after']);
	
	return apply_filters('genesis_post_author_shortcode', $output, $atts);
	
}

/**
 * This function produces the author of the post (link to author URL)
 * 
 * @example <code>[post_author_link]</code> is the default usage
 * @example <code>[post_author_link before="<b>" after="</b>"]</code>
 */
add_shortcode('post_author_link', 'genesis_post_author_link_shortcode');
function genesis_post_author_link_shortcode($atts) {
	
	$defaults = array(
		'nofollow' => FALSE,
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$author = get_the_author();
	
	//	Link?
	if ( get_the_author_meta('url') ) {
		
		//	Build the link
		$author = '<a href="' . get_the_author_meta('url') . '" title="' . esc_attr( sprintf(__("Visit %s&#8217;s website"), $author) ) . '" rel="external">' . $author . '</a>';
		
	}
	
	$output = sprintf('<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', $author, $atts['before'], $atts['after']);
	
	return apply_filters('genesis_post_author_link_shortcode', $output, $atts);
	
}

/**
 * This function produces the author of the post (link to author archive)
 * 
 * @example <code>[post_author_posts_link]</code> is the default usage
 * @example <code>[post_author_posts_link before="<b>" after="</b>"]</code>
 */
add_shortcode('post_author_posts_link', 'genesis_post_author_posts_link_shortcode');
function genesis_post_author_posts_link_shortcode($atts) {
	
	$defaults = array(
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	// Darn you, WordPress!
	ob_start();
	the_author_posts_link();
	$author = ob_get_clean();
	
	$output = sprintf('<span class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</span>', $author, $atts['before'], $atts['after']);
	
	return apply_filters('genesis_post_author_shortcode', $output, $atts);
	
}

/**
 * This function produces the comment link
 * 
 * @example <code>[post_comments]</code> is the default usage
 * @example <code>[post_comments zero="No Comments" one="1 Comment" more="% Comments"]</code>
 */
add_shortcode('post_comments', 'genesis_post_comments_shortcode');
function genesis_post_comments_shortcode($atts) {
	
	$defaults = array(
		'zero' => __('Leave a Comment', 'genesis'),
		'one' => __('1 Comment', 'genesis'),
		'more' => __('% Comments', 'genesis'),
		'hide_if_off' => 'enabled',
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	if ( ( !genesis_get_option('comments_posts') || !comments_open() ) && $atts['hide_if_off'] === 'enabled' )
		return;
	
	// Darn you, WordPress!
	ob_start();
	comments_number($atts['zero'], $atts['one'], $atts['more']);
	$comments = ob_get_clean();
	
	$comments = sprintf('<a href="%s">%s</a>', get_comments_link(), $comments);

	$output = sprintf('<span class="post-comments">%2$s%1$s%3$s</span>', $comments, $atts['before'], $atts['after']);
	
	return apply_filters('genesis_post_comments_shortcode', $output, $atts);
	
}

/**
 * This function produces the tag link list
 * 
 * @example <code>[post_tags]</code> is the default usage
 * @example <code>[post_tags sep=", " before="Tags: " after="bar"]</code>
 */
add_shortcode('post_tags', 'genesis_post_tags_shortcode');
function genesis_post_tags_shortcode($atts) {
	
	$defaults = array(
		'sep' => ', ',
		'before' => __('Tagged With: ', 'genesis'),
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$tags = get_the_tag_list( $atts['before'], trim($atts['sep']) . ' ', $atts['after'] );
	
	if ( !$tags ) return;
	
	$output = sprintf('<span class="tags">%s</span> ', $tags);
	
	return apply_filters('genesis_post_tags_shortcode', $output, $atts);
	
}

/**
 * This function produces the category link list
 * 
 * @example <code>[post_categories]</code> is the default usage
 * @example <code>[post_categories sep=", "]</code>
 */
add_shortcode('post_categories', 'genesis_post_categories_shortcode');
function genesis_post_categories_shortcode($atts) {
	
	$defaults = array(
		'sep' => ', ',
		'before' => __('Filed Under: ', 'genesis'),
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$cats = get_the_category_list( trim($atts['sep']) . ' ' );
	
	$output = sprintf('<span class="categories">%2$s%1$s%3$s</span> ', $cats, $atts['before'], $atts['after']);
	
	return apply_filters('genesis_post_categories_shortcode', $output, $atts);
	
}

/**
 * This function produces the edit post link for logged in users
 * 
 * @example <code>[post_edit]</code> is the default usage
 * @example <code>[post_edit link="Edit", before="<b>" after="</b>"]</code>
 */
add_shortcode('post_edit', 'genesis_post_edit_shortcode');
function genesis_post_edit_shortcode($atts) {
	
	$defaults = array(
		'link' => __('(Edit)', 'genesis'),
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	// Darn you, WordPress!
	ob_start();
	edit_post_link($atts['link'], $atts['before'], $atts['after']); // if logged in
	$edit = ob_get_clean();
	
	$output = $edit;
	
	return apply_filters('genesis_post_edit_shortcode', $output, $atts);
	
}
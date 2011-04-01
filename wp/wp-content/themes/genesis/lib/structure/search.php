<?php
/**
 * Using a filter, we're replacing the default search form
 * with one of our own, specific to Genesis.
 *
 * @since 0.2
 */
add_filter('get_search_form', 'genesis_search_form');
function genesis_search_form() {
	
	$search_text = get_search_query() ? esc_attr( apply_filters( 'the_search_query', get_search_query() ) ) : apply_filters('genesis_search_text', esc_attr__('Search this website&hellip;', 'genesis'));
	$button_text = apply_filters( 'genesis_search_button_text', esc_attr__( 'Search', 'genesis' ) );
	
	$onfocus = " onfocus=\"if (this.value == '$search_text') {this.value = '';}\"";
	$onblur = " onblur=\"if (this.value == '') {this.value = '$search_text';}\"";
	
	$form = '
		<form method="get" class="searchform" action="' . get_option('home') . '/" >
			<input type="text" value="'. $search_text .'" name="s" class="s"'. $onfocus . $onblur .' />
			<input type="submit" class="searchsubmit" value="'. $button_text .'" />
		</form>
	';
	
	return apply_filters('genesis_search_form', $form, $search_text, $button_text);
}
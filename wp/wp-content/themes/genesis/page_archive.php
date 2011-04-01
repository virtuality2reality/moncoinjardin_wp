<?php
/**
 * WARNING: This file is part of the core Genesis framework. DO NOT edit
 * this file under any circumstances. Please do all modifications
 * in the form of a child theme.
 *
 * Template Name: Archive
 */
get_header(); ?>

<?php genesis_before_content_sidebar_wrap(); ?>
<div id="content-sidebar-wrap">
	
	<?php genesis_before_content(); ?>
	<div id="content">
	
		<?php genesis_before_loop(); ?>
		<div class="post hentry">

			<h1 class="entry-title"><?php _e("Site Archives", 'genesis'); ?></h1>

			<div class="entry-content">
				<div class="archive-page">

					<h4><?php _e("Pages:", 'genesis'); ?></h4>
					<ul>
						<?php wp_list_pages('title_li='); ?>
					</ul>

					<h4><?php _e("Categories:", 'genesis'); ?></h4>
					<ul>
						<?php wp_list_categories('sort_column=name&title_li='); ?>
					</ul>

				</div><!-- end .archive-page-->

				<div class="archive-page">

					<h4><?php _e("Authors:", 'genesis'); ?></h4>
					<ul>
						<?php wp_list_authors('exclude_admin=0&optioncount=1'); ?>   
					</ul>

					<h4><?php _e("Monthly:", 'genesis'); ?></h4>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>

					<h4><?php _e("Recent Posts:", 'genesis'); ?></h4>
					<ul>
						<?php wp_get_archives('type=postbypost&limit=100'); ?> 
					</ul>    

				</div><!-- end .archive-page-->
			</div><!-- end .entry-content -->

		</div><!-- end .post -->
		<?php genesis_after_loop(); ?>
	
	</div><!-- end #content -->
	<?php genesis_after_content(); ?>

</div><!-- end #content-sidebar-wrap -->
<?php genesis_after_content_sidebar_wrap(); ?>

<?php get_footer(); ?>
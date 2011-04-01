<?php
/**
 * WARNING: This file is part of the core Genesis framework. DO NOT edit
 * this file under any circumstances. Please do all modifications
 * in the form of a child theme.
 */
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

if ( post_password_required() ) { ?>
	<p class="alert"><?php _e('This post is password protected. Enter the password to view comments.', 'genesis'); ?></p>
<?php
	return;
}

genesis_before_comments();
genesis_comments();
genesis_after_comments();

genesis_before_pings();
genesis_pings();
genesis_after_pings();

genesis_before_comment_form();
genesis_comment_form();
genesis_after_comment_form();
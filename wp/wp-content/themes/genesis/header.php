<?php
/**
 * WARNING: This file is part of the core Genesis framework. DO NOT edit
 * this file under any circumstances. Please do all modifications
 * in the form of a child theme.
 */
genesis_doctype();
genesis_title();
genesis_meta();

wp_head(); // we need this for plugins
?>
</head>
<body <?php body_class(); ?>>
<?php genesis_before(); ?>

<div id="wrap">
<?php genesis_before_header(); ?>
<?php genesis_header(); ?>
<?php genesis_after_header(); ?>
<div id="inner">
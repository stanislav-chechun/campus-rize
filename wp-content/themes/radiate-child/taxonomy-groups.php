<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package ThemeGrill
 * @subpackage Radiate
 * @since Radiate 1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if(function_exists('add_post_in_group')) add_post_in_group(); ?>		

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>

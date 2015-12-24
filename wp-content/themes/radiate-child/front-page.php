<?php
/**
 * The template file to show the front page display.
 *
 * @package ThemeGrill
 * @subpackage Radiate
 * @since Radiate 1.0
 */

get_header(); ?>

	<!-- banner-wrap -->
	<div id="banner-wrap" class="wsite-background wsite-custom-background">
		<div id="banner">
			<h2>Campus Rise</h2>
			<p>Helping exemplary youth from low-income households get to and through college.</p>			
			<a class="wsite-button wsite-button-large wsite-button-highlight" href="<?php echo get_home_url(); ?>/index.php/donate/" >
				<span class="wsite-button-inner">Sponsor a student now</span>
			</a>		
		</div>
	</div>
	<!-- end banner-wrap -->

	<?php 

		$args = array(
			'category_name' => 'what-we-do',
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

	?>

	<div class="content-wrap">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 posts-what-we-do">
				<h2 class="wsite-content-title"><?php the_title(); ?></h2>
				<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<?php the_content(); ?>				
			</div>
		</div>
	</div>

	<?php				
			}
		} 
		wp_reset_postdata();

		$args = array(
			'posts_per_page' => 3,
			'category_name' => 'stages',
			'orderby' => 'ID',
			'order' => 'ASC'
		);

		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) {
	?>

	<div class="content-wrap">
		<hr class="styled-hr">
		<div class="row">

	<?php			
			while ( $posts->have_posts() ) {
				$posts->the_post();
	?>

			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center posts-stages">
				<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>				
				<h2 class="wsite-content-title font-size-x-large"><?php the_title(); ?></h2>
				<div class="paragraph">
					<?php the_content(); ?>				
				</div>
				<a class="more-link wsite-button wsite-button-small wsite-button-normal" title="<?php get_the_title(); ?>" href="<?php get_the_permalink(); ?>">
					<span class="wsite-button-inner"><?php echo __('Learn More')?></span>
				</a>
			</div>

	<?php }	?>

		</div>
	</div>

	<?php			
		} 
		wp_reset_postdata();
	?>	

<?php get_footer(); ?>

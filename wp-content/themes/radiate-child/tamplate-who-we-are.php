<?php
/*
Template Name: Who We Are
*/
get_header(); ?>

<div class="content-wrap text-page-wrapper">

<?php while (have_posts()) : the_post(); ?>			
	<div class="wwa-content"><?php the_content(); ?></div>			
<?php endwhile; ?>	

<?php
	$args = array(
		'post_type' => 'team',
		'posts_per_page'=> -1,
		'order' => 'ASC',				
	);
	$posts = new WP_Query( $args );

	if ( $posts->have_posts() ) {
?>

	<div class="row">

<?php
		$count = 0;
		$published_posts = wp_count_posts('team')->publish;
		while ( $posts->have_posts() ) {
			$posts->the_post();
			if ( $count < 2 ) {
?>
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 team tm-main">
<?php				
			}
			else {
?>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 team">
<?php
			}

			if ( has_post_thumbnail() ) {
?>
				<div class="tm-img"><?php the_post_thumbnail(); ?></div>
<?php 
			}
?>

			<div class="tm-caption">
				<p><?php echo get_post_meta($post->ID, 'tm_name', 1).' '.get_post_meta($post->ID, 'tm_surname', 1); ?></p>
				<p><?php echo get_post_meta($post->ID, 'tm_position', 1); ?></p>
			</div>
			<div class="tm-story"><?php the_content(); ?></div>
			<div class="tm-follow">
				<p><?php echo __('Follow').' '.get_post_meta($post->ID, 'tm_name', 1); ?></p>
				<div>
					<a href="mailto:<?php echo get_post_meta($post->ID, 'tm_email', 1); ?>" target="_blank"><i class="fa fa-envelope fa-2x"></i></a>
					<a href="<?php echo get_post_meta($post->ID, 'tm_linkedIn', 1); ?>" target="_blank"><i class="fa fa-linkedin-square fa-2x"></i></a>
					<a href="<?php echo get_post_meta($post->ID, 'tm_facebook', 1); ?>" target="_blank"><i class="fa fa-facebook-square fa-2x"></i></a>
				</div>
			</div>		
		</div>

<?php
			$count += 1;
			if ( ( $count < $published_posts) && ( $count == 2 || $count % 4 == 2 ) ) {
?>

	</div>
	<div class="row">

<?php				
			}
		}
?>

	</div>

<?php
	}
?>
		
</div>

<?php get_footer(); ?>
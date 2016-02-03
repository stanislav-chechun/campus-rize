<?php
/*
Template Name: Who We Are
*/
get_header(); ?>

<div class="content-wrap text-page-wrapper">

<?php while (have_posts()) : the_post(); ?>			
	<?php the_content(); ?>			
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

		while ( $posts->have_posts() ) {
			$posts->the_post();

?>
		<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
<?php

			if ( has_post_thumbnail() ) { 
				the_post_thumbnail(); 
			}

?>

			<div class="tm-caption">
				<p><?php echo get_post_meta($post->ID, 'tm_name', 1).' '.get_post_meta($post->ID, 'tm_surname', 1); ?></p>
				<p><?php echo get_post_meta($post->ID, 'tm_position', 1); ?></p>
			</div>

<?php
			the_content();
			echo get_post_meta($post->ID, 'tm_email', 1);
			echo get_post_meta($post->ID, 'tm_linkedIn', 1);
			echo get_post_meta($post->ID, 'tm_facebook', 1);
			
?>

		</div>

<?php

		}

?>

	</div>

<?php
	}

?>
		
</div>

<?php get_footer(); ?>
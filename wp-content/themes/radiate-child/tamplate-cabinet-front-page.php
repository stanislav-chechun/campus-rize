<?php
/*
Template Name: Cabinet Front Page
*/
get_header(); ?>

<div class="cabinet-front-page">
		
	<?php 
		while (have_posts()) : the_post();
	?>

			<div class="text-center">
				<?php the_content(); ?>
			</div>
			<div class="cabinet-wrap">

	<?php			 
			echo do_shortcode("[wp-recall]");					 	
	 	endwhile;

	?>	
			</div>

</div>

<?php get_footer(); ?>
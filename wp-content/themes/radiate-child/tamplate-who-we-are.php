<?php
/*
Template Name: Who We Are
*/
get_header(); ?>

<div class="content-wrap text-page-wrapper">
		
	<?php while (have_posts()) : the_post(); ?>			
		<?php the_content(); ?>			
	<?php endwhile; ?>	

</div>

<?php get_footer(); ?>
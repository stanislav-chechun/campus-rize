<?php
/*
Template Name: Stage One: Build
*/
get_header(); ?>

<div id="stage-one" class="content-wrap">
		
	<?php while (have_posts()) : the_post(); ?>			
		<?php the_content(); ?>			
	<?php endwhile; ?>	

</div>

<?php get_footer(); ?>
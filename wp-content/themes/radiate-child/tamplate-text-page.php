<?php
/*
Template Name: Text Page
*/
get_header(); ?>

<div class="content-wrap text-page">
		
	<?php 
		while (have_posts()) : the_post();		
		the_content(); 			
	 	endwhile; 
	?>	

</div>

<?php get_footer(); ?>
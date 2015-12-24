<?php
/*
Template Name: Testimonials
*/
get_header(); 

if ( has_post_thumbnail() ) {

$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
$url = $thumb['0'];

?>

<div id="testimonial-img" style="background-image:url(<?php echo $url; ?>);">
	<div>

		<?php 
			while (have_posts()) : the_post(); 			
				the_content();			
			endwhile; 
		?>

	</div>
</div>

<?php } ?>

<div class="content-wrap text-page">
	<div id="kudos_submit">

		<?php echo do_shortcode("[contact-form-7 id='139' title='Campus Rise Testimonial']"); ?>

	</div>
</div>

<?php get_footer(); ?>
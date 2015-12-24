<?php
/*
Template Name: Donate
*/
get_header(); ?>

<div class="content-wrap donate">
		
	<?php 

		$args = array(
			'category_name' => 'donate',
			'orderby' => 'ID',
			'order' => 'ASC'
		);

		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) {
	?>

		<div class="row">

	<?php			
			while ( $posts->have_posts() ) {
				$posts->the_post();
	?>

			<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 posts-height">
				<?php 
					if ( has_post_thumbnail() ) {
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
						$url = $thumb['0'];
				?> 
					
				<div class="donate-img" style="background-image:url(<?php echo $url; ?>);">
					<a title="<?php get_the_title(); ?>" href="<?php the_permalink(); ?>"></a>
				</div>
					
				<?php } ?>				
				
				<h2 class="">
					<a title="<?php get_the_title(); ?>" href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a>
				</h2>			
			</div>

	<?php }	?>

		</div>

	<?php			
		} 
		wp_reset_postdata();

	?>	

</div>

<?php get_footer(); ?>
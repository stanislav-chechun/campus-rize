<?php
/*
Template Name: Student Profile
*/
get_header(); ?>

<div class="content-wrap text-page student-profile">
		
	<?php 
		
		$id_user = $_GET['id_user'];

	 	if (isset($id_user)){
			
			$user_meta = get_user_meta($id_user); 
			$user_info = get_userdata($id_user);
			$user_image_from_profile = wp_get_attachment_image( $user_meta['photo_37'][0], 'medium' );
			$user_video_from_profile = wp_get_attachment_url($user_meta['video_42'][0] );
			$user_login = $user_info->user_nicename;

	?>

			<div class="for-media">

	<?php

				if ($user_video_from_profile != ''){

	?>
					<video controls="controls">
						<source src="<?php echo $user_video_from_profile; ?>">
					</video>
	<?php
				}
				elseif ($user_image_from_profile != ''){
					echo $user_image_from_profile;
				}
				else{
					echo get_avatar($id_user);
				}
	?>
			</div>

	<?php	 			 

			echo '<h2>' . $user_info->first_name .' '. $user_info->last_name . '</h2>'; 
			echo '<p>' . $user_info->description . '</p>';			
			echo '<p>' . $user_meta['story_54'][0] . '</p>';	

		}	

		$args = array(
			'post_type' => 'give_forms', 
			'meta_key' => 'autor_login', 
			'meta_value' => $user_login,
		);

		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) {
	?>

			<div class="row">

	<?php			
				while ( $posts->have_posts() ) {
					$posts->the_post();
	?>

					<div>
						<h2 class="wsite-content-title"><?php the_title(); ?></h2>
						<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>				
					</div>

	<?php }	?>

			</div>

	<?php			
		} 
		wp_reset_postdata();	
	?>	

</div>

<?php get_footer(); ?>
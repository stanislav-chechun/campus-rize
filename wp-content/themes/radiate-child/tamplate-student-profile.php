<?php
/*
Template Name: Student Profile
*/
get_header(); ?>

<div class="text-page student-profile">
		
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
			//echo '<p>' . preg_replace("/[\r\n|\n|\r]+/", "</p><p>", nl2br($user_meta['story_54'][0])) . '</p>';
			echo '<p>' . preg_replace("/\n/", "</p><p>", $user_meta['story_54'][0]) . '</p>';	

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
				<div class="owl-carousel">
				<!--<div id="carousel-wishes" class="carousel slide" data-ride="carousel">					  
					<div class="carousel-inner" role="listbox">-->
					    

	<?php			
						while ( $posts->have_posts() ) {
							$posts->the_post();
	?>
					
							<!--<div class="item">-->
							<div>		      
	<?php 
						    		if ( has_post_thumbnail() ) { 
						    			the_post_thumbnail(); 
						    		} 
						    		else {
	?>
										<img src="<?php echo get_home_url(); ?>/wp-content/uploads/2016/01/no_photo.png" alt="">
	<?php
						    		}
	?>
						    	<!--<div class="carousel-caption">-->						 
						        	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						    	<!--</div>-->
						    </div>
					    

	<?php }	?>					    
					    
					<!--</div>
					  
					<a class="left carousel-control" href="#carousel-wishes" role="button" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					    <span class="sr-only">Previous</span>
					</a>
					<a class="right carousel-control" href="#carousel-wishes" role="button" data-slide="next">
					    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					    <span class="sr-only">Next</span>
					</a>
				</div>-->
				</div>
			</div>

	<?php			
		} 
		wp_reset_postdata();	
	?>	

</div>

<?php get_footer(); ?>
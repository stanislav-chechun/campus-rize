<?php
/*
Template Name: Sponsor A Student
*/
get_header(); ?>

<div class="content-wrap donate">
		
<?php

		$parametrs = array(
		    'role' => 'Student'
		);
		 
		$user_query = new WP_User_Query($parametrs);
		 
		if (empty($user_query->results) == FALSE) : 
?>
			
			<div class="row">

<?php
		    	foreach ($user_query->results as $user) :		                		

?>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 posts-height">
						<div class="donation-item">	

<?php

							echo get_avatar($user->ID);							
							echo '<h2><a href="' . get_home_url() . '/student-profile?id_user=' . $user->ID .'">' . $user->first_name .' '. $user->last_name . '</a></h2>'; 
							echo '<p>' . $user->description . '</p>';							

							$args = array(
								'post_type' => 'give_forms', 
								'meta_key' => 'autor_login', 
								'meta_value' => $user->user_login,
							);

							$posts = new WP_Query( $args );

							if ( $posts->have_posts() ) {

								$goal_sum = 0;												
								$earnings_sum = 0;			
								
								while ( $posts->have_posts() ) {

									$posts->the_post();
						
									$post_id = get_the_ID();
									$goal_give = get_post_meta($post_id, '_give_set_goal');												
									$earnings = get_post_meta($post_id, '_give_form_earnings');
										
									$goal = str_replace(",", "", $goal_give);
									
									$goal_sum += $goal[0];
									$earnings_sum += $earnings[0];								

								}

								$progress_sum = $earnings_sum/$goal_sum*100;	

?>					    
										    
							<div class="progress">
								<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress_sum; ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo 'width: ' . $progress_sum . '%;'; ?>">
									<span class="sr-only"><?php echo $progress_sum . "%"; ?> Complete</span>
								</div>
							</div>
							<div class="progress-description">
								<p>
									<span><?php echo $progress_sum . "%"; ?></span>	funded
								</p>
								<p>
									<span><?php echo "$" . $goal_sum; ?></span> pledged
								</p>
							</div>
										    			

<?php			
							} 
							wp_reset_postdata();	
?>						

						</div>
					</div>
<?php
		    	endforeach;
?>
			
			</div>

<?php
		endif;
?>	

</div>

<?php get_footer(); ?>
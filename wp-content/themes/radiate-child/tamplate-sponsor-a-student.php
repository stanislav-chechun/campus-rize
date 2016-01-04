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
		        /*print get_avatar($user->ID);
		        print '<p><a href="' . home_url() . "/author/{$user->user_nicename}\">{$user->display_name}</a></p>";
		        $description = get_user_meta($user->ID, 'description', true);
		        print "<p>Немного о себе: {$description}</p>";*/
		        
		        /*$author_info = get_userdata($author->ID);
        		echo '<p>'.$author_info->first_name.' '.$author_info->last_name.'</p>';*/        		

	?>

				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 posts-height">

				<div class="donation-item">	

					<?php

						echo get_avatar($user->ID);
						$id_user = $user->ID;
						echo '<h2><a href="' . get_home_url() . '/student-profile?id_user=' . $id_user .'">' . $user->first_name .' '. $user->last_name . '</a></h2>'; 
						echo '<p>' . $user->description . '</p>';
						/*$a = get_metadata('user', $user->ID);
						echo "<pre>";
						var_dump($a); 
						echo "</pre>";*/
						//echo '<li>' . get_post_meta( $query->post->ID, 'director', true ). '</li>';
						//echo '<p>' . $user->photo . '</p>';

					

					?> 

					<!--<div class="donation-progress">				
					
						<?php 

							$post_id = get_the_ID();
							$goal = get_post_meta($post_id, '_give_set_goal');
							$content = get_post_meta($post_id, '_give_form_content');
							$earnings = get_post_meta($post_id, '_give_form_earnings');
							$progress = $earnings[0]/$goal[0]*100;	

							echo $content[0];

						?>

						<div class="progress">
						  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100" style="<?php echo 'width: ' . $progress . '%;'; ?>">
						    <span class="sr-only"><?php echo $progress . "%"; ?> Complete</span>
						  </div>
						</div>

						<div class="progress-description">
							<p>
								<span><?php echo $progress . "%"; ?></span>	funded
							</p>
							<p>
								<span><?php echo "$" . $goal[0]; ?></span> pledged
							</p>
						</div>						

					</div>-->

				</div>

			</div>
	<?php

		    endforeach;
		endif;
	?>

			</div>

	<!--<?php /*$v=get_user_meta(2); 
	echo "<pre>";
	var_dump($v); 
	echo "</pre>";*/
	?>-->

</div>

<?php get_footer(); ?>
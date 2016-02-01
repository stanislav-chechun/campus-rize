<?php

add_action('init','add_tab_land_your_dream_job');

	function add_tab_land_your_dream_job(){

		$user = $_GET['user'];
		$user_info = get_userdata($user);

		if ( $user_info->roles[0] == 'student' ){		
	    	rcl_tab('dream_job', 'land_your_dream_job_showup', 'Land Your Dream Job', array('public'=>0,'class'=>'fa-briefcase','order'=>11));
	    }
	}

	function land_your_dream_job_showup(){
		//$user = $_GET['user'];		

	 	//if (isset($user)){			
			
			//$user_info = get_userdata($user);			
			//$user_login = $user_info->user_nicename;

			$args = array(
				'post_type' => 'professions', 
			);

			$posts = new WP_Query( $args );

			if ( $posts->have_posts() ) {

				//$goal_sum = 0;												
				//$earnings_sum = 0;

				/*$awards .= '<div class="your-awards">
								<h3>Award Summary</h3>
								<p>The following reflects awarded aid. Refer to your Award Notification e-mail for additional details.</p>
								<table>
									<tr>
									    <th>Award Description</th>
									    <th>Category</th>
									    <th>Offered, $</th>
									    <th>Accepted, $</th>
								   	</tr>';*/

				while ( $posts->have_posts() ) {
					$posts->the_post();
					$post_id = get_the_ID();
					//$post_id = get_the_ID();
					//$goal_give = get_post_meta($post_id, '_give_set_goal');												
					//$earnings = get_post_meta($post_id, '_give_form_earnings');

					//$goal = str_replace(",", "", $goal_give);
									
					//$goal_sum += $goal[0];
					//$earnings_sum += $earnings[0];

					/*$awards .= '<tr>
								   	<td><a href="'. get_the_permalink() .'">' . get_the_title().'</a></td>
								   	<td>wishes</td>
								   	<td>'. $earnings[0]. '</td>
								   	<td>'. $goal_give[0].'</td>
								</tr>';*/

					$profession .= '<div class="row profession">
										<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">';

					if ( has_post_thumbnail() ) { 
						//$profession .= the_post_thumbnail(); 
					} 
					else {
						$profession .='<img src="'.get_home_url().'/wp-content/uploads/2016/01/no_photo.png" alt="">';
					}

					$profession .= '</div>							        
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<h2><a href="'.get_the_permalink().'">'.get_the_title().'</a></h2>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
										<p>Industry: '.get_post_meta($post_id, "prof_industry", 1).'</p>
										<p>Location: '.get_post_meta($post_id, "prof_location", 1).'</p>
									</div>									
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
										<p><i class="fa fa-envelope-o"></i>'.get_post_meta($post_id, "prof_email", 1).'</p>
										<p><i class="fa fa-phone"></i>'.get_post_meta($post_id, "prof_tel", 1).'</p>
									</div>
									</div>';
									

				}

				/*$awards .= '<tr class="totals">
						   		<td colspan="2">Totals:</td>
						   		<td>'. $earnings_sum .'</td>
						   		<td>'. $goal_sum .'</td>
						   	</tr>
						</table>
						<a href="#" class="cabinet-btn">Redeem Awards</a>
						<a href="#" class="cabinet-btn">Transfer Funds</a>
					</div>';

				return $awards;*/
				return $profession;
			}
			
			wp_reset_postdata();
		//}    
	}

?>
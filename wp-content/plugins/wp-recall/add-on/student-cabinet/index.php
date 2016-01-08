<?php

add_action('init','add_tab_view_your_awards');

	function add_tab_view_your_awards(){
	    rcl_tab('awards', 'view_your_awards_showup', 'View your awards', array('public'=>0,'class'=>'fa-dollar','order'=>11));
	}

	function view_your_awards_showup(){
		$user = $_GET['user'];		

	 	if (isset($user)){			
			
			$user_info = get_userdata($user);			
			$user_login = $user_info->user_nicename;

			$args = array(
				'post_type' => 'give_forms', 
				'meta_key' => 'autor_login', 
				'meta_value' => $user_login,
			);

			$posts = new WP_Query( $args );

			if ( $posts->have_posts() ) {

				$goal_sum = 0;												
				$earnings_sum = 0;

				$awards .= '<table>
								<caption>Award Summary</caption>
								<tr>
								    <th>Award Description</th>
								    <th>Category</th>
								    <th>Offered, $</th>
								    <th>Accepted, $</th>
							   	</tr>';

				while ( $posts->have_posts() ) {
					$posts->the_post();
					$post_id = get_the_ID();
					$goal_give = get_post_meta($post_id, '_give_set_goal');												
					$earnings = get_post_meta($post_id, '_give_form_earnings');

					$goal = str_replace(",", "", $goal_give);
									
					$goal_sum += $goal[0];
					$earnings_sum += $earnings[0];

					$awards .= '<tr>
								   	<td>' . get_the_title().'</td>
								   	<td>wishes</td>
								   	<td>'. $earnings[0]. '</td>
								   	<td>'. $goal_give[0].'</td>
								</tr>';

				}

				$awards .= '<tr>
						   		<td colspan="2">Totals:</td>
						   		<td>'. $earnings_sum .'</td>
						   		<td>'. $goal_sum .'</td>
						   	</tr>
						</table>';

				return $awards;
			}
			wp_reset_postdata();
		}    
	}

?>
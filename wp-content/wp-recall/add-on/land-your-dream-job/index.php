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

		$args = array(
			'post_type' => 'professions',
			'posts_per_page'=> -1,				
		);
		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) {

			while ( $posts->have_posts() ) {
				$posts->the_post();
				$post_id = get_the_ID();					

				$profession .= '<div class="row profession">
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">';

				if ( has_post_thumbnail() ) { 
					$profession .= the_post_thumbnail(); 
				} 
				else {
					$profession .='<img src="'.get_home_url().'/wp-content/uploads/2016/01/no_photo.png" alt="">';
				}
				//<h2><a href="'.get_the_permalink().'">'.get_the_title().'</a></h2>
				$profession .= '</div>							        
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<h2>'.get_the_title().'</h2>
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
				 ?>
				<div class="navigation">
<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
</div>
			<?php }	
			return $profession;

		}			
		wp_reset_postdata();  
	}

?>
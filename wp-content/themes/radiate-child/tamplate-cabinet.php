<?php
/*
Template Name: Cabinet
*/
get_header(); ?>

<div class="content-wrap text-page">
		
	<?php 
		while (have_posts()) : the_post();		
			the_content(); 			
	 	
		 	$user = $_GET['user'];
		 	$user_info = get_userdata($user);
			//echo $user_info->roles[0];

			/*if ( $user_info->roles[0] == 'student' ){
				//echo "this is student";
				//rcl_activate_addon('meet-mentor');
				//rcl_deactivate_addon('meet-mentor');
			}

			if ( $user_info->roles[0] == 'mentor' ){
				//echo "this is student";
				//rcl_deactivate_addon('meet-mentor');
				//<span class="rcl-tab-button" data-tab="meet-mentor" id="tab-button-meet-mentor"><a href="http://localhost/campus-rize/student-member-login/?user=2&amp;tab=meet-mentor" class="recall-button block_button"><i class="fa fa-calendar"></i><span>Meet your Mentor</span></a></span>
			}*/	 
		 	
	 	endwhile;

	?>	

</div>

<?php get_footer(); ?>
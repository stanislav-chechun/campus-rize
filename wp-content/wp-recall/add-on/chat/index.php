<?php

add_action('init','add_tab_chat');

	function add_tab_chat(){

		$user = $_GET['user'];
		$user_info = get_userdata($user);

		//if ( $user_info->roles[0] == 'mentor' ){
	    	//rcl_tab('chat', 'chat_showup', 'Chat', array('public'=>0,'class'=>'fa-calendar','order'=>11, 'output'=>'sidebar'));
	    	//rcl_tab('chat', 'chat_showup', 'Chat', array('public'=>0,'class'=>'fa-calendar','order'=>11));
	    	//rcl_tab('chat', 'contacts_showup', 'Chat', array('public'=>0,'class'=>'fa-comments-o','order'=>11));
		//}
	}

	//function chat_showup(){

		//return rcl_get_button_tab(array('name'=>'Chat with students','id_tab'=>'chat-with-students'));
		/*return '<div>'.rcl_get_button_tab(array('name'=>'Chat with students','id_tab'=>'chat-with-students'))
				.rcl_get_button_tab(array('name'=>'Groups','id_tab'=>'groups')).'</div>';*/
		
		
		/*$parametrs = array(
		    'role' => 'Student'
		);
		 
		$user_query = new WP_User_Query($parametrs);
		 
		if (empty($user_query->results) == FALSE) : 

		    	foreach ($user_query->results as $user) :

							//echo get_avatar($user->ID);							
							echo '<h2><a href="' . get_home_url() . '/student-member-login?user=' . $user->ID .'">' . $user->first_name .' '. $user->last_name . '</a></h2>'; 							
							

		    	endforeach;	

		endif;*/
		//return $user_info->ID;

		//return do_shortcode("[userlist inpage='10' orderby='display_name' order='ASC' exclude='".$user_info->ID."' filters='0' search_form='1']");

	 	
	//}
	add_filter('tab_data_rcl','edit_privat_tab_data');

	function edit_privat_tab_data($data){
	    if($data['id']!='privat') return $data; 
	    $data['name'] = 'Chat'; 
	    $user = $_GET['user'];
	    global $current_user;
		get_currentuserinfo();
		if($current_user->ID!=$user){
			$data['name'] = ''; 

	/*?>

	<style type="text/css">
	    #chat-inner-tabs a{
	    	display: none;
	    }
	</style>

	<?php*/

		}
	    return $data;
	}

?>
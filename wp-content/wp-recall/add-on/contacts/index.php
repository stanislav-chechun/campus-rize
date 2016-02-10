<?php

add_action('init','add_tab_chat_with_students');

	function add_tab_chat_with_students(){

		$user = $_GET['user'];
		$user_info = get_userdata($user);

		//if ( $user_info->roles[0] == 'mentor' ){	    	
	    	rcl_tab('contacts', 'contacts_showup');
		//}
	}

	function contacts_showup(){

		$user = $_GET['user'];
		$user_info = get_userdata($user);		
		
		return do_shortcode("[userlist template='avatars' inpage='10' orderby='display_name' order='ASC' exclude=$user_info->ID filters='0' search_form='0']");
	 	
	}

?>
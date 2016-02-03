<?php

	add_filter('tab_data_rcl','edit_privat_tab_data');

	function edit_privat_tab_data($data){
	    if($data['id']!='privat') return $data; 
	    $data['name'] = 'Chat'; 
	    $user = $_GET['user'];
	    global $current_user;
		get_currentuserinfo();
		if($current_user->ID!=$user){
			$data['name'] = '';
		}
	    return $data;
	}

?>
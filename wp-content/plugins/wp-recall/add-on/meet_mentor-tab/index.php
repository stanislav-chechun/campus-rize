<?php
//Create new tab for meet mentor tab. Info for mentor

add_action('init','add_tab_meet_mentor');
function add_tab_meet_mentor(){
    rcl_tab('meet_mentor','meet_mentor_recall_block','Meet your mentor',array('public'=>0,'class'=>'fa-hand-paper-o','order'=>23));
}
//class - http://fontawesome.io/icons/

function meet_mentor_recall_block($user_lk){
    
    $user_data = get_userdata($user_lk);
    $user_login = $user_data->user_login;
    $mentor_id = get_user_meta($user_lk, 'mentor_for_student');
    $mentor_id =  $mentor_id[0];
    
    $mentor_vimeo = get_user_meta($mentor_id, 'vimeo_mentor', 1);
    $mentor_youtube = get_user_meta($mentor_id, 'youtube_mentor', 1);
    $mentor_data = get_userdata($mentor_id);
    ////
    $display_author_info = true;
    
    if( $mentor_id){
        $html .= '<div class="container-fluid" id="mentor_tab">';
        $html .= '<div class="row">';
        
            $html .= '<div class="col-md-8 col-xs-12" id="video_mentor">';
                if($mentor_youtube != ''){
                    $html .= display_youtube_video( $mentor_youtube, $display_author_info);
                }
                elseif ( $mentor_vimeo !='') {
                    $html .= display_vimeo_video($mentor_vimeo, $display_author_info);
                }
                else{
                    $html .= get_student_thumbnail(false, $display_author_info);
                }
            $html .= '</div>';
            
            $html .= '<div class="col-md-4 col-xs-12" id="text_mentor">';
                $html .= __('This is your mentor: ');
                $html .= $mentor_data->display_name;
            $html .= '</div>';
            
        $html .= '</div>';
        $html .='</div>';
        
    } else{
    
        $html .= '<div class="center-block">';
            $html .= '<p class="bg-warning">';
                $html .= 'Sorry, you have no mentor! Be patient!';
            $html .= '</p>';
        $html .= '</div>';
    }
    return $html;
}

add_action('init', 'kp_process_meet_mentor');

function kp_process_meet_mentor() {

	
 }
 
 
 add_action('init','add_notify_meet_mentor');
function add_notify_meet_mentor(){ 
    
}

function add_tab_meet_mentor_rcl($array_tabs){
	//meet_mentor - идентификатор вкладки дополнения
	//emeet_mentor_recall_block - название функции формирующей контент вкладки дополнения
	$array_tabs['meet_mentor']='meet_mentor_recall_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_meet_mentor_rcl');
?>
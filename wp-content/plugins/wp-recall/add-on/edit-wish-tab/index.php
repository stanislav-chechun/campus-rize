<?php
//Create new tab for edit student's wish
//use edit_ajax.php for ajax 
add_action('init','add_tab_edit_wish');
function add_tab_edit_wish(){
    rcl_tab('edit_wish','edit_recall_block','Edit your wish',array('public'=>0,'class'=>'fa-pencil-square-o','order'=>21));
}
//class - http://fontawesome.io/icons/

add_action( 'wp_enqueue_scripts', 'ajax_script_edit' );
function ajax_script_edit() {
	wp_enqueue_script('edit_wish_script',
		plugins_url( '/js/edit_wish.js', __FILE__ ),
		array('jquery'),
                false,
                true
	);
}    


function edit_recall_block($user_lk){
    
    $user_data = get_userdata($user_lk);
    $user_login = $user_data->user_login;
    $args = array(
	'post_type' => 'give_forms',
	'meta_key' => 'autor_login',
        'meta_value' => $user_login
    );
    
    $query = new WP_Query( $args );
    
    if( $query->have_posts() ){
        $html .= 
                '<table class="table table-hover">
                    <thead><th>The aim</th><th>The percentage of completion</th><th>Edit</th></thead>'; 
        while ( $query->have_posts() ) {
            $query->the_post();
            $form_id = get_the_ID();
            $amount_goal  = get_post_meta( $form_id, '_give_set_goal', true );
            $amount_have = get_post_meta( $form_id, '_give_form_earnings', true );
            $percentage = round( ($amount_have/$amount_goal)*100, 2);
          
            $html .= '<tr><td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
            $html .= '<td>' . $percentage . '%' . '</td>';
            //$html .= '<td>' . '<a href="#results"><button name="wish_id" id="wish_id" type="button" class="btn btn-primary btn-lg btn-block wish_id" value="' . $form_id . '">' . 
            $html .= '<td>' . '<a href="#results"><button name="wish_id" id="wish_id" type="button" class="btn btn-lg btn-block wish_id" value="' . $form_id . '">' .          
                        'Edit this wish' . '</button></a>' . '</td>';
                
                $html .=  '</tr>';
        }

        $html .= '</table>';
               
    } else{
        $html .= '<div class="center-block">';
            $html .= '<p class="bg-warning">';
                $html .= __('Sorry, you have no one donations forms! Start with us!');
            $html .= '</p>';
        $html .= '</div>';
    }
    wp_reset_postdata();
         
    //Posts that were active and now is waiting for updates
    $args_2 = array(
	'post_type' => 'give_forms',
        'post_status' => 'pending',
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key' => 'autor_login',
			'value' => $user_login
		),
		array(
			'key' => 'wish_status_active',
			'value' => 'pending_admin'
		)
	) 
    );
     
    $query_2 = new WP_Query( $args_2 );   
    if( $query_2->have_posts() ){
        $html .= '<p class="text-center text-uppercase">' . __('List of desire awaiting confirmation') . '</p>';
        $html .= 
                '<table class="table table-hover">
                    <thead><th>The aim</th><th>The percentage of completion</th><th>Status</th></thead>'; 
        while ( $query_2->have_posts() ) {
            $query_2->the_post();
            $form_id_2 = get_the_ID();
            $amount_goal  = get_post_meta( $form_id_2, '_give_set_goal', true );
            $amount_have = get_post_meta( $form_id_2, '_give_form_earnings', true );
            $percentage = round( ($amount_have/$amount_goal)*100, 2);
          
            $html .= '<tr><td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
            $html .= '<td>' . $percentage . '%' . '</td>';
            $html .= '<td>' . '<p class="bg-warning">' .  __('Pending. Your wish is waiting for the confirmation by administrator') . '</p>' . '</td>';
                
                $html .=  '</tr>';
        }

        $html .= '</table>';
               
    }
    
    
    wp_reset_postdata();   
    $html .= '<div id="results"></div>';
    
    return $html;
}

add_action('init', 'kp_process_edit');

function kp_process_edit() {
    
    $user_id = get_current_user_id($user_id);
    $user_data = get_userdata($user_id);
    
    if( isset( $_POST['post_id_edit'], $_POST['title_form_edit'], $_POST['goal_form_edit'], $_POST['content_form_edit'] ) && $_POST['kp_wish_edit'] == 'process_kp_wish' ) {
            
            if( ! wp_verify_nonce( $_POST['kp_nonce'], 'kp_nonce' ) ) {
                return;
            }
            //var_dump($_POST); //////////////////
            if( $_POST['title_form_edit'] == '' || $_POST['goal_form_edit'] == '' || $_POST['content_form_edit'] ==''){
                $location_fail = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=edit_wish&kp-message=wish_void';
                wp_redirect( $location_fail ); exit;
            }
            
            if(! validate_int( $_POST['goal_form_edit'], 1000000 )){
                $location_fail = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=edit_wish&int-message=goal_failed';
                wp_redirect( $location_fail ); exit;
            }
            
            //Retrieve data POST 
            $post_id = intval($_POST['post_id_edit']);
            $wish_title = sanitize_title($_POST['title_form_edit']);
            $wish_goal = sanitize_text_field($_POST['goal_form_edit']); 
            //Create properly format 
            $wish_goal = number_format($wish_goal, 2, '.', '');            
            $wish_content = sanitize_text_field($_POST['content_form_edit']);
            $wish_youtube = isset($_POST['youtube_form_edit'])? sanitize_text_field($_POST['youtube_form_edit']) : '';
            $wish_vimeo = isset($_POST['vimeo_form_edit'])?sanitize_text_field($_POST['vimeo_form_edit']) : '';
            $author_login = $user_data->user_login;
            
            
    //Update meta data
    update_post_meta( $post_id, '_give_set_goal', $wish_goal );
    update_post_meta( $post_id, '_give_form_content', $wish_content );
    update_post_meta( $post_id, 'youtube', $wish_youtube);
    update_post_meta( $post_id, 'vimeo', $wish_vimeo);
    //For those wishes that were active bur now is waiting for admin's permission
    update_post_meta( $post_id, 'wish_status_active', 'pending_admin' );
    
    // Data array
    $post_edit = array();
    $post_edit['ID'] = $post_id;
    $post_edit['post_title'] = $wish_title;
    $post_edit['post_name'] = $wish_title;
    $post_edit['post_status'] = 'pending';

    wp_update_post( $post_edit );
   
   // Upload thumbnail
    if( isset( $_FILES["image_form_edit"]["name"]) && $_FILES["image_form_edit"]["name"] !=''){
        $err_images = true;
        if ( $_FILES["image_form_edit"]["error"] == 0 && $post_id) {  
            //&& current_user_can( 'edit_post', $post_id )
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
            
            if(has_post_thumbnail()){
                delete_post_thumbnail($post_id);
            }
            
            $attachment_id = media_handle_upload( 'image_form_edit', $post_id );

            if($attachment_id ){
                set_post_thumbnail($post_id, $attachment_id);
            }

            if ( is_wp_error( $attachment_id ) ) {
                    $err_images = false;
            } 
        } else {
            $err_images = false;
        }
        
       if( $err_images == false ){
            $location_fail = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=edit_wish&kp-message=images_fail';
            wp_redirect( $location_fail ); exit;
       } 
    }
    
    //unset Data Post
    unset($_POST['post_id_edit'], $_POST['title_form_edit'], $_POST['goal_form_edit'], $_POST['content_form_edit'], $_POST['youtube_form_edit'], 
            $_POST['vimeo_form_edit'], $_POST['kp_nonce']);
    
        if( isset($user_id, $wish_title, $wish_goal, $wish_content) && $post_id > 0){
            $location_ok = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=edit_wish&kp-message=wish_updated';
            wp_redirect( $location_ok ); exit;
        } 
    }
 }
 
 
 add_action('init','add_notify_update_wish');
function add_notify_update_wish(){ 
    $complete =  __('Updating completed') ;
    $goal = __('The sum must be integer and less than $1 000 000');
    $void = __('The title, the sum and the content in the form must be filled');
    $int_failed = 'The amount that can be transferred must be: integer, should be more than a zero, do not exceed the maximum amount possible for transfer.';
    $images_fail = __('There is an error in uploading the image or you have not permission to upload the image! But your wish was created!');
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'wish_updated'){ rcl_notice_text($complete,'success');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'int_failed'){ rcl_notice_text($int_failed,'warning');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'wish_void'){ rcl_notice_text($void,'warning');}
    if (isset($_GET['int-message']) && sanitize_text_field($_GET['int-message']) == 'goal_failed'){ rcl_notice_text($goal,'warning');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'images_fail'){ rcl_notice_text($images_fail,'warning');}
}

function add_tab_edit_wish_rcl($array_tabs){
	//edit_wish - идентификатор вкладки дополнения
	//edit_recall_block - название функции формирующей контент вкладки дополнения
	$array_tabs['edit_wish']='edit_recall_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_edit_wish_rcl');
?>
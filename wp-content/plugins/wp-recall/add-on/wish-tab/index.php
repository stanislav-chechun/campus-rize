 <?php
//Create new tab for the map
add_action('init','add_tab_donation_form');
function add_tab_donation_form(){
    rcl_tab('donation_form','form_donation_block','Create your wish',array('public'=>0,'class'=>'fa-plus-square','order'=>18));
   
}
//class - http://fontawesome.io/icons/

function form_donation_block($user_lk){
            
//    $html .= do_shortcode('[public-form wp_editor]');
    
    //Repetition
//    include_once RCL_PATH .'/add-on/publicpost/rcl_publicform.php';
//    $ss = new Rcl_PublicForm($type_editor=1, $wp_editor=1 );
//    global $editpost,$rcl_options,$formfields,$formData;
//    rcl_publication_editor();
//    
//   if(isset($rcl_options['rcl_editor_buttons'])){
//			$icons = array(
//				'text'=>'fa-align-left',
//				'header'=>'fa-header',
//				'image'=>'fa-picture-o',
//				'html'=>'fa-code',
//			);
//			$names = array(
//				'text'=>__('Text Box','wp-recall'),
//				'header'=>__('Subtitle','wp-recall'),
//				'image'=>__('Image','wp-recall'),
//				'html'=>__('HTML- code','wp-recall'),
//			);
//
//			foreach($rcl_options['rcl_editor_buttons'] as $type){
//				$buttons[] = '<li><a href="#" title="'.$names[$type].'" class="get-'.$type.'-box" onclick="return rcl_add_editor_box(this,\''.$type.'\');"><i class="fa '.$icons[$type].'"></i></a></li>';
//			}
//
//			if($buttons){
//				$panel = '<div class="rcl-tools-panel">
//						<ul>'
//							.implode('',$buttons)
//						.'</ul>
//						</div>';
//			}
//		}
//    $html .= '<div class="rcl-public-editor">
//
//			<div class="rcl-editor-content">
//				'.rcl_get_editor_content($content).'
//			</div>
//			'.$panel.'
//		</div>';
////   
//     
    $html .= wp_editor( '', 'wishwpeditor', array('textarea_name' => 'content') );
    $html .= '<form  class="form-horizontal" enctype="multipart/form-data" id="wish_table" method="post">';
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="title_form">' . __( 'The title of your goal*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="title_form" class="form-control"  id="title_form" placeholder="Title" required type="text" value=""/>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="goal_form">' . __( 'The sum you need for your goal*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="goal_form" class="form-control"  id="goal_form" placeholder="Amount" type="number" required value=""/>';
                        $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="content_form">' . __( 'The content you want to display*: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<textarea id="content_form" name="content_form" class="form-control" required rows="10"></textarea>';
                        $html .= '</div>';
                        
                $html .= '</div>';

                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="youtube_form">' . __( 'https://www.youtube.com/watch?v=: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="youtube_form" class="form-control"  id="youtube_form" placeholder="Fiil in the ID of your video from youtube.com" type="text" value=""/>';
                            $html .= '<span id="helpBlock" class="help-block">';
                                $html .= 'For example: mgmVOuLgFB0';
                            $html .= '</span>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="vimeo_form">' . __( 'https://player.vimeo.com/video/ ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="vimeo_form" class="form-control"  id="vimeo_form" placeholder="Fiil in the ID of your video from vimeo.com" type="text" value=""/>';
                            $html .= '<span id="helpBlock" class="help-block">';
                                $html .= 'For example: 149253903';
                            $html .= '</span>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="image_form">' . __( 'You can upload a photo for the goal') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input  type="file" name="image_form" class="form-control"  id="image_form" multiple="false" />';
                        $html .= '</div>';
                $html .= '</div>';

                $html .= '<input type="hidden" name="kp_wish" value="process_kp_wish"/>';
                $html .=  wp_nonce_field('kp_nonce', 'kp_nonce');
                //$html .= '<p><input class="btn btn-default" type="submit" value="Create">';
                //$html .= '<input class="btn btn-default" type="reset" value="Reset"></p>';
                $html .= '<input class="wsite-button-white" type="reset" value="Reset">';
                $html .= '<input class="wsite-button" type="submit" value="Create">';
            $html .= '</form>'; 
            
    
    return $html;
 }
 
add_action('init', 'kp_process_create');

function kp_process_create() {
    $user_id = get_current_user_id($user_id);
    $user_data = get_userdata($user_id);
    if( isset( $_POST['title_form'], $_POST['goal_form'], $_POST['content_form'] ) && $_POST['kp_wish'] == 'process_kp_wish' ) {
            
            if( ! wp_verify_nonce( $_POST['kp_nonce'], 'kp_nonce' ) ) {
                return;
            }
           // var_dump($_POST); //////////////////
            if( $_POST['title_form'] == '' || $_POST['goal_form'] == '' || $_POST['content_form'] ==''){
                $location_fail = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=donation_form&kp-message=wish_void';
                wp_redirect( $location_fail ); exit;
            }
            
            if(! validate_int( $_POST['goal_form'], 1000000 )){
                $location_fail = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=donation_form&int-message=goal_failed';
                wp_redirect( $location_fail ); exit;
            }
            //Retrieve data POST 
            $wish_title = sanitize_title($_POST['title_form']);
            $wish_goal = sanitize_text_field($_POST['goal_form']); 
            //Create properly format 
            $wish_goal = number_format($wish_goal, 2, '.', '');            
            $wish_content = sanitize_text_field($_POST['content_form']);
            $wish_youtube = isset($_POST['youtube_form'])? sanitize_text_field($_POST['youtube_form']) : '';
            $wish_vimeo = isset($_POST['vimeo_form'])?sanitize_text_field($_POST['vimeo_form']) : '';
            $author_login = $user_data->user_login;
            
             // Создаем массив
    $post_data = array(
	'post_title'    => wp_strip_all_tags($wish_title),
        'post_type'     => 'give_forms',
	'post_content'  => '',
	'post_status'   => 'draft',
	'post_author'   => $user_id
	
  );

    // Insert post in WP
    $post_id = wp_insert_post( wp_slash($post_data) );
    if( ! $post_id){ return;}
    
    $settings_for_buttons = array(
        0 => array(
            "_give_id"=> array(
                "level_id"=> '1'
            ),
            "_give_amount"=> '2',
            "_give_text"=> '2 dollars',
            "_give_default"=> "default"
        ),
        1 => array(
            "_give_id"=> array(
                "level_id"=> '2'
            ),
            "_give_amount"=> '5',
            "_give_text"=> '5 dollars',
            "_give_default"=> "default"
        ),
        2 => array(
            "_give_id"=> array(
                "level_id"=> '3'
            ),
            "_give_amount"=> '10',
            "_give_text"=> '10 dollars',
            "_give_default"=> "default"
        ),
        3 => array(
            "_give_id"=> array(
                "level_id"=> '4'
            ),
            "_give_amount"=> '20',
            "_give_text"=> '20 dollars',
            "_give_default"=> "default"
        ),
        4 => array(
            "_give_id"=> array(
                "level_id"=> '5'
            ),
            "_give_amount"=> '50',
            "_give_text"=> '50 dollars',
            "_give_default"=> "default"
        ),
    );
    
    //Settings for offline donations
    $offline_notes = 'In order to make an offline donation we ask that you please follow these instructions: 
                        Make a check payable to ""
                        On the memo line of the check, please indicate that the donation is for ""
                        Please mail your check to:
                            123 G Street 
                            San Diego, CA 92101 
                        All contributions will be gratefully acknowledged and are tax deductible.';
    
    $offline_subject = '{donation} - Offline Donation Instructions';
    
    $offline_email = 'Dear {name},
                    Thank you for your offline donation request! Your generosity is greatly appreciated. 
                    In order to make an offline donation we ask that you please follow these instructions: 
                    Make a check payable to ""
                    On the memo line of the check, please indicate that the donation is for ""
                    Please mail your check to:
                        123 G Street 
                        San Diego, CA 92101 
                    Once your donation has been received we will mark it as complete and you will receive an email receipt for your records. Please contact us with any questions you may have!
                    Sincerely,';
    //Update meta data
    update_post_meta( $post_id, '_give_form_content', $wish_content );
    update_post_meta( $post_id, '_give_goal_option', 'yes' );
    update_post_meta( $post_id, '_give_set_goal', $wish_goal );
    update_post_meta( $post_id, 'autor_login', $author_login );
    update_post_meta( $post_id, '_give_price_option', 'multi' );
    update_post_meta( $post_id, '_give_set_price', '1.00');
    update_post_meta( $post_id, '_give_donation_levels', $settings_for_buttons);
    update_post_meta( $post_id, '_give_display_style', 'buttons');
    update_post_meta( $post_id, '_give_custom_amount', 'yes');
    update_post_meta( $post_id, '_give_content_option', 'give_post_form');
    update_post_meta( $post_id, '_give_payment_display', 'reveal');
    update_post_meta( $post_id, '_give_default_gateway', 'global');
    update_post_meta( $post_id, '_give_show_register_form', 'none');
    update_post_meta( $post_id, 'youtube', $wish_youtube);
    update_post_meta( $post_id, 'vimeo', $wish_vimeo);
    update_post_meta( $post_id, '_give_customize_offline_donations', 'no');
    update_post_meta( $post_id, '_give_offline_checkout_notes', $offline_notes);
    update_post_meta( $post_id, '_give_offline_donation_subject', $offline_subject);
    update_post_meta( $post_id, '_give_offline_donation_email', $offline_email);
    update_post_meta( $post_id, '_give_terms_option', 'none');
   
    //Upload thumbnail
    if( isset( $_FILES["image_form"]["name"]) && $_FILES["image_form"]["name"] !=''){
        $err_images = true;
        if ( $_FILES["image_form"]["error"] == 0 && $post_id) {  
            //&& current_user_can( 'edit_post', $post_id )
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
            require_once( ABSPATH . 'wp-admin/includes/media.php' );
            

            $attachment_id = media_handle_upload( 'image_form', $post_id );

            if($attachment_id )set_post_thumbnail($post_id, $attachment_id);

            if ( is_wp_error( $attachment_id ) ) {
                    $err_images = false;
            } 
        } else {
            $err_images = false;
        }
        
       if( $err_images == false ){
            $location_fail = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=donation_form&kp-message=images_fail';
            wp_redirect( $location_fail ); exit;
       } 
    }
    
    //unset Data Post
    unset($_POST['title_form'], $_POST['goal_form'], $_POST['content_form'], $_POST['youtube_form'], 
            $_POST['vimeo_form'], $_POST['kp_nonce']);
    
        if( isset($user_id, $wish_title, $wish_goal, $wish_content) && $post_id > 0){
            $location_ok = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=donation_form&kp-message=wish_completed';
            wp_redirect( $location_ok ); exit;
        } 
    }
}
 
add_action('init','add_notify_create_wish');

function add_notify_create_wish(){ 
    $complete =  __('Your wish was created') ;
    $int_failed = __('You have some problems with creating your wish! Try again!');
    $void = __('The title, the sum and the content in the form must be filled');
    $goal = __('The sum must be integer and less than $1 000 000');
    $images_fail = __('There is an error in uploading the image or you have not permission to upload the image! But your wish was created!');
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'wish_completed'){ rcl_notice_text($complete,'success');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'wish_failed'){ rcl_notice_text($int_failed,'warning');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'wish_void'){ rcl_notice_text($void,'warning');}
    if (isset($_GET['int-message']) && sanitize_text_field($_GET['int-message']) == 'goal_failed'){ rcl_notice_text($goal,'warning');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'images_fail'){ rcl_notice_text($images_fail,'warning');}
} 

function add_tab_donations_rcl($array_tabs){
	//donation_form - идентификатор вкладки дополнения
	//form_donation_block - название функции формирующей контент вкладки дополнения
	$array_tabs['donation_form']='form_donation_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_donations_rcl');
?>
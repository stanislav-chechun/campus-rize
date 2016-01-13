 <?php
//Create new tab for the map
add_action('init','add_tab_donation_form');
function add_tab_donation_form(){
    rcl_tab('donation_form','form_donation_block','Create your wish',array('public'=>0,'class'=>'fa-plus-square','order'=>18));
    
}
//class - http://fontawesome.io/icons/

function form_donation_block($user_lk){
   // $html .= do_shortcode('[public-form id="1" type_editor="0" wp_editor="2"]');
    
    //Repetition
//    include_once RCL_PATH .'/add-on/publicpost/rcl_publicform.php';
//    $ss = new Rcl_PublicForm($type_editor=1, $wp_editor=3 );
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
//   
//     
//    $html .= wp_editor( '', 'wishwpeditor', array('textarea_name' => 'content') );
    $html .= '<form  class="form-horizontal" id="wish_table" method="post">';
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="title_form">' . __( 'The title of your goal: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="title_form" class="form-control"  id="title_form" placeholder="Title" type="text" value=""/>';
                        $html .= '</div>';
                $html .= '</div>';
                
                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="goal_form">' . __( 'The sum you need for your goal: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<input name="goal_form" class="form-control"  id="goal_form" placeholder="Amount" type="number" value=""/>';
                        $html .= '</div>';
                $html .= '</div>';

                $html .= '<div class="form-group">';
                        $html .= '<label class="col-sm-4 control-label" for="content_form">' . __( 'The content you want to display: ') . '</label>';
                        $html .= '<div class="col-sm-8">';
                            $html .= '<textarea id="content_form" name="content_form" class="form-control" rows="10"></textarea>';
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
                            $html .= '<input name="image_form" class="form-control"  id="image_form" type="file" value=""/>';
                        $html .= '</div>';
                $html .= '</div>';

                $html .= '<input type="hidden" name="kp_wish" value="process_kp_wish"/>';
                $html .=  wp_nonce_field('kp_nonce', 'kp_nonce');
                $html .= '<p><input class="btn btn-default" type="submit" value="Create">';
                $html .= '<input class="btn btn-default" type="reset" value="Reset"></p>';
            $html .= '</form>'; 
            
    
    return $html;
 }
 
add_action('init', 'kp_process_create');

function kp_process_create() {

	if( isset( $_POST['title_form'], $_POST['goal_form'], $_POST['content_form'] ) && $_POST['kp_wish'] == 'process_kp_wish' ) {
            
            if( ! wp_verify_nonce( $_POST['kp_nonce'], 'kp_nonce' ) ) {
                return;
            }
            //Retrieve metadata From 
            $form_id_from = sanitize_text_field($_POST['aims_from']);
            $amount_goal_from  = get_post_meta( $form_id_from, '_give_set_goal', true );
            $amount_have_from = get_post_meta( $form_id_from, '_give_form_earnings', true );
            $substraction_from = $amount_have_from - $amount_goal_from;
            
            //Retrieve metadata TO 
            $form_id_to = sanitize_text_field($_POST['aims_to']);
            $amount_goal_to  = get_post_meta( $form_id_to, '_give_set_goal', true );
            $amount_have_to = get_post_meta( $form_id_to, '_give_form_earnings', true );
            $substraction_to = $amount_have_to - $amount_goal_to;
            
            $user_id = get_current_user_id();
            //Amount to transfer
            $transfer = sanitize_text_field($_POST['money']);
            if( validate_int( $transfer, $substraction_from) ){
                     //New data
                $new_amount_from = $amount_have_from - $transfer;
                $new_amount_to = $amount_have_to + $transfer;

                update_post_meta( $form_id_from, '_give_form_earnings', $new_amount_from );
                update_post_meta( $form_id_to, '_give_form_earnings', $new_amount_to );

                $location_ok = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=transfer_funds&kp-message=transfer_completed';
                wp_redirect( $location_ok ); exit;
            } else{
                 $location_fail = get_bloginfo('url') . '/account/?user='. $user_id . '&tab=transfer_funds&kp-message=int_failed';
                 wp_redirect( $location_fail ); exit;
            }
            
        }
 }
 
 
add_action('init','add_notify_create_wish');

function add_notify_create_wish(){ 
    $complete = 'Transfer completed';
    $int_failed = 'The amount that can be transferred must be: integer, should be more than a zero, do not exceed the maximum amount possible for transfer.';
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'transfer_completed'){ rcl_notice_text($complete,'success');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'int_failed'){ rcl_notice_text($int_failed,'warning');}
} 

function add_tab_donations_rcl($array_tabs){
	//donation_form - идентификатор вкладки дополнения
	//form_map_block - название функции формирующей контент вкладки дополнения
	$array_tabs['donation_form']='form_donation_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_donations_rcl');
?>
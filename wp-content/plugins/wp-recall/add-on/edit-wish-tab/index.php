<?php
//Create new tab for edit student's wish
//include_once('edit_ajax.php');
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
            //Here we do the substraction, so we must have one format for this
//            $amount_have_sub = number_format($amount_have, 2, '.', '');
//            $amount_goal_sub = str_replace(',','', $amount_goal);
//            $substraction =  give_format_amount($amount_have_sub - $amount_goal_sub); 
//           
            //return format
//            $amount_have = give_format_amount($amount_have);
//            $amount_goal = give_format_amount($amount_goal);
           
            $html .= '<tr><td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
            $html .= '<td>' . $percentage . '%' . '</td>';
            $html .= '<td>' . '<button name="wish_id" id="wish_id" type="button" class="btn btn-primary btn-lg btn-block wish_id" value="' . $form_id . '">' . 
                        'Edit this wish' . '</button>' . '</td>';
                
                $html .=  '</tr>';
        }

        $html .= '</table>';
        $html .= '<div id="results">Ждем ответа</div>';

    } else{
        $html .= '<div class="center-block">';
            $html .= '<p class="bg-warning">';
                $html .= 'Sorry, you have no one donations forms! Start with us!';
            $html .= '</p>';
        $html .= '</div>';
    }
    return $html;
}

add_action('init', 'kp_process_edit');

function kp_process_edit() {

	if( isset( $_POST['aims_to'], $_POST['aims_from'], $_POST['money'] ) && $_POST['kp_transfer'] == 'process_kp_transfer' ) {
            
            if( ! wp_verify_nonce( $_POST['kp_nonce'], 'kp_nonce' ) ) {
                return;
            }
            //Retrieve metadata From 
            $form_id_from = sanitize_text_field($_POST['aims_from']);
            $amount_goal_from  = get_post_meta( $form_id_from, '_give_set_goal', true );
            $amount_have_from = get_post_meta( $form_id_from, '_give_form_earnings', true );
            
            //Convert to one format:
            $amount_goal_from_f = str_replace(',','',  $amount_goal_from);
            $amount_have_from_f = number_format($amount_have_from, 2, '.', '');
            
            //Substraction
            $substraction_from = give_format_amount($amount_have_from_f - $amount_goal_from_f);
            
            //Retrieve metadata TO 
            $form_id_to = sanitize_text_field($_POST['aims_to']);
            $amount_goal_to  = get_post_meta( $form_id_to, '_give_set_goal', true );
            $amount_have_to = get_post_meta( $form_id_to, '_give_form_earnings', true );
                       
            //Convert to one format:
            $amount_goal_to_f = str_replace(',','',  $amount_goal_to);
            $amount_have_to_f = number_format($amount_have_to, 2, '.', '');
        
            
            $substraction_to = give_format_amount($amount_have_to_f - $amount_goal_to_f);
            
            $user_id = get_current_user_id();
            
            //Amount to transfer
            $transfer = sanitize_text_field($_POST['money']);
            
            if( validate_int( $transfer, $substraction_from) ){
                     //New data
                $transfer = give_format_amount( $transfer );
                $new_amount_from = $amount_have_from_f - $transfer;
                $new_amount_to = $amount_have_to_f + $transfer;
                
                //echo  number_format($new_amount_from, 2, '.', '') . ' ' .  number_format($new_amount_to, 2, '.', '');
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
 
 
 add_action('init','add_notify_update_wish');
function add_notify_update_wish(){ 
    $complete = 'Transfer completed';
    $int_failed = 'The amount that can be transferred must be: integer, should be more than a zero, do not exceed the maximum amount possible for transfer.';
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'transfer_completed'){ rcl_notice_text($complete,'success');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'int_failed'){ rcl_notice_text($int_failed,'warning');}
}

function add_tab_edit_wish_rcl($array_tabs){
	//edit_wish - идентификатор вкладки дополнения
	//edit_recall_block - название функции формирующей контент вкладки дополнения
	$array_tabs['edit_wish']='edit_recall_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_edit_wish_rcl');
?>
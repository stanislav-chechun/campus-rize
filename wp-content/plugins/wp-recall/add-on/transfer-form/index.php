<?php
//Create new tab for tranfer money
add_action('init','add_tab_transfer_funds');
function add_tab_transfer_funds(){
    rcl_tab('transfer_funds','form_recall_block','Transfer of funds',array('public'=>0,'class'=>'fa-usd','order'=>20));
}
//class - http://fontawesome.io/icons/

function form_recall_block($user_lk){
    
    $user_data = get_userdata($user_lk);
    $user_login = $user_data->user_login;
    $args = array(
	'post_type' => 'give_forms',
	'meta_key' => 'autor_login',
        'meta_value' => $user_login
    );
    
    $query = new WP_Query( $args );
     
    $html .= 
            '<table class="table table-hover">
                <thead><th>The aim</th><th>Donates</th><th>The goal</th><th>Substraction</th></thead>'; 
    while ( $query->have_posts() ) {
	$query->the_post();
        $form_id = get_the_ID();
        $amount_goal  = get_post_meta( $form_id, '_give_set_goal', true );
        $amount_have = get_post_meta( $form_id, '_give_form_earnings', true );
        $substraction = $amount_have - $amount_goal;
       // $aims[] .= get_the_title();
        
    $html .= '<tr><td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
    $html .= '<td>' . $amount_have . '</td>';
    $html .= '<td>' . $amount_goal . '</td>';
        if( $substraction > 0 ){
            $html .=  '<td class="success">' . $substraction . '</td>';
            $sum_transfer += $substraction;
            //Create array for select. Bigger zero
            $aims_from[$form_id][] .= get_the_title();
            $aims_from[$form_id][] .= $amount_have;
            $aims_from[$form_id][] .= $substraction;
            $aims_from[$form_id][] .= $form_id;
        } else{
            $html .=  '<td class="danger">' . $substraction . '</td>';
            //Create array for select. Below zero
            $aims_to[$form_id][] .= get_the_title();
            $aims_to[$form_id][] .= $amount_have;
            $aims_to[$form_id][] .= $substraction;
            $aims_to[$form_id][] .= $form_id;
        }
        $html .=  '</tr>';
    }
                $html .= '</table>';  
    $html .= '<h3>You can transfer the money available to the goals that are made: $' . $sum_transfer . '</h3>';
    $html .= '<form  class="form-inline" id="transfer_form" method="post">';
        $html .= '<div class="form-group">';
        $html .= '<label for="aims_from">' . __( 'Transfer money from: ') . '</label>';
                $html .= '<select id="aims_from"  name="aims_from" required>';
                        foreach( $aims_from as $aim){
                            $html .=  '<option value="' . $aim[3] . '">' . $aim[0] . '</option>';
                        } 	             
                $html .= '</select>';
        $html .= '</div>';
        
        $html .= '<div class="form-group">';
        $html .= '<label for="aims_to">' . __( 'Transfer money to: ') . '</label>';
                $html .= '<select id="aims_to"  name="aims_to" required>';
                        foreach( $aims_to as $aim){
                            $html .=  '<option value="' . $aim[3] . '">' . $aim[0] . '</option>';
                        } 	             
                $html .= '</select>';
        $html .= '</div>';
        
        $html .= '<div class="form-group">';
            $html .= '<div class="input-group">';
            $html .= '<label class="sr-only"  for="money">' .  __( 'Enter the amount: ') . '</label>';
            $html .= '<div class="input-group-addon">$</div>';
            $html .= '<input name="money" class="form-control"  id="money" placeholder="Amount" type="text" value=""/>';
            $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<input type="hidden" name="kp_transfer" value="process_kp_transfer"/>';
        $html .=  wp_nonce_field('kp_nonce', 'kp_nonce');
        $html .= '<p><input class="btn btn-default" type="submit" value="Transfer">';
        $html .= '<input class="btn btn-default" type="reset" value="Reset"></p>';
    $html .= '</form>'; 
    
    return $html;
}

add_action('init', 'kp_process_transfer');

function kp_process_transfer() {

	if( isset( $_POST['aims_to'], $_POST['aims_from'], $_POST['money'] ) && $_POST['kp_transfer'] == 'process_kp_transfer' ) {
            
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
            
            //Amount to transfer
            $transfer = sanitize_text_field($_POST['money']);
            if( validate_int( $transfer, $substraction_from) ){
                 //New data
            $new_amount_from = $amount_have_from - $transfer;
            $new_amount_to = $amount_have_to + $transfer;
            
            // update_post_meta( $form_id_from, '_give_form_earnings', $new_amount_from );
            //update_post_meta( $form_id_to, '_give_form_earnings', $new_amount_to );
            } else{
                 $location_fail = get_bloginfo('url') . '/account/?user=1&tab=transfer_funds&kp-message=transfer_failed';
                 wp_redirect( $location_fail ); exit;
            }
            
           
           
//            if( ! $_POST['au_expiration'] || strlen( trim( $_POST['au_expiration'] ) ) <= 0 ) {
//		//wp_die( __('Please select the expiration date for users.', 'rcp_csvui' ), __('Error') );
//                wp_redirect( admin_url( '/options-general.php?page=activate_users.php&au-message=users-error-activated' ) ); exit;
//		}
          
      
       
        $location_ok = get_bloginfo('url') . '/account/?user=1&tab=transfer_funds&kp-message=transfer_completed';
       // wp_redirect( $location_ok ); exit;
        }
 }
 
 
 add_action('init','add_notify_update_profile');
function add_notify_update_profile(){    
    if (isset(sanitize_text_field($_GET['kp-message'])) && $_GET['kp-message'] == 'transfer_completed') rcl_notice_text('Transfer complete','success');
    if (isset(sanitize_text_field($_GET['kp-message'])) && $_GET['kp-message'] == 'transfer_failed') rcl_notice_text('Transfer complete','warning');
}

function add_tab_transfer_form_rcl($array_tabs){
	//transfer_funds - идентификатор вкладки дополнения
	//form_recall_block - название функции формирующей контент вкладки дополнения
	$array_tabs['transfer_funds']='form_recall_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_transfer_form_rcl');
?>
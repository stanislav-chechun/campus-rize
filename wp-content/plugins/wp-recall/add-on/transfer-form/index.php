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
        $aims[] .= get_the_title();
        
    $html .= '<tr><td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
    $html .= '<td>' . $amount_have . '</td>';
    $html .= '<td>' . $amount_goal . '</td>';
        if( $substraction > 0 ){
            $html .=  '<td class="success">' . $substraction . '</td>';
            $sum_transfer += $substraction;
        } else{
            $html .=  '<td class="danger">' . $substraction . '</td>';
        }
        $html .=  '</tr>';
}
            
    $html .= '</table>';
    $html .= '<h3>You can transfer the money available to the goals that are made: $' . $sum_transfer . '</h3>';
    $html .= '<form  class="form-inline" id="transfer_form" method="post">';
    $html .= '<div class="form-group">';
    $html .= '<label for="aims">' . __( 'Choose your aim: ') . '</label>';
            $html .= '<select id="aims"  name="aims" required>';
                    foreach( $aims as $aim){
                        $html .=  '<option value="' . $aim . '">' . $aim . '</option>';
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

	if( isset( $_POST['aims'] ) && $_POST['kp_transfer'] == 'process_kp_transfer' ) {

//            if( ! wp_verify_nonce( $_POST['au_nonce'], 'au_nonce' ) ) {
//                return;
//            }
//            if( ! $_POST['au_expiration'] || strlen( trim( $_POST['au_expiration'] ) ) <= 0 ) {
//		//wp_die( __('Please select the expiration date for users.', 'rcp_csvui' ), __('Error') );
//                wp_redirect( admin_url( '/options-general.php?page=activate_users.php&au-message=users-error-activated' ) ); exit;
//		}
          
             //My testing // $user_id = 524;
//        $expiration = isset( $_POST['au_expiration'] ) ? sanitize_text_field( $_POST['au_expiration'] ) : false;
//        $status = 'active';
//        $subscription_id = '2';
//        $signup_method = 'live';
//
//   $all_users = au_get_all_users();
//    foreach ($all_users as $user) {
//       update_user_meta( $user->ID, 'rcp_status', $status );
//        
//    }
       
        $location = get_bloginfo('url') . '/account/?user=1&tab=transfer_funds&kp-message=transfer_completed';
        wp_redirect( $location ); exit;
        }
 }
 
 add_action('init','add_notify_update_profile');
function add_notify_update_profile(){    
    if (isset($_GET['kp-message'])) rcl_notice_text('Transfer complete','success');
}

function add_tab_transfer_form_rcl($array_tabs){
	//transfer_funds - идентификатор вкладки дополнения
	//form_recall_block - название функции формирующей контент вкладки дополнения
	$array_tabs['transfer_funds']='form_recall_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_transfer_form_rcl');
?>
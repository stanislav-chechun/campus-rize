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
    
    if( $query->have_posts() ){
        $html .= 
                '<table class="table table-hover">
                    <thead><th>The aim</th><th>Donates</th><th>The goal</th><th>Substraction</th></thead>'; 
        while ( $query->have_posts() ) {
            $query->the_post();
            $form_id = get_the_ID();
            $amount_goal  = get_post_meta( $form_id, '_give_set_goal', true );
            $amount_have = get_post_meta( $form_id, '_give_form_earnings', true );
            
            //Here we do the substraction, so we must have one format for this
            $amount_have_sub = number_format($amount_have, 2, '.', '');
            $amount_goal_sub = str_replace(',','', $amount_goal);
            $substraction =  give_format_amount($amount_have_sub - $amount_goal_sub); 
           
            //return format
            $amount_have = give_format_amount($amount_have);
            $amount_goal = give_format_amount($amount_goal);
           
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
        if( count($aims_from) > 0 && count($aims_to) > 0){
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
        // If it nothing to display in selects
        } else{
            $html .= '<div class="center-block">';
            $html .= '<p class="bg-warning">';
                $html .= 'Sorry, you have nothing to transfer or all of your aims are achived!';
            $html .= '</p>';
        $html .= '</div>';
        }
    } else{
        $html .= '<div class="center-block">';
            $html .= '<p class="bg-warning">';
                $html .= 'Sorry, you have no one donations forms! Start with us!';
            $html .= '</p>';
        $html .= '</div>';
    }    
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
 
 
 add_action('init','add_notify_update_profile');
function add_notify_update_profile(){ 
    $complete = 'Transfer completed';
    $int_failed = 'The amount that can be transferred must be: integer, should be more than a zero, do not exceed the maximum amount possible for transfer.';
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'transfer_completed'){ rcl_notice_text($complete,'success');}
    if (isset($_GET['kp-message']) && sanitize_text_field($_GET['kp-message']) == 'int_failed'){ rcl_notice_text($int_failed,'warning');}
}

function add_tab_transfer_form_rcl($array_tabs){
	//transfer_funds - идентификатор вкладки дополнения
	//form_recall_block - название функции формирующей контент вкладки дополнения
	$array_tabs['transfer_funds']='form_recall_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_transfer_form_rcl');
?>
<?php

 require($_SERVER['DOCUMENT_ROOT'].'/campus-rize/wp-load.php'); 

if( isset($_POST['day_id'], $_POST['user_id'])){
    $user_id = intval($_POST['user_id']);
    $day_id = sanitize_text_field($_POST['day_id']);
    
    $old_array_time = get_user_meta($user_id, 'avaible_time', true);
    unset($old_array_time[$day_id]);
    $res = update_user_meta( $user_id, 'avaible_time', $old_array_time );
    
    if( $res ){
        $html = '<p style="color:rgb(22, 120, 0);">Date ' . $day_id . ' was removed!</p>';
        echo $html;
    }
}
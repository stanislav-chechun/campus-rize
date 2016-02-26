<?php 
/*  # Plugin Name: Personal Calendar PK
    # Version: 1.0.0
    # Description: Easily accept and manage user's availibility on your WordPress blog.
    # Author: Pavel Kovalchuk
    # Author URI: 
    # Plugin URI: 
    # This program is free software; 
*/
define("TIME_ROWS",2);
define("TIME_COLUMNS",5);


add_action ( 'show_user_profile', 'pk_show_availability_mentor' );
add_action ( 'edit_user_profile', 'pk_show_availability_mentor' );

function pk_show_availability_mentor ( $user) {
    if(is_admin()){
        $delete_url = plugins_url('personal-calendar-pk/delete_ajax.php');
    ?><script type="text/javascript">
        jQuery(document).ready(function($) { 
           
            
            $(function() {
                    $('.choose_time').timepicker();
            });
            
            var i = 1;
            var timeRows = 2;
            var timeColumns = 5;
            
            $('input.remove_day').click(function() {
                var idItem = $(this).attr('name');
                var trId = 'tr#' + idItem;
                
                jQuery.ajax({
			type: 'POST',
			url: '<?php echo $delete_url; ?>',
			data: "day_id=" + idItem + "&user_id=<?php echo $user->ID; ?>",
			cache: false,
			success: function(data){
					jQuery("#results").empty();
					jQuery("#results").html(data);
			}
		});
                
                $( trId ).remove();
            });
            
            $('#add').click(function() {
                var row = '<tr><td>Date</td><td><input type="text" name="' + i + '" class="datepicker"><td></tr>';
		var z,k;
                for(z=1; z <= timeRows; z++){
                    row += '<tr>';
                    for(k=1; k <= timeColumns; k++){
                        row += '<td>';
                            row += '<input id="' + i + z + k + '1' + '" name="' + i + z + k + '1' + '" type="text" class="time choose_time" />';
                            row += '<input id="' + i + z + k + '2' + '" name="' + i + z + k + '2' + '" type="text" class="time choose_time" />';
                        row += '</td>';
                    }
                    row += '</tr>';
                }
                $(row).fadeIn('slow').appendTo('#time_table');
		i++;
                $('.datepicker').datepicker({dateFormat: dateFormat});
                $('.choose_time').timepicker();
            });
            
             var dateFormat = 'yy-mm-dd'; 
            $('.datepicker').datepicker({dateFormat: dateFormat});
        });
         
        </script>
        <?php
        $user_data = get_userdata($user->ID);
       
        // Must remove admin from this!!!!!!!!
        if(is_admin()){
            if( $user_data->has_cap('mentor') || $user_data->has_cap('administrator')){

                $array_time = get_user_meta($user->ID, 'avaible_time', true);
                
                //sorting an array
                uksort($array_time, function($a, $b){
                    if ($a == $b) {
                            return 0;
                    }
                    return ($a < $b) ? -1 : 1;
                });
              
                ?>
        <hr size="6" color = "green" >
        <h2 style="text-align:center"><?php _e( 'This is the area of the plugin\'s "Personal Calendar"!!!');?></h2>
       
        <h3><?php _e( 'There are an availability for that mentor');?></h3><div id="results"></div>
        
        <table id="time_exist" class="table_exist">
            <?php 
            foreach( $array_time as $date => $time){
                ?>  <tr id="<?php echo $date; ?>">
                        <td><input type="button" name="<?php echo $date; ?>" value="Delete day" class="remove_day"></input></td>
                        <td><strong><?php echo $date; ?></strong></td>
                        <td>
                        <?php foreach($time as $time_item){ ?>

                            <?php echo $time_item . '; '; ?>
                        <?php } ?>
                        </td>
                    </tr>
                    
            <?php } ?>
      
        </table>
             
                <h3><?php _e( 'Add an availability for that mentor');?></h3>
       <?php echo USER_UPDATE; ?>
                    <table id="time_table" class="form-table">
                        <tr><td><?php _e('Date'); ?> </td><td><input type="text" name="<?php _e('0');?>" class="datepicker" ></td></tr>

                        <?php for($i=1; $i <= TIME_ROWS; $i++) {
                           ?><tr><?php for($k=1; $k <= TIME_COLUMNS; $k++){
                               ?><td>
                                   <input id="<?php echo 0 . $i . $k . '1'; ?>" name="<?php echo 0 . $i . $k . '1'; ?>" type="text" class="time choose_time" />
                                   <input id="<?php echo 0 . $i . $k . '2'; ?>" name="<?php echo 0 . $i . $k . '2'; ?>"type="text" class="time choose_time" />
                               </td><?php
                            }?>
                            </tr><?php
                       } ?>

                    </table>
                    <input type="hidden" name="pk_action" value="process_pk_date"/>
                  
                    <input type="button" value="Add New Date" id="add"></input>
                    <hr size="6" color = "green" >
                <?php
            }
        }
    }
}
add_action('admin_init', 'pk_process_activate');
function pk_process_activate() {
    if( isset( $_POST['pk_action'] ) && $_POST['pk_action'] == 'process_pk_date' ) {
//        if( ! wp_verify_nonce( $_POST['pk_nonce'], 'pk_nonce' ) ) {
//                return;
//            }
            
            $user = get_user_by('email', sanitize_email($_POST['email'])); 
            $user_id = $user->ID;
//            echo $user_id;
//            echo '<pre>';var_dump($_POST);echo '</pre>';
            $array_time =[];
            
            $i = 0;
            
            while($_POST[$i]){
                
                for($z=0 ; $z <= TIME_ROWS; $z++){
                    
                    for($k=0 ; $k <= TIME_COLUMNS; $k++){
                        
                       // $array_time[$_POST[$i]][] = $_POST[$i . $z . $k . '1'] . ' - ' . $_POST[$i . $z . $k . '2'];
                        
                        if( ! empty( $_POST[$i . $z . $k . '1']) && ! empty( $_POST[$i . $z . $k . '2'] )){
                            $array_time[$_POST[$i]][] = $_POST[$i . $z . $k . '1'] . ' - ' . $_POST[$i . $z . $k . '2'];
                        }
                    
                        elseif( ! empty( $_POST[$i . $z . $k . '1']) && empty( $_POST[$i . $z . $k . '2'] )){
                            $array_time[$_POST[$i]][] = $_POST[$i . $z . $k . '1'];
                        }
                    
                        elseif( empty( $_POST[$i . $z . $k . '1']) && !empty( $_POST[$i . $z . $k . '2'] )){
                            $array_time[$_POST[$i]][] = $_POST[$i . $z . $k . '2'];
                        }
                        
                    }
                   
                    
                }
            $i++;
                
            }
      
            //echo '<pre>';var_dump($array_time);echo '</pre>';
        $old_array_time = get_user_meta($user_id, 'avaible_time', true); 
        $new_array_time = $old_array_time + $array_time; 
        $result_time = update_user_meta( $user_id, 'avaible_time', $new_array_time );
        
        
//       
    }
}

//add_action('admin_notices', 'pk_good_notices', 1);
//function pk_good_notices() {
//     
//      echo '<div class="updated">User availability has been updated!</div>';
//    
//}

//add_action('admin_notices', 'pk_time_notices',33 );
//function pk_time_notices() {
//	if( isset( $_GET['pk-message-time'] ) && $_GET['pk-message-time'] == 'time_updated' ) {
//		add_settings_error( 'pk_time-ui', 'updated', __('User availability has been updated!'), 'updated' );
//	}
//        
////        if( isset( $_GET['au-message'] ) && $_GET['au-message'] == 'users-error-activated' ) {
////		add_settings_error( 'au-rep-ui', 'activated', __('Please select the expiration date for users.', 'rcp_csvui'), 'error' );
////	}
//}


add_action('admin_enqueue_scripts', 'pk_admin_scripts');
function pk_admin_scripts() {
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_register_script( 'jquery.timepicker', plugins_url('personal-calendar-pk/jquery.timepicker.js'), array('jquery'), true);
    wp_enqueue_script( 'jquery.timepicker' );
}

add_action('admin_print_styles', 'pk_admin_styles');
function pk_admin_styles() {
    wp_enqueue_style('jquery-ui-custom', plugins_url('personal-calendar-pk/jquery-ui.css'));
    wp_enqueue_style('jquery.timepicker', plugins_url('personal-calendar-pk/jquery.timepicker.css'));
   
}
?>

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
    ?><script type="text/javascript">
        jQuery(document).ready(function($) { 
           
            
            $(function() {
                    $('.choose_time').timepicker();
            });
            
            var i = 1;
            var timeRows = 2;
            var timeColumns = 5;
            
            $('#add').click(function() {
                var row = '<tr><td>Date</td><td><input type="text" name="date_' + i + '" class="datepicker"><td></tr>';
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
       
        ?>
	<h3><?php _e( 'Select an availability for that mentor');?></h3>
	<form id="pk-edit-availability" method="post">
            <table id="time_table" class="form-table">
                <tr><td><?php _e('Date'); ?> </td><td><input type="text" name="date_<?php _e('0');?>" class="datepicker" ></td></tr>

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
            <?php wp_nonce_field('pk_nonce', 'pk_nonce'); ?>
            <?php submit_button( __('Submit') ); ?>
        </form>
        <input type="button" value="Add New" id="add"></input>
<?php
        }
    }
    }
}
add_action('admin_init', 'au_process_activate');
function au_process_activate() {
    if( isset( $_POST['pk_action'] ) && $_POST['pk_action'] == 'process_pk_date' ) {
        if( ! wp_verify_nonce( $_POST['pk_nonce'], 'pk_nonce' ) ) {
                return;
            }
           echo '<pre>';var_dump($_POST);echo '</pre>';
            $array_time =[];
            for($i=0; $i <= TIME_ROWS; $i++){
                for($z=0, $k=0; $k <= TIME_COLUMNS; $k++, $z++){
                    echo $_POST[$z . $i . $k . '1'];
                    echo $_POST[$z . $i . $k . '2'];
                    
                    if( ! empty( $_POST[$z . $i . $k . '1']) && ! empty( $_POST[$z . $i . $k . '2'] )){
                        $array_time[] = $_POST[$z . $i . $k . '1'] . ' - ' . $_POST[$z . $i . $k . '2'];
                    }
                    
                    elseif( ! empty( $_POST[$z . $i . $k . '1']) && empty( $_POST[$z . $i . $k . '2'] )){
                        $array_time[] = $_POST[$z . $i . $k . '1'];
                    }
                    
                    elseif( empty( $_POST[$z . $i . $k . '1']) && !empty( $_POST[$z . $i . $k . '2'] )){
                        $array_time[] = $_POST[$z . $i . $k . '2'];
                    }
                    
                }
            }
            echo '<pre>';var_dump($array_time);echo '</pre>';
    }
}

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

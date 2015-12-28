<?php

	function add_scripts() {
		wp_enqueue_style( 'bootstrap.min', get_home_url() . '/wp-content/themes/radiate-child/bootstrap/bootstrap.min.css' );
		wp_enqueue_style( 'lato', '//fonts.googleapis.com/css?family=Lato:400,300,300italic,700,400italic,700italic&subset=latin,latin-ext' );
		wp_enqueue_style( 'montserrat', '//fonts.googleapis.com/css?family=Montserrat:400,700&subset=latin,latin-ext' );
		wp_enqueue_script( 'bootstrap.min.js', get_home_url() . '/wp-content/themes/radiate-child/bootstrap/bootstrap.min.js', array(), false, true );
		wp_enqueue_script( 'scripts.js', get_home_url() . '/wp-content/themes/radiate-child/js/scripts.js', array(), false, true );
		//wp_enqueue_script( 'mobile.js', get_template_directory_uri() . '/js/mobile.js', array(), false, true );
	}
	add_action( 'wp_enqueue_scripts', 'add_scripts' );
        
        //Pavel
        //Meta box for needed money
        
add_action('add_meta_boxes', 'need_money', 1);

function need_money() {
    add_meta_box( 'sum_money', 'The required amount of money', 'need_money_box_func', 'post', 'normal', 'high'  );
}

// the code for the block
function need_money_box_func( $post ){
    // 5 - id of the category Donate
    //Следует обдумать добавление боксов для определенной категории
    $arr_cat = $post->post_category;
    if( in_array(5, $arr_cat)){
        
        ?>
	<p><label><input type="text" name="money[sum]" value="<?php echo get_post_meta($post->ID, 'sum', 1); ?>" style="width:20%" /> <?php echo _('Sum, $'); ?></label></p>

	<input type="hidden" name="sum_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
    }
}

add_action('save_post', 'money_sum_update', 0);

/* Save data */
function money_sum_update( $post_id ){
	if ( !wp_verify_nonce($_POST['sum_fields_nonce'], __FILE__) ) return false; // checking
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // autochecking
	if ( !current_user_can('edit_post', $post_id) ) return false; // checking user role

	if( !isset($_POST['money']) ) return false; 
        if(! is_numeric($_POST['money']['sum'])) return false;

	// save
	$_POST['extra'] = array_map('trim', $_POST['money']);
	foreach( $_POST['money'] as $key=>$value ){
		if( empty($value) ){
			delete_post_meta($post_id, $key); // if field is empty
			continue;
		}

		update_post_meta($post_id, $key, $value); // add_post_meta()
	}
	return $post_id;
}
        
        //Pavel

?>
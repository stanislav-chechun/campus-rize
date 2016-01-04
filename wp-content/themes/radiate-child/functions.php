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
        
        
add_action('add_meta_boxes', 'video_yt__donations', 1);

function video_yt__donations() {
    add_meta_box( 'give-video', 'Video presentation', 'need_video_box_func', 'give_forms', 'side', 'low'  );
}

// the code for the block
function need_video_box_func( $post ){
        ?>
	<p><label>https://www.youtube.com/watch?v=<input type="text" name="video[youtube]" value="<?php echo get_post_meta($post->ID, 'youtube', 1); ?>" style="width:40%" /> <?php echo _('Youtube'); ?></label></p>
        <p><label>https://player.vimeo.com/video/<input type="text" name="video[vimeo]" value="<?php echo get_post_meta($post->ID, 'vimeo', 1); ?>" style="width:40%" /> <?php echo _('Vimeo'); ?></label></p>
	<input type="hidden" name="video_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>" />
	<?php
}

add_action('save_post', 'give_video_update', 0);

/* Save data */
function give_video_update( $post_id ){
	if ( !wp_verify_nonce($_POST['video_fields_nonce'], __FILE__) ) return false; // checking
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  ) return false; // autochecking
	if ( !current_user_can('edit_post', $post_id) ) return false; // checking user role

	//if( !isset($_POST['video']) ) return false; 
        //if(! is_numeric($_POST['money']['sum'])) return false;

	// save
	$_POST['video'] = array_map('trim', $_POST['video']);
	foreach( $_POST['video'] as $key=>$value ){
		if( empty($value) ){
			delete_post_meta($post_id, $key); // if field is empty
			continue;
		}

		update_post_meta($post_id, $key, $value); // add_post_meta()
	}
	return $post_id;
}

    
        //Pavel


		//Anjela

	$result = add_role( 'student', __(
		'Student' ),
		array( 
			'edit_posts' => true, // Allows user to edit their own posts
			'create_posts' => true,
			'upload_files' => true,
		) );


?>
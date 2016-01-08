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

//Validate numbers
function validate_int($input, $max, $min = 0  )
{
  return filter_var(
    $input,
    FILTER_VALIDATE_INT,
    array(
      //'flags'   => FILTER_FLAG_ALLOW_HEX,
      'options' => array('min_range' => $min, 'max_range' => $max)
    )
  );
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


	add_action('add_meta_boxes', 'donation_form_autor', 1);

	function donation_form_autor() {
		add_meta_box( 'autor_field', 'Autor', 'donation_autor_box_showup', 'give_forms', 'side', 'low'  );
	}

	function donation_autor_box_showup( $post ) { 
	?>
		<form action="" method="post">

			<!--<p>Name<br />
				<input type="text" name="autor_name" value="<?php echo get_post_meta($post->ID, 'autor_name', 1); ?>" />
			</p>

			<p>Surname<br />
				<input type="text" name="autor_surname" value="<?php echo get_post_meta($post->ID, 'autor_surname', 1); ?>" />
			</p>-->

			<p>Login<br />
				<input type="text" name="autor_login" value="<?php echo get_post_meta($post->ID, 'autor_login', 1); ?>" />
			</p>
			
		</form>
	<?php
	}

	add_action('save_post', 'donation_autor_save'); 

	function donation_autor_save($postID) { 

		/*if (!isset($_POST['autor_name'])
			&& !isset($_POST['autor_surname'])) */
		if (!isset($_POST['autor_login']))
		return; 

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return; 

		if (wp_is_post_revision($postID)) 
		return; 

		// correction data
		/*$autor_name    = sanitize_text_field($_POST['autor_name']);
		$autor_surname = sanitize_text_field($_POST['autor_surname']);*/
		$autor_login    = sanitize_text_field($_POST['autor_login']);

		// records
		/*update_post_meta($postID, 'autor_name', $autor_name);
		update_post_meta($postID, 'autor_surname', $autor_surname);*/
		update_post_meta($postID, 'autor_login', $autor_login);

	} 
	 
	add_action('init','add_tab_view_your_awards');

	function add_tab_view_your_awards(){
	    rcl_tab('awards', 'view_your_awards_showup', 'View your awards', array('public'=>0,'class'=>'fa-dollar','order'=>11));
	}

	function view_your_awards_showup(){
		$user = $_GET['user'];		

	 	if (isset($user)){			
			
			$user_info = get_userdata($user);			
			$user_login = $user_info->user_nicename;

			$args = array(
				'post_type' => 'give_forms', 
				'meta_key' => 'autor_login', 
				'meta_value' => $user_login,
			);

			$posts = new WP_Query( $args );

			if ( $posts->have_posts() ) {

				$goal_sum = 0;												
				$earnings_sum = 0;
?>

				<table>
					<caption>Award Summary</caption>
					<tr>
					    <th>Award Description</th>
					    <th>Category</th>
					    <th>Offered, $</th>
					    <th>Accepted, $</th>
				   	</tr>

<?php

					while ( $posts->have_posts() ) {
						$posts->the_post();
						$post_id = get_the_ID();
						$goal_give = get_post_meta($post_id, '_give_set_goal');												
						$earnings = get_post_meta($post_id, '_give_form_earnings');

						$goal = str_replace(",", "", $goal_give);
									
						$goal_sum += $goal[0];
						$earnings_sum += $earnings[0];

?>

				   	<tr>
				   		<td><?php the_title(); ?></td>
				   		<td>wishes</td>
				   		<td><?php echo $earnings[0]; ?></td>
				   		<td><?php echo $goal_give[0]; ?></td>
				   	</tr>

<?php

				}

?>
					<tr>
				   		<td colspan="2">Totals:</td>
				   		<td><?php echo $earnings_sum; ?></td>
				   		<td><?php echo $goal_sum; ?></td>
				   	</tr>
				</table>

<?php
			} 
			wp_reset_postdata();
		}	    
	}

?>
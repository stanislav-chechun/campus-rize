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

//It's for downloading map on student's account. For Plugin Store Locator
function map_please(){
    // if (isset($_GET['tab']) && sanitize_text_field($_GET['tab']) == 'assistance_map'){
    if (isset($_GET['user']) ){
        $GLOBALS['is_on_sl_page'] = 1;
     }
}
 add_action('wp', 'map_please');
 
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

//For displaying youtube video
//Check if video exist and output it or standart image
function display_youtube_video($video_id, $display_author_info){
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $video_id);
    if(is_array($headers) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$headers[0]) : false){
        $result = '<iframe src="https://www.youtube.com/embed/' . $video_id .'?rel=0" frameborder="0" rel="0" allowfullscreen></iframe>';
    } else { 
        $result = get_student_thumbnail('youtube.com', $display_author_info);
            }
    return $result;
}

function display_vimeo_video($video_id, $display_author_info){
    $headers = get_headers('http://vimeo.com/api/oembed.json?url=http%3A//vimeo.com/' . $video_id);
    if(is_array($headers) ? preg_match('/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/',$headers[0]) : false){
        $result = '<iframe src="https://player.vimeo.com/video/' . $video_id . '?color=fff700&byline=0&portrait=0&badge=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
    } else {
        $result = get_student_thumbnail('vimeo.com', $display_author_info);
    }
    
    return $result;   
}


//Test for create role Mentor
add_role(
	'mentor', __('Mentor'),
	array(
		'read'         => true,  // true разрешает эту возможность
		'edit_posts'   => false,  // true разрешает редактировать посты
		'delete_posts' => false, // false запрещает удалять посты
                'upload_files' => false,
	)
);

//Add metabox for mentor profile in the admin bar
add_filter('user_contactmethods', 'mentor_contactmethods',44);
 
function mentor_contactmethods($user_contactmethods){
 
  $user_contactmethods['youtube_mentor'] = 'Youtube video ID';
  $user_contactmethods['vimeo_mentor'] = 'Vimeo video ID';
 
  return $user_contactmethods;
}

//Add metabox for student profile in the admin bar for assign mentor

/**
 * Add additional custom fields to profile page
 */

add_action ( 'show_user_profile', 'kp_show_extra_mentor' );
add_action ( 'edit_user_profile', 'kp_show_extra_mentor' );

function kp_show_extra_mentor ( $user ) {
?>
        <?php 
        $user_data = get_userdata($user->ID);
        // Must remove admin from this!!!!!!!!
        if(is_admin()){
        if( $user_data->has_cap('student') || $user_data->has_cap('administrator')){
       
        ?>
	<h3><?php _e( 'Select Mentor for that student');?></h3>
	
	<table class="form-table">
            <tr>
		<th><label for="list_mentor"><?php _e( 'Mentors' ); ?></label></th>
		<td>
		<?php $value = get_the_author_meta( 'mentor_for_student', $user->ID );
                          $mentor_users = get_users('role=mentor');?>
                    <select name="list_mentor" id="list_mentor">
                        <?php foreach ($mentor_users as $mentor) {

                                ?> <option value="<?php echo $mentor->ID ;?>" <?php selected( $value, $mentor->ID ); ?> ><?php echo $mentor->user_nicename;?></option><?php
                            }

                            ?>
                    </select>
		</td>
		
            </tr>
		<?php // end of chunk ?>
		
	</table>

<?php
        }
    }
}

/**
 * Save data input from custom field on profile page
 */

add_action ( 'personal_options_update', 'kp_save_extra_mentor' );
add_action ( 'edit_user_profile_update', 'kp_save_extra_mentor' );

function kp_save_extra_mentor( $user_id ) {
    if(is_admin()){
        if( isset( $_POST['list_mentor'] )){
            if ( !current_user_can( 'edit_user', $user_id ) )
                    return false;		
            // copy this line for other fields
            update_user_meta( $user_id, 'mentor_for_student', $_POST['list_mentor'] );
        }
    
    }
}


/// End Add metabox for student profile in the admin bar for assign mentor

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

//View thumbnail in give donation youtube.com
function get_student_thumbnail($video_source, $display_author_info){
    if( $video_source == false){
        $display_author_info = false;
    }
    
    $result = '';
    if( $display_author_info == true){
        $result .= '<h2>' . __('Sorry, but video-id is invalid on ') . $video_source . '</h2>';
    } 
    if( has_post_thumbnail()){ 
        $result .= get_the_post_thumbnail();
    } else{
        $result .= ' <image src="' . get_stylesheet_directory_uri(). '/images/student.jpg">';
    }
    
    return $result;
}


function example_dashboard_widget_function(){
	// Показать то, что вы хотите показать
	echo "Я — великий виджет админки от Primary Technology";
}
// Создаем функцию, используя хук действия
function example_add_dashboard_widgets() {
	wp_add_dashboard_widget('example_dashboard_widget', 'Привет, случайный прохожий!', 'example_dashboard_widget_function');
}
// Хук в 'wp_dashboard_setup', чтобы зарегистрировать нашу функцию среди других
add_action('wp_dashboard_setup', 'example_add_dashboard_widgets' );
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

	add_action('init', 'register_professional_network');

	function register_professional_network(){
		$args = array(
			'label'  => null,
			'labels' => array(
				'name'               => 'Professions',
				'singular_name'      => 'Profession',
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New Profession',
				'menu_name'          => 'Professional Network',
			),
			'public'              => true,
			'menu_position'       => 4,
			'supports'            => array('title', 'editor', 'thumbnail'),
			'register_meta_box_cb'=> 'profession_meta_add',
		);

		register_post_type('professions', $args );
	}
	
	function profession_meta_add() { 
		add_meta_box('profession_meta_add', 'General Information', 'profession_meta_showup', 'professions', 'normal', 'high'); 
	} 

	function profession_meta_showup( $post ) { 
		?>
		<form action="" method="post">

			<p>Industry</p>
			<input type="text" name="prof_industry" value="<?php echo get_post_meta($post->ID, 'prof_industry', 1); ?>" style="width:100%" />

			<p>Location</p>
			<input type="text" name="prof_location" value="<?php echo get_post_meta($post->ID, 'prof_location', 1); ?>" style="width:100%" />

			<p>E-mail</p>
			<input type="email" name="prof_email" value="<?php echo get_post_meta($post->ID, 'prof_email', 1); ?>" style="width:100%" />

			<p>Phone <br />(format: +XX (XXX) XXX-XX-XX)</p>
			<input type="text" name="prof_tel" pattern="\+[0-9]{2} \([0-9]{3}\) [0-9]{3}-[0-9]{2}-[0-9]{2}" value="<?php echo get_post_meta($post->ID, 'prof_tel', 1); ?>" style="width:100%" />			

		</form>
		<?php
	} 

	add_action('save_post', 'profession_meta_save'); 

	function profession_meta_save($postID) { 

		if (!isset($_POST['prof_industry']))
		return; 

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return; 

		if (wp_is_post_revision($postID)) 
		return; 

		$prof_industry = sanitize_text_field($_POST['prof_industry']);
		$prof_email    = sanitize_text_field($_POST['prof_email']);
		$prof_tel      = sanitize_text_field($_POST['prof_tel']);
		$prof_location = sanitize_text_field($_POST['prof_location']);

		update_post_meta($postID, 'prof_industry', $prof_industry);
		update_post_meta($postID, 'prof_email', $prof_email);
		update_post_meta($postID, 'prof_tel', $prof_tel);
		update_post_meta($postID, 'prof_location', $prof_location);

	}

	add_action('init', 'register_team');

	function register_team(){
		$args = array(
			'label'  => null,
			'labels' => array(
				'name'               => 'Team',
				'singular_name'      => 'Team Member',
				'add_new'            => 'Add New',
				'add_new_item'       => 'Add New Team Member',
				'menu_name'          => 'Team',
			),
			'public'              => true,
			'menu_position'       => 5,
			'supports'            => array('title', 'editor', 'thumbnail'),
			'register_meta_box_cb'=> 'team_meta_add',
		);

		register_post_type('team', $args );
	}
	
	function team_meta_add() { 
		add_meta_box('team_meta_add', 'Personal Information', 'team_meta_showup', 'team', 'normal', 'high'); 
	} 

	function team_meta_showup( $post ) { 
		?>
		<form action="" method="post">

			<p>Name</p>
			<input type="text" name="tm_name" value="<?php echo get_post_meta($post->ID, 'tm_name', 1); ?>" style="width:100%" />

			<p>Surname</p>
			<input type="text" name="tm_surname" value="<?php echo get_post_meta($post->ID, 'tm_surname', 1); ?>" style="width:100%" />

			<p>Position</p>
			<input type="text" name="tm_position" value="<?php echo get_post_meta($post->ID, 'tm_position', 1); ?>" style="width:100%" />

			<p>E-mail</p>
			<input type="email" name="tm_email" value="<?php echo get_post_meta($post->ID, 'tm_email', 1); ?>" style="width:100%" />

			<p>LinkedIn</p>
			<input type="text" name="tm_linkedIn" value="<?php echo get_post_meta($post->ID, 'tm_linkedIn', 1); ?>" style="width:100%" />

			<p>Facebook</p>
			<input type="text" name="tm_facebook" value="<?php echo get_post_meta($post->ID, 'tm_facebook', 1); ?>" style="width:100%" />			

		</form>
		<?php
	} 

	add_action('save_post', 'team_meta_save'); 

	function team_meta_save($postID) { 

		if (!isset($_POST['tm_name'])
			&& !isset($_POST['tm_surname'])
			&& !isset($_POST['tm_position'])
			&& !isset($_POST['tm_email'])
			&& !isset($_POST['tm_linkedIn'])
			&& !isset($_POST['tm_facebook']))
		return; 

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return; 

		if (wp_is_post_revision($postID)) 
		return; 

		$tm_name     = sanitize_text_field($_POST['tm_name']);
		$tm_surname  = sanitize_text_field($_POST['tm_surname']);
		$tm_position = sanitize_text_field($_POST['tm_position']);
		$tm_email    = sanitize_text_field($_POST['tm_email']);
		$tm_linkedIn = sanitize_text_field($_POST['tm_linkedIn']);
		$tm_facebook = sanitize_text_field($_POST['tm_facebook']);

		update_post_meta($postID, 'tm_name', $tm_name);
		update_post_meta($postID, 'tm_surname', $tm_surname);
		update_post_meta($postID, 'tm_position', $tm_position);
		update_post_meta($postID, 'tm_email', $tm_email);
		update_post_meta($postID, 'tm_linkedIn', $tm_linkedIn);
		update_post_meta($postID, 'tm_facebook', $tm_facebook);

	}

?>
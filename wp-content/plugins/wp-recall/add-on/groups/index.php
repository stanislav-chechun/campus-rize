<?php

require_once 'groups-init.php';
require_once 'groups-core.php';
require_once 'shortcodes.php';
if(is_admin()) require_once 'groups-options.php';
require_once 'groups-widgets.php';
if(!is_admin()) require_once 'groups-public.php';
require_once 'upload-avatar.php';

rcl_enqueue_style('groups',__FILE__);

if(function_exists('rcl_register_rating_type')){
    if(!is_admin()) add_action('init','rcl_register_rating_group_type');
    if(is_admin()) add_action('admin_init','rcl_register_rating_group_type');
    function rcl_register_rating_group_type(){
        rcl_register_rating_type(array('post_type'=>'post-group','type_name'=>__('Record groups','wp-recall'),'style'=>true));
    }
}

add_filter('taxonomy_public_form_rcl','rcl_add_taxonomy_public_groups');
function rcl_add_taxonomy_public_groups($tax){
    if (!isset($tax['post-group'])) $tax['post-group'] = 'groups';
    return $tax;
}

add_action('init','rcl_add_postlist_group');
function rcl_add_postlist_group(){
    rcl_postlist('group','post-group',__('Record groups','wp-recall'),array('order'=>40));
}

add_action('init','rcl_add_tab_groups');
function rcl_add_tab_groups(){
    rcl_tab('groups','rcl_tab_groups',__('Groups','wp-recall'),array('public'=>1,'class'=>'fa-group'));
}

function rcl_ajax_tab_groups($array_tabs){
    $array_tabs['groups']='rcl_tab_groups';
    return $array_tabs;
}
add_filter('ajax_tabs_rcl','rcl_ajax_tab_groups');

add_action('init','rcl_register_default_group_sidebars',10);
function rcl_register_default_group_sidebars(){

    rcl_register_group_area(
        array(
            'name'=>__('Sidebar','wp-recall'),
            'id'=>'sidebar'
        )
    );

    rcl_register_group_area(
        array(
            'name'=>__('Main','wp-recall'),
            'id'=>'content'
        )
    );

    rcl_register_group_area(
        array(
            'name'=>__('Footer','wp-recall'),
            'id'=>'footer'
        )
    );

}

function rcl_group(){
    global $rcl_group;

    $admin = (rcl_is_group_can('admin')||current_user_can('edit_others_posts'))? 1: 0;

    $class = ($admin)? 'class="admin-view"': '';

    echo '<div id="rcl-group" data-group="'.$rcl_group->term_id.'" '.$class.'>';

    if($admin)
        echo rcl_group_admin_panel();

    echo '<div id="group-popup"></div>';

    rcl_include_template('single-group.php',__FILE__);

    echo '</div>';

}

/*deprecated*/
function add_post_in_group(){
    rcl_group();
}

function rcl_tab_groups($author_lk){

    global $wpdb,$user_ID,$rcl_options;

    if($author_lk==$user_ID){

        $group_can_public = (isset($rcl_options['public_group_access_recall']))? $rcl_options['public_group_access_recall']: false;
        if($group_can_public){
                $userdata = get_userdata( $user_ID );
                if($userdata->user_level>=$group_can_public){
                        $public_groups = true;
                }else{
                        $public_groups = false;
                }
        }else{
                $public_groups = true;
        }

        if($public_groups){
            $content = '<div id="create-group">'
                . '<form method="post">'
                    . '<div class="form-field">'
                        . '<input type="text" required placeholder="'.__('Enter the name of the new group','wp-recall').'" name="rcl_group[name]">'
                        . '<input type="submit" class="recall-button" name="rcl_group[create]" value="'.__('Сreate','wp-recall').'">'
                    . '</div>'
                    . wp_nonce_field('rcl-group-create','_wpnonce',true,false)
                . '</form>'
            . '</div>';
        }
    }

    $content .= rcl_get_grouplist(array('filters'=>1,'search_form'=>0,'user_id'=>$author_lk,'add_uri'=>array('tab'=>'groups')));

    return $content;
}

function rcl_get_link_group_tag($content){
	global $post,$user_ID,$rcl_group;
	if($post->post_type!='post-group') return $content;

	$group_data = get_the_terms( $post->ID, 'groups' );

	foreach((array)$group_data as $data){
		if($data->parent==0) $group_id = $data->term_id;
		else $tag = $data;
	}

	if(!$tag) return $content;

        if( doing_filter('the_excerpt') ){

            if(!$rcl_group) $rcl_group = rcl_get_group($group_id);

            if($rcl_group->group_status=='closed'){
                if($rcl_group->admin_id!=$user_ID){

                    $user_status = rcl_get_group_user_status($user_ID,$rcl_group->term_id);

                    if(!$user_status) $content = rcl_close_group_post_content();

                }
            }
        }

	$cat = '<p><i class="fa fa-folder"></i>'.__('Category in the group','wp-recall').': <a href="'. get_term_link( (int)$group_id, 'groups' ) .'?group-tag='.$tag->slug.'">'. $tag->name .'</a></p>';

	return $cat.$content;
}

function rcl_init_get_link_group_tag(){
	if(is_single()) add_filter('the_content','rcl_get_link_group_tag',80);
	else add_filter('the_excerpt','rcl_get_link_group_tag',80);
}
add_action('wp','rcl_init_get_link_group_tag');

function rcl_init_namegroup(){
	if(is_single()) add_filter('the_content','rcl_add_namegroup',80);
        if(is_search()) add_filter('the_excerpt','rcl_add_namegroup',80);
}
add_action('wp','rcl_init_namegroup');

function rcl_add_namegroup($content){
	global $post;
	if(get_post_type( $post->ID )!='post-group') return $content;

	$groups = get_the_terms( $post->ID, 'groups' );
	foreach((array)$groups as $group){
		if($group->parent) continue;
		$group_link = '<p><i class="fa fa-users"></i>'.__('Published in the group','wp-recall').': <a href="'. get_term_link( (int)$group->term_id, 'groups' ) .'">'. $group->name .'</a></p>';
	}
	$content = $group_link.$content;
	return $content;
}

//Создаем новую группу
function rcl_new_group(){

    global $user_ID,$wpdb;

    $name_group = sanitize_text_field($_POST['rcl_group']['name']);
    $group_id = rcl_create_group(array('name'=>$name_group,'admin_id'=>$user_ID));

    if(!$group_id){
        rcl_notice_text(__('Create a group failed','wp-recall'),'error');
    }else{
        wp_redirect(get_term_link( (int)$group_id, 'groups' ));
        exit;
    }

}

function rcl_init_group_create( ) {
  if ( isset($_POST['rcl_group']) ) {
    if( !wp_verify_nonce( $_POST['_wpnonce'], 'rcl-group-create' ) ) return false;
    add_action( 'wp', 'rcl_new_group' );
  }
}
add_action('init', 'rcl_init_group_create');

add_filter('rcl_group_thumbnail','rcl_group_add_thumb_buttons');
function rcl_group_add_thumb_buttons($content){
    $rcl_group;

    if(!rcl_is_group_can('admin')) return $content;

    $content .= '<div id="group-avatar-upload">
            <span id="file-upload" class="fa fa-download">
                <input type="file" id="groupavatarupload" accept="image/*" name="uploadfile">
            </span>
	</div>
	<span id="avatar-upload-progress"></span>';
    return $content;
}

add_action('wp','rcl_group_actions');
function rcl_group_actions(){
    global $user_ID,$rcl_group;

    if(!isset($_POST['group-submit'])) return false;
    if( !wp_verify_nonce( $_POST['_wpnonce'], 'group-action-' . $user_ID ) ) return false;

    switch($_POST['group-action']){
        case 'leave': rcl_group_remove_user($user_ID,$rcl_group->term_id); break;
        case 'join': rcl_group_add_user($user_ID,$rcl_group->term_id); break;
        case 'ask': rcl_group_add_request_for_membership($user_ID,$rcl_group->term_id); break;
        case 'update':
            $args = $_POST['group-options'];
            $args['group_id'] = $rcl_group->term_id;
            rcl_update_group($args);
            break;
        case 'update-widgets':
            $data = $_POST['data'];
            rcl_update_group_widgets($rcl_group->term_id,$data);
            break;
    }

    wp_redirect(rcl_get_group_permalink($rcl_group->term_id)); exit;
}

function rcl_get_group_options($group_id){
    global $rcl_group,$user_ID;

    $default_role = rcl_get_group_option($group_id,'default_role');
    $category = rcl_get_group_option($group_id,'category');

    $category = (is_array($category))? implode(', ',$category): $category;

    /*$data = array( 'wpautop' => 1
        ,'media_buttons' => 0
        ,'textarea_name' => 'group-options[description]'
        ,'textarea_rows' => 10
        ,'tabindex' => null
        ,'editor_css' => ''
        ,'editor_class' => 'autosave'
        ,'teeny' => 0
        ,'dfw' => 0
        ,'tinymce' => 1
        ,'quicktags' => 1
    );

    ob_start();
    wp_editor( esc_textarea(strip_tags(rcl_get_group_description($group_id))), 'contentarea', $data );
    $editor = ob_get_contents();
    ob_end_clean();*/

    $content = '<div id="group-options">'
        . '<h3>Настройки группы</h3>'
        . '<form method="post">'
            . '<div class="group-option">'
                . '<label>Название группы</label>'
                . '<input type="text" name="group-options[name]" value="'.$rcl_group->name.'">'
            . '</div>'
            . '<div class="group-option">'
                . '<label>Описание</label>'
                . '<textarea name="group-options[description]">'.esc_html(strip_tags(rcl_get_group_description($group_id))).'</textarea>'
            . '</div>'
            . '<div class="group-option">'
                . '<label>Статус группы</label>'
                . '<select name="group-options[status]">'
                . '<option '.selected($rcl_group->group_status,'open',false).' value="open">Открытая группа</option>'
                . '<option '.selected($rcl_group->group_status,'closed',false).' value="closed">Закрытая группа</option>'
                . '</select>'
            . '</div>'
            . '<div class="group-option">'
                . '<label>Членство</label>'
                . '<input type="checkbox" name="group-options[can_register]" '.checked(rcl_get_group_option($group_id,'can_register'),1,false).' value="1"> Регистрация разрешена'
                . '<label>Роль нового пользователя</label>'
                . '<select name="group-options[default_role]">'
                . '<option '.selected($default_role,'reader',false).' value="reader">Посетитель</option>'
                . '<option '.selected($default_role,'author',false).' value="author">Автор</option>'
                . '</select>'
            . '</div>'
            . '<div class="group-option">'
                . '<label>Категории группы <small>(указывать через запятую)</small></label>'
                . '<textarea name="group-options[category]">'.$category.'</textarea>'
            . '</div>';

            $content = apply_filters('rcl_group_options',$content);

            $content .= '<div class="group-option">'
                . '<input type="submit" class="recall-button" name="group-submit" value="Сохранить настройки">'
                . '<input type="hidden" name="group-action" value="update">'
                . wp_nonce_field( 'group-action-' . $user_ID,'_wpnonce',true,false )
            . '</div>'
        . '</form>'
    . '</div>';

    return $content;
}

function rcl_get_group_requests_content($group_id){

    $requests = rcl_get_group_option($group_id,'requests_group_access');

    $content = '<h3>'.__('Requests for access to the group','wp-recall').'</h3>';

    if(!$requests){
        $content .= '<p>'.__('No queries','wp-recall').'</p>';
        return $content;
    }

    add_action('user_description','rcl_add_group_access_button');

    $content .= rcl_get_userlist(array('include'=>implode(',',$requests),'filters'=>0,'orderby'=>'time_action','data'=>'rating_total,posts_count,comments_count,description,user_registered'));

    return $content;
}

function rcl_add_group_access_button(){
    global $rcl_user;
    echo '<div class="group-request" data-user="'.$rcl_user->ID.'">';
    echo rcl_get_button(__('Approve request','wp-recall'),'#',array('icon'=>'fa-thumbs-up','class'=>'apply-request','attr'=>'data-request=1'));
    echo rcl_get_button(__('Reject request','wp-recall'),'#',array('icon'=>'fa-thumbs-down','class'=>'apply-request','attr'=>'data-request=0'));
    echo '</div>';
}

function rcl_add_group_user_options(){
    global $rcl_user,$rcl_group,$user_ID;

    if($user_ID==$rcl_user->ID) return false;
    if($rcl_user->ID==$rcl_group->admin_id) return false;

    $group_roles = rcl_get_group_roles();

    echo '<div id="options-user-'.$rcl_user->ID.'" class="group-request" data-user="'.$rcl_user->ID.'">';

        echo '<div class="group-user-option">';
            echo rcl_get_group_callback('rcl_group_ajax_delete_user',__('Delete','wp-recall'));
        echo '</div>';

        echo '<div class="group-user-option">';
            echo __('User status','wp-recall').' <select name="user_role">';
            foreach($group_roles as $role=>$data){
                echo '<option value="'.$role.'" '.selected($rcl_user->user_role,$role,false).'>'.$data['role_name'].'</option>';
            }
            echo '</select>';
            echo rcl_get_group_callback('rcl_group_ajax_update_role',__('Save','wp-recall'),array('user_role'));
        echo '</div>';

    echo '</div>';
}

add_action('wp_ajax_rcl_apply_group_request','rcl_apply_group_request');
function rcl_apply_group_request(){
    global $rcl_group,$user_ID;

    $user_id = intval($_POST['user_id']);
    $apply = intval($_POST['apply']);
    $group_id = intval($_POST['group_id']);

    $rcl_group = rcl_get_group($group_id);

    if($rcl_group->admin_id!=$user_ID) return false;

    $requests = rcl_get_group_option($group_id,'requests_group_access');
    $key = array_search($user_id, $requests);

    if(!$requests||false===$key) return false;

    unset($requests[$key]);

    if($apply){

        $subject = __('Request access to the group approved!','wp-recall');
        $textmail = '
        <h3>Добро пожаловать в группу "'.$rcl_group->name.'"!</h3>
        <p>Поздравляем, ваш запрос на доступ к приватной группе на сайте "'.get_bloginfo('name').'" был одобрен.</p>
        <p>Теперь вы можете принимать участие в жизни этой группы как полноценный ее участник.</p>
        <p>Вы можете перейти в группу прямо сейчас, перейдя по ссылке:</p>
        <p>'.get_term_link( (int)$group_id, 'groups' ).'</p>';

        rcl_group_add_user($user_id,$group_id);

        $log['result']='<span class="success">'.__('The request was accepted','wp-recall').'</span>';

    }else{

        $log['result']='<span class="error">'.__('Request rejected','wp-recall').'</span>';
        $subject = __('The request to access the group rejected.','wp-recall');
        $textmail = '
        <p>Сожалеем, но ваш запрос на доступ к приватной группе "'.$rcl_group->name.'" на сайте "'.get_bloginfo('name').'" был отклонен ее админом.</p>';

    }

    $user_email = get_the_author_meta('user_email',$user_id);
    rcl_mail($user_email, $subject, $textmail);

    rcl_update_group_option($group_id,'requests_group_access',$requests);


    $log['user_id']=$user_id;
    echo json_encode($log);
    exit;
}

add_filter('rcl_feed_posts_array','rcl_add_feed_group_posts',10);
function rcl_add_feed_group_posts($posts){
    global $wpdb,$user_ID;

    $groups = $wpdb->get_col("SELECT groups_users.group_id, groups.ID AS group_id "
            . "FROM ".RCL_PREF."groups_users AS groups_users "
            . "INNER JOIN ".RCL_PREF."groups AS groups ON groups_users.user_id=groups.admin_id "
            . "WHERE (groups_users.user_id='$user_ID' OR groups.admin_id='$user_ID') "
            . "GROUP BY groups_users.group_id, groups.ID");

    if($groups){

        $objects = $wpdb->get_col("SELECT term_relationships.object_id "
            . "FROM $wpdb->term_relationships AS term_relationships "
            . "INNER JOIN $wpdb->term_taxonomy AS term_taxonomy ON term_relationships.term_taxonomy_id=term_taxonomy.term_taxonomy_id "
            . "WHERE term_taxonomy.term_id IN (".implode(',',$groups).")");

        if($objects) $posts = array_unique(array_merge($posts,$objects));

    }

    return $posts;
}

add_filter('file_scripts_rcl','rcl_get_scripts_groups');
function rcl_get_scripts_groups($script){

	$ajaxdata = "type: 'POST', data: dataString, dataType: 'json', url: wpurl+'wp-admin/admin-ajax.php',";

	$script .= "
	jQuery('#rcl-group').on('click','a.rcl-group-link',function(){
            var callback = jQuery(this).data('callback');
            var group_id = jQuery(this).data('group');
            var value = jQuery(this).data('value');

            var dataString = 'action=rcl_get_group_link_content&group_id='+group_id+'&callback='+callback;
            if(value) dataString += '&value='+value;
            rcl_preloader_show('#rcl-group > div');
            jQuery.ajax({
                ".$ajaxdata."
                success: function(data){
                    if(data){
                        jQuery('#group-popup').html(data);

                        var height = jQuery('#group-link-content').height();
                        /*var height_group = jQuery('#rcl-group').height();*/

                        /*if(height_group>height_content) var height = height_group;
                        else var height = height_content;*/

                        /*height = height+300;*/
                        jQuery('#group-popup').height(height);
                        var offsetTop = jQuery('#group-link-content').offset().top;
                        jQuery('body,html').animate({scrollTop:offsetTop -70}, 500);

                    } else {
                        rcl_notice('Error','error');
                    }
                    rcl_preloader_hide();
                }
            });
            return false;
	});
        jQuery('#group-popup').on('click','#group-userlist .ajax-navi a',function(){
            var page = jQuery(this).text();
            var url = jQuery(this).attr('href');
            var group_id = jQuery(this).parents('#rcl-group').data('group');
            var dataString = 'action=rcl_get_group_link_content&group_id='+group_id+'&callback=rcl_get_group_users&page='+page+'&get='+url;
            rcl_preloader_show('#rcl-group > div');
            jQuery.ajax({
                ".$ajaxdata."
                success: function(data){
                    if(data){

                        jQuery('#group-popup').html(data);

                        /*var height = jQuery('#group-link-content').height()+200;*/
                        /*jQuery('#group-link-content').parent().height(height);*/
                        var offsetTop = jQuery('#group-link-content').offset().top;
                        jQuery('body,html').animate({scrollTop:offsetTop -70}, 500);

                    } else {
                        rcl_notice('Error','error');
                    }
                    rcl_preloader_hide();
                }
            });
            return false;
	});
        jQuery('#group-popup').on('click','.group-request .apply-request',function(){
            var button = jQuery(this);
            var user_id = button.parent().data('user');
            var apply = button.data('request');
            var group_id = button.parents('#rcl-group').data('group');
            var dataString = 'action=rcl_apply_group_request&group_id='+group_id+'&user_id='+user_id+'&apply='+apply;
            rcl_preloader_show('#group-popup > div');
            jQuery.ajax({
                ".$ajaxdata."
                success: function(data){
                    if(data){

                        button.parent().html(data['result']);

                    } else {
                        rcl_notice('Error','error');
                    }
                    rcl_preloader_hide();
                }
            });
            return false;
	});
        jQuery('#rcl-group').on('click','.rcl-group-callback',function(){
            var callback = jQuery(this).data('callback');
            var group_id = jQuery(this).data('group');
            var name = jQuery(this).data('name');
            if(name){
                var valname = jQuery(this).parents('.group-user-option').children('[name*=\''+name+'\']').val();
            }
            var user_id = jQuery(this).parents('.group-request').data('user');
            var dataString = 'action=rcl_group_callback&group_id='+group_id+'&callback='+callback+'&user_id='+user_id;
            if(name) dataString += '&'+name+'='+valname;
            rcl_preloader_show('#rcl-group > div');
            jQuery.ajax({
                ".$ajaxdata."
                success: function(data){
                    if(data['success']){
                        var type = 'success';
                    } else {
                        var type = 'error';
                    }

                    if(data['place']=='notice') rcl_notice(data[type],type);
                    if(data['place']=='buttons') jQuery('#options-user-'+user_id).html('<span class=\''+type+'\'>'+data[type]+'</span>');

                    rcl_preloader_hide();
                }
            });
            return false;
	});
	";
	return $script;
}

function rcl_get_groups_footer_scripts($script){
	global $rcl_options;

	$maxsize_mb = (isset($rcl_options['avatar_weight'])&&$rcl_options['avatar_weight'])? $rcl_options['avatar_weight']: 2;
	$maxsize = $maxsize_mb*1024*1024;

	$script .= "
	$('#groupavatarupload').fileupload({
		dataType: 'json',
		type: 'POST',
		url: wpurl+'wp-admin/admin-ajax.php',
		formData:{action:'rcl_group_avatar_upload'},
		loadImageMaxFileSize: ".$maxsize.",
		autoUpload:true,
		imageMinWidth:150,
		imageMinHeight:150,
		disableExifThumbnail: true,
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10);
			$('#avatar-upload-progress').show().html('<span>'+progress+'%</span>');
		},
		submit: function (e, data) {
                    var group_id = $('#groupavatarupload').parents('#rcl-group').data('group');
                    data.formData = {
                        group_id: group_id,
                        action:'rcl_group_avatar_upload'
                    };
		},
		done: function (e, data) {

                    if(data.result['error']){
                            rcl_notice(data.result['error'],'error');
                            return false;
                    }

                    $('#rcl-group .group-avatar img').attr('src',data.result['avatar_url']);
                    $('#avatar-upload-progress').hide().empty();
                    rcl_notice(data.result['success'],'success');

		}
	});";
	return $script;
}
add_filter('file_footer_scripts_rcl','rcl_get_groups_footer_scripts');
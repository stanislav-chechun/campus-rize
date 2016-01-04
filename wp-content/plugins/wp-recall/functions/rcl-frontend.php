<?php

add_action('wprecall_init','init_user_lk',2);
function init_user_lk(){
    global $wpdb,$user_LK,$rcl_userlk_action,$rcl_options,$user_ID;

    $user_LK = false;
    $userLK = false;
    $get='user';

    if(isset($rcl_options['link_user_lk_rcl'])&&$rcl_options['link_user_lk_rcl']!='') $get = $rcl_options['link_user_lk_rcl'];
    if(isset($_GET[$get])) $userLK = $_GET[$get];

    if(!$userLK){
        if($rcl_options['view_user_lk_rcl']==1){
                $post_id = url_to_postid($_SERVER['REQUEST_URI']);
                if($rcl_options['lk_page_rcl']==$post_id) $user_LK = $user_ID;
        }else {
            if(isset($_GET['author'])) $user_LK = $_GET['author'];
            else{
                $url = (isset($_SERVER['SCRIPT_URL']))? $_SERVER['SCRIPT_URL']: $_SERVER['REQUEST_URI'];
                $url = preg_replace('/\?.*/', '', $url);
                $url_ar = explode('/',$url);
                foreach($url_ar as $key=>$u){
                    if($u!='author') continue;
                    $nicename = $url_ar[$key+1];
                    break;
                }
                if(!$nicename) return false;
                $user_LK = $wpdb->get_var($wpdb->prepare("SELECT ID FROM ".$wpdb->prefix."users WHERE user_nicename='%s'",$nicename));
            }
        }
    }else{
	$user_LK = $userLK;
    }

    if($user_LK){
        $rcl_userlk_action = $wpdb->get_var($wpdb->prepare("SELECT time_action FROM ".RCL_PREF."user_action WHERE user='%d'",$user_LK));
        rcl_fileapi_scripts();
    }
}

//добавляем уведомление в личном кабинете
function rcl_notice_text($text,$type='warning'){
    if(is_admin())return false;
    if (!class_exists('Rcl_Notify'))
        include_once RCL_PATH.'functions/rcl_notify.php';
    $block = new Rcl_Notify($text,$type);
}

add_action('wp_head','rcl_head_js_data',1);
function rcl_head_js_data(){
    global $user_ID;
    $data = "<script>
	var user_ID = $user_ID;
	var wpurl = '".preg_quote(trailingslashit(get_bloginfo('wpurl')),'/:')."';
	var rcl_url = '".preg_quote(RCL_URL,'/:')."';
	</script>\n";
    echo $data;
}

add_action('wp_footer','rcl_popup_contayner');
function rcl_popup_contayner(){
    echo '<div id="rcl-overlay"></div>
		  <div id="rcl-popup"></div>';
}

add_filter('wp_footer', 'rcl_footer_url');
function rcl_footer_url(){
	global $rcl_options;
	if($rcl_options['footer_url_recall']!=1) return false;
	if(is_front_page()&&!is_user_logged_in()) echo '<p class="plugin-info">'.__('The site works using the functionality of the plugin').'  <a target="_blank" href="http://wppost.ru/">Wp-Recall</a></p>';
}

function rcl_get_author_block(){
    global $post;

    $content = "<div id=block_author-rcl>";
    $content .= "<h3>".__('Author of publication','wp-recall')."</h3>";

    if(function_exists('rcl_add_userlist_follow_button')) add_filter('user_description','rcl_add_userlist_follow_button',90);

    $content .= rcl_get_userlist(array(
            'template' => 'rows',
            'include' => $post->post_author,
            'filter' => 0,
            'data'=>'rating_total,description,posts_count,user_registered,comments_count'
            //'orderby'=>'time_action'
        ));

    if(function_exists('rcl_add_userlist_follow_button')) remove_filter('user_description','rcl_add_userlist_follow_button',90);

    $content .= "</div>";

    return $content;
}

function rcl_get_miniaction($action,$user_id=false){
    global $wpdb;
    if(!$action) $action = $wpdb->get_var($wpdb->prepare("SELECT time_action FROM ".RCL_PREF."user_action WHERE user='%d'",$user_id));
    $last_action = rcl_get_useraction($action);
    $class = (!$last_action&&$action)?'online':'offline';

    $content = '<div class="status_author_mess '.$class.'">';
    if(!$last_action&&$action) $content .= '<i class="fa fa-circle"></i>';
    else $content .= 'не в сети '.$last_action;
    $content .= '</div>';

    return $content;
}

//заменяем ссылку автора комментария на ссылку его ЛК
add_filter('get_comment_author_url', 'rcl_get_link_author_comment');
function rcl_get_link_author_comment($href){
    global $comment;
    if($comment->user_id==0) return $href;
    $href = get_author_posts_url($comment->user_id);
    return $href;
}

add_action('wp_head','rcl_hidden_admin_panel');
function rcl_hidden_admin_panel(){
    global $rcl_options,$user_ID;

    if(!$user_ID){
        return show_admin_bar(false);
    }

    $access = 7;
    if(isset($rcl_options['consol_access_rcl'])) $access = $rcl_options['consol_access_rcl'];
    $user_info = get_userdata($user_ID);
    if ( $user_info->user_level < $access ){
            show_admin_bar(false);
    }else{
            return true;
    }
}

add_action('init','rcl_banned_user_redirect');
function rcl_banned_user_redirect(){
    global $user_ID;
    if(!$user_ID) return false;
    $user_data = get_userdata( $user_ID );
    $roles = $user_data->roles;
    $role = array_shift($roles);
    if($role=='banned') wp_die(__('Congratulations! You have been banned.','wp-recall'));
}

add_filter('the_content','rcl_message_post_moderation');
function rcl_message_post_moderation($cont){
global $post;
    if($post->post_status=='pending'){
        $mess = '<h3 class="pending-message">'.__('Publication pending approval!','wp-recall').'</h3>';
        $cont = $mess.$cont;
    }
    return $cont;
}

function rcl_buttons(){
    global $user_LK; $content = '';
    echo apply_filters( 'the_button_wprecall', $content, $user_LK );
}

function rcl_tabs(){
    global $user_LK; $content = '';
    echo apply_filters( 'the_block_wprecall', $content, $user_LK);
}

function rcl_before(){
    global $user_LK; $content = '';
    echo apply_filters( 'rcl_before_lk', $content, $user_LK );
}

function rcl_after(){
    global $user_LK; $content = '';
    echo apply_filters( 'rcl_after_lk', $content, $user_LK );
}

function rcl_header(){
    global $user_LK; $content = '';
    echo apply_filters('rcl_header_lk',$content,$user_LK);
}

function rcl_sidebar(){
    global $user_LK; $content = '';
    echo apply_filters('rcl_sidebar_lk',$content,$user_LK);
}

function rcl_content(){
    global $user_LK; $content = '';
    $content = apply_filters('rcl_content_lk',$content,$user_LK);
    echo $content;
}

function rcl_footer(){
    global $user_LK; $content = '';
    echo apply_filters('rcl_footer_lk',$content,$user_LK);
}

function rcl_action(){
    global $rcl_userlk_action;
    $last_action = rcl_get_useraction($rcl_userlk_action);
    $class = (!$last_action)? 'online': 'offline';
    $status = '<div class="status_user '.$class.'"><i class="fa fa-circle"></i></div>';
    if($last_action) $status .= __('not online','wp-recall').' '.$last_action;
    echo $status;
}

function rcl_avatar($size=120){
    global $user_LK; $after='';
    echo '<div id="rcl-contayner-avatar">';
	echo '<span class="rcl-user-avatar">'.get_avatar($user_LK,$size).'</span>';
	echo apply_filters('after-avatar-rcl',$after,$user_LK);
	echo '</div>';

}

function rcl_status_desc(){
    global $user_LK;
    $desc = get_the_author_meta('description',$user_LK);
    if($desc) echo '<div class="ballun-status">'
        //. '<span class="ballun"></span>'
        . '<p class="status-user-rcl">'.nl2br(esc_textarea($desc)).'</p>'
        . '</div>';
}

function rcl_username(){
    global $user_LK;
    echo get_the_author_meta('display_name',$user_LK);
}

function rcl_notice(){
    $notify = '';
    $notify = apply_filters('notify_lk',$notify);
    if($notify) echo '<div class="notify-lk">'.$notify.'</div>';
}

function rcl_sort_gallery($attaches,$key,$user_id=false){
    global $user_ID;

    if(!$attaches) return false;
    if(!$user_id) $user_id = $user_ID;
    $cnt = count($attaches);
    $v=$cnt+10;
    foreach($attaches as $attach){
            $id = str_replace($key.'-'.$user_id.'-','',$attach->post_name);
            if(!is_numeric($id)||$id>100) $id = $v++;
            if(!$id) $id = 0;
            foreach($attach as $k=>$att){
                    $gallerylist[(int)$id][$k]=$attach->$k;
            }
    }

    $b=0;
    $cnt = count($gallerylist);
    for($a=0;$b<$cnt;$a++){
            if(!isset($gallerylist[$a])) continue;
            $new[$b] = $gallerylist[$a];
            $b++;
    }
    for($a=$cnt-1;$a>=0;$a--){$news[]=(object)$new[$a];}

    return $news;
}
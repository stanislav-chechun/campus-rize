 <?php
//Create new tab for the map
add_action('init','add_tab_donation_form');
function add_tab_donation_form(){
    rcl_tab('donation_form','form_donation_block','Create your wish',array('public'=>0,'class'=>'fa-plus-square','order'=>18));
    
}
//class - http://fontawesome.io/icons/

function form_donation_block($user_lk){
   // $html .= do_shortcode('[public-form]');
    global $editpost,$rcl_options,$formfields,$formData;
//    var_dump($editpost);
    include_once RCL_PATH .'/add-on/publicpost/rcl_publicform.php';
    $ss = new Rcl_PublicForm($type_editor="1");
    $ss->type_editor = 1;
    $html .= rcl_get_edit_box('text');
    var_dump(rcl_get_edit_box(1));
   
    $html .= '<div class="rcl-public-editor">

			<div class="rcl-editor-content">
				'.rcl_get_editor_content($content).'
			</div>
			'.$panel.'
		</div>';
    $html .= RCL_PATH;
    return $html;
 }
 

function add_tab_donations_rcl($array_tabs){
	//donation_form - идентификатор вкладки дополнения
	//form_map_block - название функции формирующей контент вкладки дополнения
	$array_tabs['donation_form']='form_donation_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_donations_rcl');
?>
 <?php
//Create new tab for the map
add_action('init','add_tab_assistance_map');
function add_tab_assistance_map(){
    rcl_tab('assistance_map','form_map_block','Assistance in Ann Arbor',array('public'=>0,'class'=>'fa-eye','order'=>19));
    
}
//class - http://fontawesome.io/icons/

function form_map_block($user_lk){
    if (function_exists('sl_template')) {
        $html .= '<h3>' . __('If you ever find yourself in an extreme pinch and need help immediately, DO NOT worry. Of course, you can always call your Campus Rise mentor, but also know that there are TONS of resources here in Ann Arbor. Look at the map below to find a list of over 25 places that will offer emergency food, housing, and general assistance.') . '</h3>'; 
        $html .= sl_template('[STORE-LOCATOR]');
    } 
     return $html;
 }
 

function add_tab_map_rcl($array_tabs){
	//assistance_map - идентификатор вкладки дополнения
	//form_map_block - название функции формирующей контент вкладки дополнения
	$array_tabs['assistance_map']='form_map_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_map_rcl');
?>
<?php

add_filter('admin_options_wprecall','rcl_get_tablist_options');
function rcl_get_tablist_options($content){
    global $tabs_rcl,$rcl_options;

    rcl_sortable_scripts();

    $opt = new Rcl_Options('tabs');

	if(!$tabs_rcl) {
        $content .= $opt->options(__('Setting tabs','wp-recall'),__('Neither one tab personal account not found','wp-recall'));
        return $content;
    }

    $tabs = '<p>'.__('Sort your tabs by dragging them to the desired position','wp-recall').'</p>'
            . '<ul id="tabs-list-rcl" class="sortable">';

    if(isset($rcl_options['tabs']['order'])){
        foreach($rcl_options['tabs']['order'] as $order=>$key){
            if(!isset($tabs_rcl[$key])) continue;
            $tabs .= rcl_get_tab_option($key);
            $keys[$key] = 1;
        }
        foreach($tabs_rcl as $key=>$tab){
            if(isset($keys[$key])) continue;
            $tabs .= rcl_get_tab_option($key,$tab);
        }
    }else{

        foreach($tabs_rcl as $key=>$tab){
            if(!isset($tab['args']['order'])) continue;
            $order = $tab['args']['order'];
            if (isset($order)) {
                if (!isset($otabs[$order])) {
                    $otabs[$order][$key] = $tab;
                }else {
                    for($a=$order;1==1;$a++){
                        if(!isset($otabs[$a])){
                            $otabs[$a][$key] = $tab;
                            break;
                        }
                    }
                }
            }
        }

        foreach($tabs_rcl as $key=>$tab){
            if (!isset($tab['args']['order'])) {
                $otabs[][$key] = $tab;
            }
        }

        ksort($otabs);

        foreach($otabs as $order=>$vals){
            foreach($vals as $key=>$val){
                $tabs .= rcl_get_tab_option($key,$val);
            }
        }
    }
    $tabs .= '</ul>';

    $tabs .= '<script>jQuery(function(){jQuery(".sortable").sortable();return false;});</script>';

    $content .= $opt->options(__('Setting tabs','wp-recall'),$opt->option_block(array($tabs)));

    return $content;
}

function rcl_get_tab_option($key,$tab=false){
    global $rcl_options;
    $name = (isset($rcl_options['tabs']['name'][$key])) ?$rcl_options['tabs']['name'][$key] :  $tab['name'];
    return '<li>'
            . __('Name tab','wp-recall').': <input type="text" name="tabs[name]['.$key.']" value="'.$name.'">'
            . '<input type="hidden" name="tabs[order][]" value="'.$key.'">'
            . '</li>';
}
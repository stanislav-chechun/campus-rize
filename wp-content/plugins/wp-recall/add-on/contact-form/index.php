<?php
//Create new tab for tranfer money
add_action('init','add_tab_transfer_funds');
function add_tab_transfer_funds(){
    rcl_tab('transfer_funds','form_recall_block','Transfer of funds',array('public'=>0,'class'=>'fa-envelope','order'=>20));
}
function form_recall_block($user_lk){
    ?>
    <h3>You can transfer the money available to the goals that are made</h3>
    <form  class="form-inline" id="transfer_form" method="post">
        <div class="form-group">
            <label for="aims"> <?php  __( 'Choose your aim: '); ?> </label>
            <select name="give_form" required>
                <option id="aims" value="Чебурашка">Чебурашка</option>
                <option value="Крокодил Гена">Крокодил Гена</option>
                <option value="Шапокляк">Шапокляк</option>
                <option value="Крыса Лариса">Крыса Лариса</option>
            </select>
        </div>
        
        <div class="form-group">
            <div class="input-group">
            <label class="sr-only"  for="money"><?php __( 'Enter the amount: '); ?> </label>
            <div class="input-group-addon">$</div>
            <input name="money" class="form-control"  id="money" placeholder="Amount" type="text" value=""/>
            </div>
        </div>
        
        <input type="hidden" name="kp_transfer" value="process_kp_transfer"/>
        <?php wp_nonce_field('kp_nonce', 'kp_nonce'); ?>
        <p><input class="btn btn-default" type="submit" value="Transfer">
        <input class="btn btn-default" type="reset" value="Reset"></p>
    </form>
    <?php 
}

function add_tab_transfer_form_rcl($array_tabs){
	//transfer_funds - идентификатор вкладки дополнения
	//form_recall_block - название функции формирующей контент вкладки дополнения
	$array_tabs['transfer_funds']='form_recall_block'; 
	return $array_tabs; 
} 
add_filter('ajax_tabs_rcl','add_tab_transfer_form_rcl');
?>
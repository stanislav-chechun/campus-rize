<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rcl_robokassa_form
 *
 * @author Андрей
 */

add_action('init','rcl_add_robokassa_payment');
function rcl_add_robokassa_payment(){
    $pm = new Rcl_Robokassa_Payment();
    $pm->register_payment('robokassa');
}

class Rcl_Robokassa_Payment extends Rcl_Payment{

    public $form_pay_id;

    function register_payment($form_pay_id){
        $this->form_pay_id = $form_pay_id;
        parent::add_payment($this->form_pay_id, array('class'=>get_class($this),'request'=>'InvId' ));
        if(is_admin()) $this->add_options();
    }

    function add_options(){
        add_filter('rcl_pay_option',(array($this,'options')));
        add_filter('rcl_pay_child_option',(array($this,'child_options')));
    }

    function options($options){
        $options[$this->form_pay_id] = __('Robokassa','wp-recall');
        return $options;
    }

    function child_options($child){

        $opt = new Rcl_Options();

        $child .= $opt->child(
            array(
                'name'=>'connect_sale',
                'value'=>$this->form_pay_id
            ),
            array(
                $opt->title(__('Connection settings ROBOKASSA','wp-recall')),
                $opt->label(__('The ID of the store','wp-recall')),
                $opt->option('text',array('name'=>'robologin')),
                $opt->label(__('The status of the account ROBOKASSA','wp-recall')),
                $opt->option('select',array(
                    'name'=>'robotest',
                    'parent'=>true,
                    'options'=>array(
                        __('Work','wp-recall'),
                        __('Test','wp-recall')
                    )
                )),
                $opt->child(
                    array(
                        'name'=>'robotest',
                        'value'=>0
                    ),
                    array(
                        $opt->label(__('1 Password','wp-recall')),
                        $opt->option('password',array('name'=>'onerobopass')),
                        $opt->label(__('2 Password','wp-recall')),
                        $opt->option('password',array('name'=>'tworobopass'))
                    )
                ),
                $opt->child(
                    array(
                        'name'=>'robotest',
                        'value'=>1
                    ),
                    array(
                        $opt->label(__('1 Password','wp-recall')),
                        $opt->option('password',array('name'=>'test_onerobopass')),
                        $opt->label(__('2 Password','wp-recall')),
                        $opt->option('password',array('name'=>'test_tworobopass'))
                    )
                )
            )
        );

        return $child;
    }

    function pay_form($data){
        global $rmag_options;

        if($rmag_options['robotest']==1){
            $formaction = 'http://test.robokassa.ru/Index.aspx';
            $pass1 = $rmag_options['test_onerobopass'];
        }else{
            $formaction = 'https://merchant.roboxchange.com/Index.aspx';
            $pass1 = $rmag_options['onerobopass'];
        }

        $login = $rmag_options['robologin'];

        $crc = md5("$login:$data->pay_summ:$data->pay_id:$pass1:Shp_item=2:shpa=$data->user_id:shpb=$data->pay_type");

        $submit = ($data->pay_type==1)? __('Confirm the operation','wp-recall'): __('Pay through payment system','wp-recall');

        $fields = array(
            'MrchLogin'=>$login,
            'OutSum'=>$data->pay_summ,
            'InvId'=>$data->pay_id,
            'shpb'=>$data->pay_type,
            'shpa'=>$data->user_id,
            'SignatureValue'=>$crc,
            'Shp_item'=>'2',
            'Culture'=>'ru'
        );

        $form = parent::form($fields,$data,$formaction);

        return $form;
    }

    function result($data){
        global $rmag_options;

        $data->pay_summ = $_REQUEST["OutSum"];
        $data->pay_id = $_REQUEST["InvId"];
        $data->user_id = $_REQUEST["shpa"];
        $data->pay_type = $_REQUEST["shpb"];

        $crc = strtoupper($_REQUEST["SignatureValue"]);

        $my_crc = strtoupper(md5
                ("$data->pay_summ:"
                . "$data->pay_id:"
                . "".$rmag_options['tworobopass'].":"
                . "Shp_item=".$_REQUEST['Shp_item'].":"
                . "shpa=$data->user_id:"
                . "shpb=$data->pay_type"));

        if ($my_crc !=$crc){ rcl_mail_payment_error($my_crc); die;}

        if(!parent::get_pay($data)) parent::insert_pay($data);

        echo 'OK'; exit;

    }

    function success(){
        global $rmag_options;

        $data = array(
            'pay_id' => $_REQUEST["InvId"],
            'user_id' => $_REQUEST["shpa"]
        );

        if(parent::get_pay((object)$data)){
            wp_redirect(get_permalink($rmag_options['page_successfully_pay'])); exit;
        } else {
            wp_die(__('A record of the payment in the database was not found','wp-recall'));
        }

    }
}

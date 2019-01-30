<?php
/**
 * Created by PhpStorm.
 * User: webwerks
 * Date: 11/11/16
 * Time: 5:26 PM
 */
class Subscription extends MY_Controller{
 
    /**
     * Plans constructor.
     */
    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    /**
     *  View list of message Template
     */
    function index(){
        $this->data['subscription'] = Users_plans::all();
        $this->load->blade('admin.subscription.index',$this->data);
    }

}
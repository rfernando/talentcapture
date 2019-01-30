<?php

class Dashboard extends MY_Controller{

    function __construct() {
        $this->adminUserRequired = TRUE;
        parent::__construct();
    }

    function index(){
        $this->load->blade('admin.dashboard', $this->data);
    }
}
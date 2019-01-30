<?php

class Charities extends MY_Controller{

    function __construct() {
        $this->authUserRequired = TRUE;
        parent::__construct();
    }


}
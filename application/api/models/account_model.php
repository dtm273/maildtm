<?php

//no direct access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Driver API Model
 */
class Driver_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL & ~E_NOTICE);
        $this->load->helper('url');
        $this->load->helper('log_helper');
    }
    
    public function create($device_id){
        
    }
}

?>

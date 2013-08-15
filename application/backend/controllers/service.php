<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service extends AI_Controller {

    function __construct() {
        parent::__construct();

        $this->lang->load('common');
        $this->load->library('session');
    }

    function index() {
        $data = $this->session->userdata;
        $data['lang'] = $this->lang;

        $this->template->set('title', 'Service');
        $this->template->load('layout/default', 'service/index', $data);
    }

}

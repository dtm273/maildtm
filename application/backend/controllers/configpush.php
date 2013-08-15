<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ConfigPush extends AI_Controller {

    function __construct() {
        parent::__construct();

        $this->lang->load('common');
        $this->load->library('session');
    }

    function index() {
        $data = $this->session->userdata;
        $data['lang'] = $this->lang;

        $this->template->set('title', 'ConfigPush');
        $this->template->load('layout/default', 'configpush/index', $data);
    }

}

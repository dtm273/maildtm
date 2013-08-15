<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Account extends AI_Controller {

    function __construct() {
        parent::__construct();

        $this->lang->load('common');
        $this->load->library('session');
    }

    function index() {
        $data = $this->session->userdata;
        $data['lang'] = $this->lang;

        $this->template->set('title', 'Account');
        $this->template->load('layout/default', 'account/index', $data);
    }

}

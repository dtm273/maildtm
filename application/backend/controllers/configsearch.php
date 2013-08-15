<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ConfigSearch extends AI_Controller {

    private $item_name = 'configsearch';

    function __construct() {
        parent::__construct();

        //load library
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('table');
        $this->load->library('form_validation');
        $this->load->library('common');

        $this->load->helper('pagination');

        $this->lang->load('common');

        //load for page
        $this->lang->load($this->item_name);
    }

    //search
    function search() {
        $this->index();
    }

    //list
    function index($data = array()) {
        $item_name = $this->item_name;
        $data['item_name'] = $item_name;

        //get model
        $lang = $this->lang;

        // get request param
        $this->common->get_request_param($data);

        //set flash data
        $this->common->setFlashData($data);

        // load view
        $data['lang'] = $this->lang;
        $this->template->set('title', $this->lang->line('page_title'));

        $this->template->add_js('lib/uploadify/jquery.uploadify.min.js');
        $this->template->add_js('lib/tiny_mce/jquery.tinymce.js');
        $this->template->add_js('js/jquery.dragsort-0.5.1.min.js');
        $this->template->add_js('js/' . $item_name . '.js');
        $this->template->add_css('lib/uploadify/uploadify.css');
        $this->template->add_js('lib/colorbox/jquery.colorbox.min.js');
        $this->template->add_css('lib/colorbox/colorbox.css');

        $data['title'] = $this->lang->line('page_title');
        $searchconfig['page_limit'] = PAGE_LIMIT;
        (SEARCH_ZN_ENABLED) ? $searchconfig['search_zn_enabled'] = 'yes' : $searchconfig['search_zn_enabled'] = 'no';
        (SEARCH_NCT_ENABLED) ? $searchconfig['search_nct_enabled'] = 'yes' : $searchconfig['search_nct_enabled'] = 'no';
        (SEARCH_NS_ENABLED) ? $searchconfig['search_ns_enabled'] = 'yes' : $searchconfig['search_ns_enabled'] = 'no';
        
        $data['searchconfig'] = $searchconfig;
        $data['submit_button_label'] = $this->lang->line('button_update');
        $this->template->load('layout/default', $item_name . '/index', $data);
    }

    //add or edit
    function edit() {
        
    }

    // validation rules
    function _set_rules() {
//		$this->form_validation->set_rules('site_name', $this->lang->line('site_name'), 'trim|required');
    }

}


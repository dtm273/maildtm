<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class App_Version extends AI_Controller {

    private $item_name = 'app_version';

    function __construct() {
        parent::__construct();

        //load library
        $this->load->library('session');
        $this->load->library('pagination');
        $this->load->library('table');
        $this->load->library('form_validation');
        $this->load->library('common');
        $this->load->library('pushmanager');

        $this->load->helper('pagination');

        $this->lang->load('common');

        //load for page
        //$this->lang->load($this->item_name);
        $this->load->model($this->item_name . '_model', '', TRUE);
    }

    //list
    function index() {
        $item_name = $this->item_name;
        $data['item_name'] = $item_name;

        $model_name = $item_name . '_model';
        $model = $this->$model_name;
        $lang = $this->lang;
        
        // get request param
        $search_param = array('osType');
        $this->common->get_request_param($data, $search_param);
        
        // load data
        $items = $model->get_paged_list(PAGE_LIMIT, $data['limitstart'], $data['keyword'])->result();
        $total = $model->count_all($data['keyword']);

        // generate pagination
        $condition = '';
        if($data['keyword_osType'])
            $condition = 'keyword_osType=' . $data['keyword_osType'];
        
        if ($data['keyword']) 
            $condition = 'keyword=' . $data['keyword'];
        
        if($data['keyword_osType'] && $data['keyword'])
            $condition = 'keyword=' . $data['keyword'] .'&' .'keyword_osType=' . $data['keyword_osType'];
        
        $url = $item_name .'/search?' .$condition;
        
        $pagination = setPaginationParam($this->pagination, $url, $total);
        $data['pagination_info'] = getPaginationInfoStyle($this->lang->line('pagination_count'), $total);
        $data['pagination_links'] = getPaginationLinksStyle($pagination);
        
        // set template table data		
        $this->table->set_template(
                array('table_open' => '<table id="list" class="table table-striped table-bordered dTableR">')
        );
        //set table heading
        $tbl_heading = array(
            '0' => array('data' => '<input type="checkbox" data-tableid="list" class="select_rows" name="select_rows">', 'width' => '1%'),
            '1' => array('data' => $lang->line('version'), 'width' => '10%'),
            '2' => array('data' => $lang->line('os_type'), 'width' => '20%'),
            '3' => array('data' => $lang->line('status'), 'width' => '10%'),
            '5' => array('data' => $lang->line('created'), 'width' => '20%'),
        );
        $this->table->set_heading($tbl_heading);
        //set table row	
        foreach ($items as $item) {
                $this->table->add_row('<input type="checkbox" class="select_row" name="row_sel" id="item_' . $item->id . '">', 
                '<a href="' . site_url($item_name . '/edit/' . $item->id) . '">' . $item->version . '</a>',
                $item->os_type,
                ($item->status == 1) ? 'Mới' : '',
                $item->created
            );
        }
        //generate table
        $data['table'] = $this->table->generate();

        //set flash data
        $this->common->setFlashData($data);
        
        // load view
        $data['lang'] = $this->lang;
        $data['data'] = $data;
        $data['action'] = site_url('version_manager');
        $this->template->add_js('lib/colorbox/jquery.colorbox.min.js');
        $this->template->add_css('lib/colorbox/colorbox.css');
        //$this->template->add_js('js/' . $item_name . '.js');
        $this->template->set('title', $this->lang->line('page_title'));
        $this->template->load('layout/default', $item_name . '/index', $data);
    }
    
    //search
    function search() {
        $this->index();
    }
    
    //add or edit
    function edit() {
        $item_name = $this->item_name;

        //get model
        $model_name = $item_name . '_model';
        $model = $this->$model_name;

        // get request param
        $this->common->get_request_param($data);


        //get input param
        $item_data = $model->get_by_id($data['id'])->row();
        if ($item_data)
            $item = get_object_vars($item_data);
        if (!isset($item) || !$item) {
            $fields = $model->get_fields();
            $item = $this->caommon->get_input_param($fields);
        }
        //process action submit
        if ($data['action'] == 'submit') {
            //avoid direct access
            $isSubmit = $this->input->post('submit');
            if (!$isSubmit) {
                redirect($item_name . '/add');
            }

            // set validation properties
            $this->_set_rules();

            // run validation
            if ($this->form_validation->run() == false) {
                $this->form_validation->set_error_delimiters('', '');
                $data['message'] = validation_errors();
                $data['message_code'] = '2';
            } else {
                // save data
                $id = $this->input->post('id');
                if (!$id) {
                    // insert data
                    $model->save($item);
                    $this->session->set_flashdata('message', $this->lang->line('add_success'));
                } else {
                    // update data
                    $model->update($id, $item);
                    $this->session->set_flashdata('message', $this->lang->line('update_success'));
                }

                // set user message	
                $this->session->set_flashdata('message_code', '1');
                redirect($this->item_name);
            }
        }

        // set common properties
        $data['action'] = site_url($this->item_name . '/add/submit');
        $data['link_back'] = anchor($this->item_name, $this->lang->line('button_back'), array('class' => 'btn btn-inverse dropdown-toggle'));

        // load view
        $data['lang'] = $this->lang;
        $data[$this->item_name] = $item;

        $this->template->add_js('lib/uploadify/jquery.uploadify.min.js');
        $this->template->add_js('lib/tiny_mce/jquery.tinymce.js');
        $this->template->add_js('js/jquery.dragsort-0.5.1.min.js');
        $this->template->add_js('js/' . $item_name . '_edit.js');
        $this->template->add_css('lib/uploadify/uploadify.css');
        $this->template->add_js('lib/colorbox/jquery.colorbox.min.js');
        $this->template->add_css('lib/colorbox/colorbox.css');
        if ($data['page'] == 'add') {
            $data['submit_button_label'] = $this->lang->line('button_add');
            $data['title'] = $this->lang->line('add');
            $this->template->set('title', $this->lang->line('add'));
        } else {
            $data['submit_button_label'] = $this->lang->line('button_edit');
            $data['title'] = $this->lang->line('update');
            $this->template->set('title', $this->lang->line('update'));
        }
        $this->template->load('layout/default', $item_name . '/edit', $data);
    }
    
    // validation rules
    function _set_rules() {
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required');
    }

}


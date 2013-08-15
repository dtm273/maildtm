<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Push_Notification extends AI_Controller {

    private $item_name = 'push';

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
        $this->lang->load($this->item_name);
        $this->load->model($this->item_name . '_model', '', TRUE);
    }

    //list
    function index() {
        $item_name = $this->item_name;
        $data['item_name'] = $item_name;

        $model_name = $item_name . '_model';

        // get request param
        $this->common->get_request_param($data);
       
        //set flash data
        $this->common->setFlashData($data);

        // load view
        $data['lang'] = $this->lang;
        $data['data'] = $data;
        $data['action'] = site_url($this->item_name . '_notification/send/submit');
        $this->template->add_js('lib/colorbox/jquery.colorbox.min.js');
        $this->template->add_css('lib/colorbox/colorbox.css');
        $this->template->add_js('js/' . $item_name . '.js');
        $this->template->set('title', $this->lang->line('page_title'));
        $this->template->load('layout/default', $item_name . '/index', $data);
    }
    
    function send(){
        $this->common->get_request_param($data);
        
        // run validation
        if ($data['action'] == 'submit') {
            $isSubmit = $this->input->post('submit');
            if (!$isSubmit) {
                redirect('push_notification' . '/send');
            }

            // set validation properties
            $this->_set_rules();
            
            if ($this->form_validation->run() == false) {
                $this->form_validation->set_error_delimiters('', '');
                $data['message'] = validation_errors();
                $data['message_code'] = '2';
            } else {
                // save data
                $title = $this->input->post('title');
                $message = $this->input->post('message');
                $link = $this->input->post('link');
                $token_test = $this->input->post('token_test');
                $push_user_type = $this->input->post('push_user_type');
                $os_type = $this->input->post('os_type');
                
                if($token_test){
                    $acme = 'test|'.$link;
                    $device[] = array(
                        'device_id' => 'TEST_PUSH',
                        'token_id'  => $token_test
                    );
                    $this->push_to_devices($message, $title, $device, $os_type, $acme);
                    $this->session->set_flashdata('message', $this->lang->line('push_test_success'));
                }
                if($push_user_type == 1){ // push to all drivers
                    $acme = 'drivers|'.$link;
                    $devices = $this->push_model->getAllUserToken(1);
                    $this->push_to_devices($message, $title, $devices, $os_type, $acme);
                    $this->session->set_flashdata('message', $this->lang->line('push_driver_success'));
                }
                if($push_user_type == 2){ // push to all customer
                    $acme = 'customers|'.$link;
                    $devices = $this->push_model->getAllUserToken(0);
                    $this->push_to_devices($message, $title, $devices, $os_type, $acme);
                    
                    $this->session->set_flashdata('message', $this->lang->line('push_customer_success'));
                }
                
                $this->session->set_flashdata('message_code', '1');
                redirect('push_notification');
            }
        }
        
        // set user message	
        $data['lang'] = $this->lang;
        $data['data'] = $data;
        $data['action'] = site_url('push_notification' . '/send/submit');
        $this->session->set_flashdata('message_code', '1');
        $this->template->load('layout/default', 'push/index', $data);
    }
    // validation rules
    function _set_rules() {
        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required');
        $this->form_validation->set_rules('message', $this->lang->line('message'), 'trim|required');
    }
    /*
     * Push thong bao chung thuc
     */
    function pushDriverCf(){
        $title = 'ALOTAXI';
        $message = 'Tai khoan chua chung thuc';
        $deviceIds = $this->drivers_model->getCfDrivers();
        $link = "http://alotaxi.vcinema.vn/mobile/updatestatus";
        $acme = '2;' .$link;
        $this->pushmanager->push($message, $title, $deviceIds, $acme);
    }
    
    /*
     * Push thong bao khi co version moi
     */
    function pushNewVersion(){
        $title = 'ALOTAXI';
        $message = 'Alotaxi phien ban moi';
        // Get all user token 
        //$deviceIds = $this->drivers_model->getAllUserToken();
        $link_appstore = "";
        $acme = '1;' .$link_appstore;
        //$this->pushmanager->pushToToken($message, $title, $deviceIds, $acme);
    }
    

}


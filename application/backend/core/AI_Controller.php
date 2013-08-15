<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AI_Controller extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
       
        // load library
        $this->load->helper('url');
        $this->load->library(array('tank_auth'));        
        $this->load->library('session');
        
        if (!$this->tank_auth->is_logged_in()) {
        	redirect('/auth/login/');
        } else {
        	$data['user_id']	= $this->tank_auth->get_user_id();
        	$data['username']	= $this->tank_auth->get_username();  

        	$this->session->set_userdata($data);
        }
        
    }

}

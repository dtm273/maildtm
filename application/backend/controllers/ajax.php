<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
			
		$this->load->library('security');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->library('pagination');
		$this->load->library('table');
		
		//load helper
		$this->load->library('common');
		$this->load->helper('pagination');
		$this->lang->load('common');
	}

	function index()
	{
		redirect('index');
	}

	//action remove
	function remove(){
		$obj = $this->uri->segment(3);
		switch($obj){
			case 'companyimage':
				$this->removeCompanyImage();
				break;
			case 'driversimage':
				$this->removeDriversImage();
				break;
			default:
				echo 'Ajax object don\'t exist!';
				break;
		}		
	}
	
	
	/*
	 * Remove drivers image
	* success	: no return
	* fail			: return error message
	*/
	private function removeDriversImage(){
		$base_path = BASEPATH.'../assets/images/';
	
		//get param
		$name 				= $this->input->post('name');
		$subfolder 		= $this->input->post('subfolder');
	
		$dir    = $base_path . $subfolder;
		$DelFilePath	= $dir . '/' .$name;
		if (file_exists($DelFilePath)) {
			unlink ($DelFilePath);
		} else {
			echo 'File ' . $name . ' not exists!';
		}
	}
		
	/* 
	 * Remove company image
	 * success	: no return
	 * fail			: return error message
	 */
	private function removeCompanyImage(){
		$base_path = BASEPATH.'../assets/images/';
		
		//get param
		$name 				= $this->input->post('name');
		$subfolder 		= $this->input->post('subfolder');
		
		$dir    = $base_path . $subfolder;
		$DelFilePath	= $dir . '/' .$name;
		if (file_exists($DelFilePath)) {
			unlink ($DelFilePath);
		} else {
			echo 'File ' . $name . ' not exists!';
		}
	}
}


<?php
if ( ! defined('VBOT')) exit('No direct script access allowed');

class connection{
	//get content by curl
	public function get_content($link){
		$result = file_get_contents($link);
		return $result;
	}
}
<?php
require_once(APPPATH . 'libraries/REST_Controller.php');

class YouTube extends REST_Controller {

    var $link;
    var $xpath;
    var $domain = "http://www.youtube.com";

    //initialize
    public function __construct() {
        parent::__construct();
        $this->load->library('connection');
        $this->load->library('Html');

        $this->setLink();

        $this->setXpath();
    }

    function index_get() {
        $param = $this->get();
        $this->process($param);
    }

    function index_post() {
        $param = $this->post();
        $this->process($param);
    }

    function setXpath() {
        $xpath = array(
            
        );
        $this->xpath = $xpath;
    }

    function setLink() {
        $link = array(
            'search' => "",
        );
        $this->link = $link;
    }

    //process api
    private function process($param) {
        //get param
        $keyword = $param['keyword'];
        $keyword = str_replace(' ', '+', $keyword);

    }

}

?>
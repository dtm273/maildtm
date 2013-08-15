<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class Suggest extends REST_Controller {
    var $link;
    var $xpath;
    var $domain = "http://mp3.zing.vn";

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
    
    function setXpath(){
        $xpath = array(
            'top_video_sum'         => "//div[@class='singer-item singer-video']/div",
            'search_result_baihat'  => "//*[@id='zme-autocomplete-list']/li[4]/ul/li",
            'search_result_video'   => "//*[@id='zme-autocomplete-list']/li[3]/ul/li",
            'search_result_album'   => "//*[@id='zme-autocomplete-list']/li[2]/ul/li"
        );
        
        $this->xpath = $xpath;
    }
    
    function setLink(){
        $link = array(
            'search'                => "http://mp3.zing.vn/tim-kiem/bai-hat.html",
            'suggest_search'        => "http://mp3.zing.vn/suggest/search"
        );
        
        $this->link = $link;
    }

    //process api
    private function process($param) {
        //get param
        $keyword = $param['keyword'];
        /** type (filter theo)
         * song: 
         * artist:
         * video: 
         * album
         */
        
        $keyword = str_replace(' ', '+', $keyword);
        
        $xpath_search = $this->link['suggest_search'] . "?term=$keyword";
        $result = $this->connection->get_content($xpath_search);
        
        print_r($result);
    }

}
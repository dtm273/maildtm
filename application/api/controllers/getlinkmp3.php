<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class GetLinkMp3 extends REST_Controller {
    var $link;
    var $xpath;
    var $domain = "http://mp3.zing.vn";
    var $api_name = "GetLinkMp3";

    //initialize
    public function __construct() {
        parent::__construct();
        $this->load->library('connection');
        $this->load->library('Html');
        $this->load->helper('url'); 
        $this->load->helper('log_helper');
        
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
            'link_mp3'              => "//div[@class='player _flashPlayer']/script[1]",
        );
        $this->xpath = $xpath;
    }
    
    function setLink(){
        $link = array(
            'search'                => "http://mp3.zing.vn/tim-kiem/bai-hat.html",
            'search_album'          => "http://mp3.zing.vn/tim-kiem/playlist.html",
            'search_video'          => "http://mp3.zing.vn/tim-kiem/video.html",
        );
        $this->link = $link;
    }

    //process api
    private function process($param) {
        $time = time();
        write_log("INFO", "Start $this->api_name at: $time");
        $this->setXpath();
        if(!isset($param['link_url']) || !isset($param['source'])){
            $result = array(
                'metadata'  => array('result' => 0),
                'data' => ''
            );
            $result_json = json_encode($result);
            print_r($result_json);
            return $result_json;
        }
        $link_url = $param['link_url'];
        if(!preg_match('/(.*).html/', $link_url)){
            $link_url = $link_url . '.html';
        }
        if($param['source'] == 'zn'){
            $linkmp3 = $this->getlinkmp3_zn($link_url);
        }
        if($linkmp3){
            $dataResult = array(
                'metadata'  => array('result' => 1),
                'data' => $linkmp3
            );
        }else{
            $dataResult = array(
                'metadata'  => array('result' => 0),
                'data' => '',
            );
        }
        
        $result_json = json_encode($dataResult);
        print_r($result_json);
        
        $time = time();
        write_log("INFO", "End $this->api_name at: $time");
        
        if(isset($param['uid'])){
            $uid = $param['uid'];
            $this->saveHistory($uid,  $result);
        }
        return $result_json;
    }

    function getlinkmp3_zn($link_url){
        $content_mp3 = $this->connection->get_content($link_url);
        if($content_mp3 == '') return FALSE;
        $objmp3 = new Html();
        $objmp3->init($content_mp3);
        $xpath_mp3 = $this->xpath['link_mp3'];
        $item_mp3 = $objmp3->get_xpath_content($xpath_mp3);
        if(empty($item_mp3)) return FALSE;
        
        if(!preg_match('/xmlURL=http(.*)&/', $item_mp3, $linkXML_arr)) return FALSE;
        $linkXML = str_replace(array('xmlURL=', '&'), '', $linkXML_arr[0]);
        
        $contentXML = $this->connection->get_content($linkXML);
        if($contentXML == '') return FALSE;
        
        if(!preg_match('/http:\/\/stream(.*)mp3/', $contentXML, $linkmp3_arr)) return FALSE;
        $link_mp3 = $linkmp3_arr[0];
        
        return $link_mp3;
    }
    
    function saveHistory($uid,  $result){
        
    }
}
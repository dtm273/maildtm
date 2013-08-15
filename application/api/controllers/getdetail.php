<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class GetDetail extends REST_Controller {
    var $link;
    var $xpath;
    var $xpath_album;
    var $xpath_video;
    var $domain_zing = "http://mp3.zing.vn";
    var $domain_ntc = "http://www.nhaccuatui.com";
    var $api_name = "GetDetail";

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
        );
        $this->link = $link;
    }

    private function process($param) {
        $time = time();
        write_log("INFO", "Start $this->api_name at: $time");
        if(!isset($param['search_type']) || !isset($param['link_url']) || !isset($param['source'])){
            $result = array(
                'metadata'  => array('result' => 1),
                'data' => ''
            );
            $result_r = json_encode($result);
            print_r($result_r);
            return $result_r;
        }
        
        $link_url = $param['link_url'];
        if(!preg_match('/(.*).html/', $link_url)){
            $link_url = $link_url . '.html';
        }
        $source = $param['source'];
        $search_type = $param['search_type'];
        
        $res = array();
        if($source == 'zn' && $search_type == 'song'){
            $res = $this->getSongDetailZn($link_url);
        }
        if($source == 'zn' && $search_type == 'album'){
            $res = $this->getAlbumDetailZn($link_url);
        }
        if($source == 'zn' && $search_type == 'video'){
            $res = $this->getVideoDetailZn($link_url);
        }
        //$this->response($result);
        if($res == FALSE || empty($res)){
            $result = array(
                'metadata'  => array('result' => 0),
                'data' => ''
            );
        }else{
            $result = array(
                'metadata'  => array('result' => 1),
                'data' => $res
            );
        }
        $time = time();
        write_log("INFO", "End $this->api_name at: $time");
        $result_json = json_encode($result);
        print_r($result_json);
        return $result_json;
    }
    public function getSongDetailZn($link_url){
        $content_mp3 = $this->connection->get_content($link_url);
        if(trim($content_mp3) == ''){
            return FALSE;
        }
        $objmp3 = new Html();
        $objmp3->init($content_mp3);
        $xpath_mp3 = $this->xpath['link_mp3'];
        $item_mp3 = $objmp3->get_xpath_content($xpath_mp3);
        if(trim($item_mp3) == ''){
            return FALSE;
        }
        $check = preg_match('/xmlURL=http(.*)&/', $item_mp3, $linkXML_arr);
        if(!$check){
            return FALSE;
        }
        $linkXML = str_replace(array('xmlURL=', '&'), '', $linkXML_arr[0]);
        $contentXML = $this->connection->get_content($linkXML);
        $check = preg_match('/http:(.*)source>/', $contentXML, $linkmp3_arr);
        if(!$check){
            return FALSE;
        }
        $link_mp3 = preg_replace('/=]]><\/source>/', '', $linkmp3_arr[0]);
        $result['link_mp3'] = $link_mp3;
        return $result;
    }

    function setAlbumXpathZn(){
        $xpath = array(
            'song_list' => "//*[@id='_plContainer']/li",
            
        );
        $this->xpath_album = $xpath;
    }
    /*
     * Chi tiet 1 album
     * Luot nghe
     * Nam phat hanh
     * So bai hat
     * The loai
     * Song Array
     */
    public function getAlbumDetailZn($link_url){
        $sub_domain = "http://mp3.zing.vn";
        $this->setAlbumXpathZn();
        $content = $this->connection->get_content($link_url);
        if($content == ''){
            return FALSE;
        }
        $objHtml = new Html();
        $objHtml->init($content);
        $xpath_albumList = $this->xpath_album['song_list'];
        $song_sum = $objHtml->get_xpath_node_length($xpath_albumList);
        $song = array();
        if($song_sum){
            for($s_i = 0; $s_i < $song_sum; $s_i++){
                $r_index = $s_i+1;
                $xpath_song = $xpath_albumList. "[$r_index]/div/a[@id='_itemDetail$s_i']";
                $item_song = $objHtml->get_xpath_attr($xpath_song);
                if(empty($item_song)){
                    return FALSE;
                }
                $song_title_str = $item_song['title'];
                $song_title_arr = explode('-', $song_title_str);
                $name = trim($song_title_arr[0]);
                $singer = trim($song_title_arr[1]);
                
                $song_link = $sub_domain .$item_song['href'];
                $song[$s_i]['link_url'] = $song_link;
                $song[$s_i]['name'] = $name;
                $song[$s_i]['singer'] = $singer;
                $song[$s_i]['source'] = 'zn';
                
//                $song_url_str .= $song_link .'|';
            }
        }else{
            return FALSE;
        }
        
        // get link mp3
        //http://localhost:81/phucanh/api/getlinkmp3?urls=link&source=zn
//        $linkGetMp3 = base_url() ."api/getlinkmp3";
//        $param = '?search_type=song' . "&urls=$song_url_str" . '&source=zn';
//        $linkGetMp3 .= $param;
//        $contentLinkMp3 = $this->connection->get_content($linkGetMp3);
        //print_r($contentLinkMp3); die;
        
//        $linkmp3 = json_decode($contentLinkMp3);
        if(!empty($song)){
            $result = $song;
        }else{
            return FALSE;
        }
        
        return $result;
    }
    function setVideoXpathZn(){
        $xpath = array(
            'xml_link'      => "//div[@id='_videoPlayer']/script[1]",
            'xml_link_full' => "//div[@id='_videoPlayer']",
            
        );
        $this->xpath_video = $xpath;
    }
    
    public function getVideoDetailZn($link_url){
        $result['link_mp4'] = '';
        $this->setVideoXpathZn();
        $sub_domain = "http://mp3.zing.vn";
        $this->setAlbumXpathZn();
        $content = $this->connection->get_content($link_url);
        if(trim($content) == ''){
            return FALSE;
        }
        $objHtml = new Html();
        $objHtml->init($content);
        
        $xpath_xml = $this->xpath_video['xml_link'];
        $item_str = $objHtml->get_xpath_content($xpath_xml);
        if(trim($item_str) == ''){
            return FALSE;
        }
//        $xpath_xml_full = $this->xpath_video['xml_link_full'];
//        $item_full_str = $objHtml->get_xpath_content($xpath_xml_full);
//        //var_dump($item_str);
//        $b = html_entity_decode($item_full_str);
//        var_dump($item_full_str);
//        print_r(htmlspecialchars($item_full_str));die;
        
        preg_match('/xmlURL(.*);textad/', $item_str, $linkxml_arr);
        $linkxml = str_replace(array('xmlURL=', ';textad', '&amp'), '', $linkxml_arr[0]);
        $content_xml = $this->connection->get_content($linkxml);
        //var_dump($content_xmt);
        preg_match_all('/http:(.*).mp4/', $content_xml, $linkmp4_arr);
        $link_mp4 = $linkmp4_arr[0];
        $result['link_mp4'] = $link_mp4;
        
        return $result ;
        
    }
}
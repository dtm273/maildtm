<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class GetLinkMp3 extends REST_Controller {
    var $link;
    var $xpath;
    var $domain = "http://mp3.zing.vn";

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
        write_log("INFO", "Start API Search at: $time");
        if(!isset($param['urls'])){
            $result = array(
                'metadata'  => array('result' => 1),
                'data' => ''
            );
            $result_json = json_encode($result);
            print_r($result_json);
            return $result_r;
        }
        $links = $param['urls'];
        $link_arr = explode('|', $links);
        
        $links = $link_arr;;
        
        foreach($links as $key => $link){
            if($link != ''){
                $searchLink = base_url() . "api/getdetail" . "?link_url=$link" ."&source=zn&search_type=song";
                var_dump($searchLink);
                $page[] = $searchLink;
            }
        }
        $sum_link = count($page);
        //var_dump($page);
        $resultArr = $this->multiple_threads_request($page);
        var_dump($resultArr); die;
        $result = array();
        for($res_i = 0; $res_i < $sum_link; $res_i++){
                $res_json = $resultArr[$res_i];
                //print_r($res_json); 
                $resObj = json_decode($res_json);
                var_dump($resObj);
                if($resObj) $result[]['link_mp3'] = $resObj->link_mp3;
                //var_dump($resObj); die;
        }
        //$this->response($result);
        
        $dataResult = array(
            'metadata'  => array('result' => 1),
            'data' => $result
        );
        $result_json = json_encode($dataResult);
        print_r($result_json);
        
        $time = time();
        write_log("INFO", "End API Search at: $time");
        if(isset($param['uid'])){
            $uid = $param['uid'];
        }
        return $result_r;
    }
    function multiple_threads_request($nodes) {
        $mh = curl_multi_init();
        $curl_array = array();
        foreach ($nodes as $i => $url) {
            
            $curl_array[$i] = curl_init($url);
            curl_setopt($curl_array[$i], CURLOPT_USERPWD, "insidemusic:apiinsidemusic"); 
            curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($mh, $curl_array[$i]);
        }
        $running = NULL;
        do {
            usleep(10000);
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $res = array();
        //$result = '';
        foreach ($nodes as $i => $url) {
            $res[$i] = curl_multi_getcontent($curl_array[$i]);
            //print_r($res[$i]); die;
            //$result .= curl_multi_getcontent($curl_array[$i]);
        };

        foreach ($nodes as $i => $url) {
            curl_multi_remove_handle($mh, $curl_array[$i]);
        }
        curl_multi_close($mh);
        return $res;
    }

}
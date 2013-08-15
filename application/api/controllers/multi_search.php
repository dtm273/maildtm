<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class Multi_Search extends REST_Controller {
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
            'top_video_sum'         => "//div[@class='singer-item singer-video']/div",
            'top_album_sum'         => "//div[@class='singer-item singer-album']/div",
            'list_album_sum'        => "//div[@class='video-item']",
            'list_video_sum'        => "//div[@class='video-item']",
            'top_song'              => "//div[@class='content-item ie-fix']",
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
        write_log("INFO", "Start API Search at: $time");
        //Tu khoa tim kiem
        $keyword = $param['keyword'];
        //Ket qua search trang so
        $page = $param['page'];
        // Search theo
        $search_type = $param['search_type'];
        
        // Khoi tao tim kiem
        $keyword = str_replace(' ', '+', $keyword);
        
//        $time = time();
//        print_r("start" .$time);
        $search_zing_url = base_url() . "api/search" . "?keyword=$keyword" ."&search_type=$search_type&page=1";
        //$search_nct = base_url() ."api/searchnct" ."?keyword=$keyword" ."&search_type=song&page=1";
        $search_ns_url = base_url() . "api/searchns" ."?keyword=$keyword" .'.html' ."&search_type=$search_type&page=1";
        $search_zing = array('url' => $search_zing_url);
        $search_ns = array('url' => $search_ns_url);
        
        //$links_search = array($search_zing, $search_ns);
        $links_search = array($search_zing_url, $search_ns_url);
        //var_dump($links_search);
        $res = $this->multiple_threads_request($links_search);
        //$result = $this->connection->multiple_threads_get_content($links_search);
        //print_r($res); die;
        // Loc lai ket qua
        
        $dataResult = array();
        $res_cnt = 0;
        for ($res_i = 0; $res_i < 2; $res_i++) {
            $res_json = $res[$res_i];
            //print_r($res_json); 
            $resObj = json_decode($res_json);
            if($resObj == NULL){
                continue;               
            }
            var_dump($resObj);
            if($resObj->$search_type){
                $data[$res_i] = $resObj->$search_type->data;
            
                //var_dump($data[$i]); die;
                $sum_data_obj = count($data[$res_i]);
                for ($obj_i = 0; $obj_i < $sum_data_obj; $obj_i++) {
                    $dataArr = get_object_vars($data[$res_i][$obj_i]);
                    //var_dump($dataArr); 
                    $dataResult[$res_cnt] = $dataArr;
                    $res_cnt++;
                }
            }
        }
        $time = time();
        write_log("INFO", "End API Search at: $time");
        if(isset($param['uid'])){
            $uid = $param['uid'];
        }
        print_r($result);
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
            //$result .= curl_multi_getcontent($curl_array[$i]);
        };

        foreach ($nodes as $i => $url) {
            curl_multi_remove_handle($mh, $curl_array[$i]);
        }
        curl_multi_close($mh);
        
        return $res;
    }

}
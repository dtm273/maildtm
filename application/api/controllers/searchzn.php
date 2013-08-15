<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class SearchZn extends REST_Controller {
    var $link;
    var $xpath;
    var $domain = "http://mp3.zing.vn";

    //initialize
    public function __construct() {
        parent::__construct();
        $this->load->library('connection');
        $this->load->library('Html');
        $this->load->helper('url');
        
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
//        $time = time();
//        print_r("Start at" .$time);
        //Tu khoa tim kiem
        $keyword = $param['keyword'];
        //Ket qua search trang so
        $page = $param['page'];
        // Search theo
        $search_type = $param['search_type'];
        
        // Khoi tao tim kiem
        $keyword = str_replace(' ', '+', $keyword);
        
        $type = $param['type'];
        if($type == 'multi'){
            $res = $this->multisearch($keyword, $search_type);
            $pages = count($res);
            //var_dump($pages); die;
            //print_r($res[1]);
            $dataResult = array();
            $res_cnt = 0;
            for($res_i = 0; $res_i < $pages; $res_i++){
                $res_json = $res[$res_i];
                //print_r($res_json); 
                $resObj = json_decode($res_json);
                $data[$res_i] = $resObj->$search_type->data;
                //var_dump($data[$i]); die;
                $sum_data_obj = count($data[$res_i]);
                for($obj_i = 0; $obj_i < $sum_data_obj; $obj_i++){
                    $dataArr = get_object_vars($data[$res_i][$obj_i]);
                    //var_dump($dataArr); 
                    $dataResult[$res_cnt] = $dataArr;
                    $res_cnt++;
                    var_dump($res_cnt);
                }
            }
            //var_dump($res_cnt);
            $result['metadata'] = '';
            $result['data'] = array();
            $result[$search_type]['metadata'] = '';
            $result[$search_type]['data'] = $dataResult;
        }
        
        if($search_type == 'all'){

            $result_album = $this->search_playlist($keyword, $page);
            $result_video = $this->search_video($keyword, $page);
            $result_song = $this->search_song($keyword, $page);
            $result['album'] = $result_album;
            $result['video'] = $result_video;
            $result['song'] = $result_song;
        }
        else if($search_type == 'song'){

            $result_song = $this->search_song($keyword, $page);
            $result['song'] = $result_song;
        }
        else if($search_type == 'video'){
        
            $result_video = $this->search_video($keyword, $page);
            $result['video'] = $result_video;
        }
        else if($search_type == 'album'){
            
            $result_album = $this->search_playlist($keyword, $page);
            $result['album'] = $result_album;
        }
        
//        $time = time();
//        print_r("End at" .$time);
        $this->response($result);
    }
    /*
    * Lay ra danh sach top album
    */
    function search_album($keyword, $page){
        $link_search = $this->link['search_album'] . "?q=$keyword";
        if($page != '') $link_search .= "&p=$page";
        $content = $this->connection->get_content($link_search);
        $objhtml = new Html();
        $objhtml->init($content);
            
        $result['metadata'] = '';
        $result['data'] = array();
//        $sum_top_album = $objhtml->get_xpath_node_length($this->xpath['top_album_sum']);
        $sum_top_album = 5;
        $cnt_album = 0;
        for($i = 1; $i <= $sum_top_album; $i++){
            $xpath_item_ablum = $this->xpath['top_album_sum'] ."[$i]/a";
            $item_album = $objhtml->get_xpath_attr($xpath_item_ablum);
            if(empty($item_album)) break;
            
            $link_url = $this->domain . $item_album['href'];
            $title_str = html_entity_decode(strip_tags($item_album['title']), ENT_NOQUOTES, "UTF-8");
            $title_arr = explode('-', $title_str);
            $name = html_entity_decode(strip_tags($title_arr[0]), ENT_NOQUOTES, "UTF-8");
            $singer = html_entity_decode(strip_tags($title_arr[1]), ENT_NOQUOTES, "UTF-8");
            
            //thumb 
            $xpath_thumb = $this->xpath['top_album_sum'] ."[$i]/a/img";
            $item_thumb = $objhtml->get_xpath_attr($xpath_thumb);
            $thumb = $item_thumb['src'];
            
            $result['data'][$cnt_album]['link_url'] = $link_url;
            $result['data'][$cnt_album]['name'] = $name;
            $result['data'][$cnt_album]['singer'] = $singer;
            $result['data'][$cnt_album]['thumb'] = $thumb;
            $cnt_album++;
        }
        return $result;
    }
    
    function search_playlist($keyword, $page){
        $link_search = $this->link['search_album'] . "?q=$keyword";
        if($page != '') $link_search .= "&p=$page";
        $content = $this->connection->get_content($link_search);
        $objhtml = new Html();
        $objhtml->init($content);
        
        $result['metadata'] = '';
        $result['data'] = array();
//        $sum_top_album = $objhtml->get_xpath_node_length($this->xpath['list_album_sum']);
        $sum_top_playlist = 21;
        $cnt_album = 0;
        for($i = 2; $i <= $sum_top_playlist; $i++){
            $xpath_item_ablum = $this->xpath['list_album_sum'] ."[$i]/div/h3/a";
            $item_album = $objhtml->get_xpath_attr($xpath_item_ablum);
            if(empty($item_album)) break;
            
            $link_url = $this->domain . $item_album['href'];
            
            $name = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_item_ablum)), ENT_NOQUOTES, "UTF-8"));
            
            $xpath_singer = $this->xpath['list_album_sum'] ."[$i]/div/p[1]/a";
            $singer = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_singer)), ENT_NOQUOTES, "UTF-8"));
            
            //thumb 
            $xpath_thumb = $this->xpath['list_album_sum'] ."[$i]/a/img";
            $item_thumb = $objhtml->get_xpath_attr($xpath_thumb);
            $thumb = $item_thumb['src'];
            
            //view
            $xpath_view = $this->xpath['list_album_sum'] ."[$i]/div/p[2]"; 
            $item_str = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_view)), ENT_NOQUOTES, "UTF-8"));
            $item_arr = explode('|', $item_str);
            $view_str = $item_arr[1];
            $view = preg_replace('/Lượt xem:/', '', $view_str);
            $view = trim($view);
            $date_created_str = $item_arr[0];
            $date_created = preg_replace('/Ngày tạo:/', '', $date_created_str);
            $date_created = trim($date_created);
            
            
            $result['data'][$cnt_album]['link_url'] = $link_url;
            $result['data'][$cnt_album]['name'] = $name;
            $result['data'][$cnt_album]['singer'] = $singer;
            $result['data'][$cnt_album]['thumb'] = $thumb;
            $result['data'][$cnt_album]['view'] = $view;
            $result['data'][$cnt_album]['date_created'] = $date_created;
            $result['data'][$cnt_album]['source'] = 'zn';
            
            $cnt_album++;
        }
        return $result;
    }

    function search_video($keyword, $page){
        $link_search = $this->link['search_video'] . "?q=$keyword";
        if($page != '') $link_search .= "&p=$page";
        $content = $this->connection->get_content($link_search);
        $objhtml = new Html();
        $objhtml->init($content);
            
        $result['metadata'] = '';
        $result['data'] = array();
//        $sum_top_album = $objhtml->get_xpath_node_length($this->xpath['list_video_sum']);
        $sum_top_video = 21;
        $cnt_video = 0;
        for($i = 2; $i <= $sum_top_video; $i++){
            $xpath_item_video = $this->xpath['list_video_sum'] ."[$i]/div/h3/a";
            $item_video = $objhtml->get_xpath_attr($xpath_item_video);
            if(empty($item_video)) break;
            $link_url = $this->domain . $item_video['href'];
            
            $name = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_item_video)), ENT_NOQUOTES, "UTF-8"));
            
            $xpath_singer = $this->xpath['list_video_sum'] ."[$i]/div/p[1]/a";
            $singer = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_singer)), ENT_NOQUOTES, "UTF-8"));
            
            //thumb 
            $xpath_thumb = $this->xpath['list_video_sum'] ."[$i]/a/img";
            $item_thumb = $objhtml->get_xpath_attr($xpath_thumb);
            $thumb = $item_thumb['src'];
            
            //view
            $xpath_view = $this->xpath['list_video_sum'] ."[$i]/div/p[2]"; 
            $item_str = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_view)), ENT_NOQUOTES, "UTF-8"));
            $item_arr = explode('|', $item_str);
            $view_str = $item_arr[1];
            $view = preg_replace('/Lượt xem:/', '', $view_str);
            $view = trim($view);
            $date_created_str = $item_arr[0];
            $date_created = preg_replace('/Ngày tạo:/', '', $date_created_str);
            $date_created = trim($date_created);
            
            $result['data'][$cnt_video]['link_url'] = $link_url;
            $result['data'][$cnt_video]['name'] = $name;
            $result['data'][$cnt_video]['singer'] = $singer;
            $result['data'][$cnt_video]['thumb'] = $thumb;
            $result['data'][$cnt_video]['view'] = $view;
            $result['data'][$cnt_video]['date_created'] = $date_created;
            $result['data'][$cnt_video]['source'] = 'zn';
            
            $cnt_video++;
        }
        return $result;
    }

    /*
    * Lay ra danh sach top video
    */        
    function search_top_video($objhtml){
        $result['metadata'] = '';
        $result['data'] = array();
//        $sum_top_video = $objhtml->get_xpath_node_length($this->xpath['top_video_sum']);
        $sum_top_video = 5;
        $cnt_video = 0;
        for($i = 1; $i <= $sum_top_video; $i++){
            $xpath_item_video = $this->xpath['top_video_sum'] ."[$i]/a";
            $item_video = $objhtml->get_xpath_attr($xpath_item_video);
            if(empty($item_video)) break;
            
            $link_url = $this->domain . $item_video['href'];
            $title_str = html_entity_decode(strip_tags($item_video['title']), ENT_NOQUOTES, "UTF-8");
            $title_arr = explode('-', $title_str);
            $name = html_entity_decode(strip_tags($title_arr[0]), ENT_NOQUOTES, "UTF-8");
            $singer = html_entity_decode(strip_tags($title_arr[1]), ENT_NOQUOTES, "UTF-8");
            
            //thumb
            $xpath_thumb = $this->xpath['top_video_sum'] ."[$i]/a/img";
            $item_thumb = $objhtml->get_xpath_attr($xpath_thumb);
            $thumb = $item_thumb['src'];
            
            $result['data'][$cnt_video]['link_url'] = $link_url;
            $result['data'][$cnt_video]['name'] = $name;
            $result['data'][$cnt_video]['singer'] = $singer;
            $result['data'][$cnt_video]['thumb'] = $thumb;
            $cnt_video++;
        }
        
        return $result;
    }
    
    public function multisearch($keyword, $search_type){
        
        if($search_type == 'song'){
            $searchZnPage1 = base_url() . "api/searchzn" . "?keyword=$keyword" ."&search_type=song&page=1&type=";
            $searchZnPage2 = base_url() . "api/searchzn" . "?keyword=$keyword" ."&search_type=song&page=2&type=";
            $searchZnPage3 = base_url() . "api/searchzn" . "?keyword=$keyword" ."&search_type=song&page=3&type=";
            $page1 = array('url' => $searchZnPage1);
            $page2= array('url' => $searchZnPage2);
            $page3 = array('url' => $searchZnPage3);
            $pages = array($page1, $page2, $page3);
            $result = $this->connection->multiple_threads_get_content($pages);
            
            return $result;
        }
    }
    /*
    * Lay da danh sach top cac bai hat
    */
    function search_song($keyword, $page){
        $link_search = $this->link['search'] . "?q=$keyword";
        if($page != '') $link_search .= "&p=$page";
        $content = $this->connection->get_content($link_search);
        $objhtml = new Html();
        $objhtml->init($content);
            
        $result['metadata'] = '';
        $result['data'] = array();
//        $sum_top_song = $objhtml->get_xpath_node_length($this->xpath['top_song']);
        $sum_top_song = 19;
        $cnt_song = 0;
        for($i = 1; $i < $sum_top_song; $i++){
            // Thong tin bai hat
            $xpath_item_song = $this->xpath['top_song'] ."[$i]/h3/a";
            $item_song = $objhtml->get_xpath_attr($xpath_item_song);
            if(empty($item_song)) break;
            
            $link_url = $this->domain . $item_song['href'];
            $title_str = html_entity_decode(strip_tags($item_song['title']), ENT_NOQUOTES, "UTF-8");
            $title_arr = explode(' - ', $title_str);
            $name = html_entity_decode(strip_tags($title_arr[0]), ENT_NOQUOTES, "UTF-8");
            $singer = html_entity_decode(strip_tags($title_arr[1]), ENT_NOQUOTES, "UTF-8");
            
            // dung luong
            $xpath_quality = $this->xpath['top_song'] ."[$i]/p[1]";
            $item_quality_str = html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_quality)), ENT_NOQUOTES, "UTF-8");
            $item_quality_arr = explode('|', $item_quality_str);
            $n = count($item_quality_arr);
            $quality = trim($item_quality_arr[$n-1]);
            
            // ngay dang
             $xpath_publish = $this->xpath['top_song'] ."[$i]/p[2]";
             $item_publish_str = html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_publish)), ENT_NOQUOTES, "UTF-8");
             $item_publish_arr = explode('|', $item_publish_str);
             $publish_str = trim($item_publish_arr[1]);
             $publish_arr = explode(' ', $publish_str);
             $n = count($publish_arr);
             $publish = trim($publish_arr[$n - 1]);
             $publish = str_replace('/', '-', $publish);
             // luot nghe
             $view_str = $item_publish_arr[2];
             $view_arr = explode(':', $view_str);
             $view_tmp = trim($view_arr[1]);
             $view_tmp_srr = explode(' ', $view_tmp);
             $view = trim($view_tmp_srr[0]);
            
            $result['data'][$cnt_song]['link_url'] = $link_url;
            $result['data'][$cnt_song]['name'] = $name;
            $result['data'][$cnt_song]['singer'] = $singer;
            $result['data'][$cnt_song]['link_download'] = '';
            $result['data'][$cnt_song]['link_mp3'] = '';
            $result['data'][$cnt_song]['quality'] = $quality;
            $result['data'][$cnt_song]['view'] = $view;
            $result['data'][$cnt_song]['source'] = 'zn';
            
            $cnt_song++;
        }
        return $result;
    }

}
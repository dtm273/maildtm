<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class AmericaCharts extends REST_Controller {
    var $link;
    var $xpath;
    var $domain = "http://www.americasmusiccharts.com/index.cgi?fmt=H1";
    var $sub_domain = "http://www.americasmusiccharts.com/";

    //initialize
    public function __construct() {
        parent::__construct();
        $this->load->library('connection');
        $this->load->library('Html');
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
            'song_item_list' => '//body/table[3]/tr[2]/td/table[2]/tr',
            'top_video_sum'         => "//div[@class='singer-item singer-video']/div",
            'top_album_sum'         => "//div[@class='singer-item singer-album']/div",
            'list_album_sum'        => "//div[@class='video-item']",
            'list_video_sum'        => "//div[@class='video-item']",
            'top_song'              => "//div[@class='content-item ie-fix']",
            'first-search-song'            => "//div[@class='first-search-song']",
            'link_mp3'              => "//div[@class='player _flashPlayer']/script[1]",
            
        );
        $this->xpath = $xpath;
    }
    function setLink(){
        $link = array(
            'search_domain'         => "http://mp3.zing.vn/",
            'search'                => "http://mp3.zing.vn/tim-kiem/bai-hat.html",
            'search_album'          => "http://mp3.zing.vn/tim-kiem/playlist.html",
            'search_video'          => "http://mp3.zing.vn/tim-kiem/video.html",
        );
        $this->link = $link;
    }
    
    private function process($param) {
        $this->setXpath();
        $this->setLink();
        
        $content = $this->connection->get_content($this->domain);
        $objhtml = new Html();
        $objhtml->init($content);
        
        $xpath_itemSum = $this->xpath['song_item_list'];
        $songSum = $objhtml->get_xpath_node_length($xpath_itemSum);
        $result['data'] = array();
        $cnt = 0;
        //var_dump($songSum);
        for($si = 3; $si < $songSum; $si++){
            $xpath_itemSinger = $xpath_itemSum . "[$si]/td[3]";
            $singer = trim(strip_tags($objhtml->get_xpath_content($xpath_itemSinger)));
            
            $xpath_songTitle = $xpath_itemSum . "[$si]/td[5]";
            $title = trim(strip_tags($objhtml->get_xpath_content($xpath_songTitle)));
            
            $data = $this->search_song($title, $singer);
            if(!empty($data)){
                $result['data'][$cnt] = $data;
                $cnt++;
            }
        }
        
        $result_json = json_encode($result);
        print_r($result_json);
        //$this->response($result);
    }
    function search_song($title, $singer){
        $keyword = $title .' ' .$singer;
        $keyword = str_replace(' ', '+', $keyword);
            
        $link_search = $this->link['search'] . "?q=$keyword";
        $searchContent = $this->connection->get_content($link_search);
        $objhtml = new Html();
        $objhtml->init($searchContent);
            
        
        $sum_top_song = $objhtml->get_xpath_node_length($this->xpath['top_song']);
        $cnt_song = 0;
        $result = array();
        for($i = 1; $i < $sum_top_song; $i++){
            // Thong tin bai hat
            $xpath_item_song = $this->xpath['first-search-song'] ."[$i]/h3/a";
            $item = $objhtml->get_xpath_content($this->xpath['first-search-song']);
            $item_song = $objhtml->get_xpath_attr($xpath_item_song);
            
            if(empty($item_song)) break;
            
            $link_url = $this->link['search_domain'] . $item_song['href'];
            $title_str = html_entity_decode(strip_tags($item_song['title']), ENT_NOQUOTES, "UTF-8");
            $title_arr = explode(' - ', $title_str);
            $name = html_entity_decode(strip_tags($title_arr[0]), ENT_NOQUOTES, "UTF-8");
            $singer = html_entity_decode(strip_tags($title_arr[1]), ENT_NOQUOTES, "UTF-8");
            
//            // dung luong
//            $xpath_quality = $this->xpath['first-search-song'] ."[$i]/p[1]";
//            $item_quality_str = html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_quality)), ENT_NOQUOTES, "UTF-8");
//            $item_quality_arr = explode('|', $item_quality_str);
//            $n = count($item_quality_arr);
//            $quality = trim($item_quality_arr[$n-1]);
//            
//            // ngay dang
//             $xpath_publish = $this->xpath['first-search-song'] ."[$i]/p[2]";
//             $item_publish_str = html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_publish)), ENT_NOQUOTES, "UTF-8");
//             $item_publish_arr = explode('|', $item_publish_str);
//             $publish_str = trim($item_publish_arr[1]);
//             $publish_arr = explode(' ', $publish_str);
//             $n = count($publish_arr);
//             $publish = trim($publish_arr[$n - 1]);
//             $publish = str_replace('/', '-', $publish);
//             // luot nghe
//             $view_str = $item_publish_arr[2];
//             $view_arr = explode(':', $view_str);
//             $view_tmp = trim($view_arr[1]);
//             $view_tmp_srr = explode(' ', $view_tmp);
//             $view = trim($view_tmp_srr[0]);
            
            $result[$cnt_song]['link_url'] = $link_url;
            $result[$cnt_song]['title'] = $name;
            $result[$cnt_song]['singer'] = $singer;
            //$result['data'][$cnt_song]['link_download'] = '';
            //$result['data'][$cnt_song]['link_mp3'] = '';
            //$result['data'][$cnt_song]['quality'] = $quality;
           // $result['data'][$cnt_song]['view'] = $view;
            $result[$cnt_song]['source'] = 'zn';
            
            $cnt_song++;
        }
        return $result;
    }
}
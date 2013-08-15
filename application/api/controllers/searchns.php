<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class SearchNS extends REST_Controller {

    var $link;
    var $xpath;
    var $domain = "http://nhacso.net";

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
            'top_artist_sum'    => "//*[@id='search_nghesi_new']/ul/li",
            'top_video_sum'     => "//*[@id='search_video_new']/ul/li",
            'top_album_sum'     => "//*[@id='search_album_new']/ul/li",
            'top_playlist_sum'  => "//*[@id='search_playlist_new']/ul/li",
            'top_song'          => "//*[@id='search_baihat_new']/ul/li",
            'link_mp3'          => "",
        );
        $this->xpath = $xpath;
    }

    function setLink() {
        $link = array(
            'search' => "http://nhacso.net/tim-kiem/",
        );
        $this->link = $link;
    }

    //process api
    private function process($param) {
        //get param
        $keyword = $param['keyword'];
        $keyword = str_replace(' ', '+', $keyword);
        $link_search = $this->link['search'] . "$keyword" .'.html';
        $content = $this->connection->get_content($link_search);
        $objhtml = new Html();
        $objhtml->init($content);

        /*
         * Lay ra danh sach top album
         */
        $result['albums']['metadata'] = '';
        $result['albums']['data'] = array();
        $sum_top_album = $objhtml->get_xpath_node_length($this->xpath['top_album_sum']);
        $cnt_album = 0;
        if($sum_top_album) {
            for ($i = 1; $i <= $sum_top_album; $i++) {
                $xpath_item_ablum = $this->xpath['top_album_sum'] . "[$i]/p[1]/a";
                $item_album = $objhtml->get_xpath_attr($xpath_item_ablum);

                $link_url = $item_album['href'];
                $name = trim(html_entity_decode(strip_tags($item_album['title']), ENT_NOQUOTES, "UTF-8"));

                $xpath_singer = $this->xpath['top_album_sum'] . "[$i]/p[2]/span/a";
                $item_singer = $objhtml->get_xpath_content($xpath_singer);
                $singer = trim(html_entity_decode(strip_tags($item_singer), ENT_NOQUOTES, "UTF-8"));
                //thumb 
                $xpath_thumb = $this->xpath['top_album_sum'] . "[$i]/div/a/img";
                $item_thumb = $objhtml->get_xpath_attr($xpath_thumb);
                $thumb = $item_thumb['src'];

                $result['albums']['data'][$cnt_album]['link_url'] = $link_url;
                $result['albums']['data'][$cnt_album]['name'] = $name;
                $result['albums']['data'][$cnt_album]['singer'] = $singer;
                $result['albums']['data'][$cnt_album]['thumb'] = $thumb;
                $result['albums']['data'][$cnt_album]['source'] = 'ns';
                $cnt_album++;
            }
        }
        
        /*
         * Lay ra danh sach top video
         */
        $result['video']['metadata'] = '';
        $result['video']['data'] = array();
        $sum_top_video = $objhtml->get_xpath_node_length($this->xpath['top_video_sum']);
        $cnt_video = 0;
        if($sum_top_video){
            for($i = 1; $i <= $sum_top_video; $i++){
                $xpath_item_video = $this->xpath['top_video_sum'] . "[$i]/p[1]/a";
                $item_video = $objhtml->get_xpath_attr($xpath_item_video);

                $link_url = $item_video['href'];
                $name = trim(html_entity_decode(strip_tags($item_video['title']), ENT_NOQUOTES, "UTF-8"));

                $xpath_singer = $this->xpath['top_video_sum'] . "[$i]/p[2]/span/a";
                $item_singer = $objhtml->get_xpath_content($xpath_singer);
                $singer = trim(html_entity_decode(strip_tags($item_singer), ENT_NOQUOTES, "UTF-8"));

                //thumb 
                $xpath_thumb = $this->xpath['top_video_sum'] . "[$i]/div/a/img";
                $item_thumb = $objhtml->get_xpath_attr($xpath_thumb);
                $thumb = $item_thumb['src'];

                $result['video']['data'][$cnt_video]['link_url'] = $link_url;
                $result['video']['data'][$cnt_video]['name'] = $name;
                $result['video']['data'][$cnt_video]['singer'] = $singer;
                $result['video']['data'][$cnt_video]['thumb'] = $thumb;
                $result['video']['data'][$cnt_video]['source'] = 'ns';
                $cnt_video++;
            }
        }
        /*
         * Lay ra danh sach top playlist
         */
        $result['playlist']['metadata'] = '';
        $result['playlist']['data'] = array();
        $sum_top_playlist = $objhtml->get_xpath_node_length($this->xpath['top_playlist_sum']);
        $cnt_playlist = 0;
        if($sum_top_playlist){
            for($i = 1; $i <= $sum_top_playlist; $i++){
                $xpath_item_playlist = $this->xpath['top_playlist_sum'] . "[$i]/p[1]/a";
                $item_playlist = $objhtml->get_xpath_attr($xpath_item_playlist);

                $link_url = $item_playlist['href'];
                $name = trim(html_entity_decode(strip_tags($item_playlist['title']), ENT_NOQUOTES, "UTF-8"));

                $xpath_singer = $this->xpath['top_playlist_sum'] . "[$i]/p[2]/span/a";
                $item_singer = $objhtml->get_xpath_content($xpath_singer);
                $singer = trim(html_entity_decode(strip_tags($item_singer), ENT_NOQUOTES, "UTF-8"));

                //thumb 
                $xpath_thumb = $this->xpath['top_playlist_sum'] . "[$i]/div/a/img";
                $item_thumb = $objhtml->get_xpath_attr($xpath_thumb);
                $thumb = $item_thumb['src'];

                $result['playlist']['data'][$cnt_playlist]['link_url'] = $link_url;
                $result['playlist']['data'][$cnt_playlist]['name'] = $name;
                $result['playlist']['data'][$cnt_playlist]['singer'] = $singer;
                $result['playlist']['data'][$cnt_playlist]['thumb'] = $thumb;
                $result['playlist']['data'][$cnt_playlist]['source'] = 'ns';
//                var_dump($result['playlist']['data']); die;
                $cnt_video++;
            }
        }
        /*
         * Lay da danh sach top cac bai hat
         */
        $result['song']['metadata'] = '';
        $result['song']['data'] = array();
        $sum_top_song = $objhtml->get_xpath_node_length($this->xpath['top_song']);
        $cnt_song = 0;
        if($sum_top_song){
            for($i = 1; $i < $sum_top_song; $i++){
                // Thong tin bai hat
                $xpath_item_song = $this->xpath['top_song'] ."[$i]/h2/a[1]";
                $item_song = $objhtml->get_xpath_attr($xpath_item_song);
                $link_url = trim($item_song['href']);
                $name = trim($item_song['title']);

                $xpath_singer = $this->xpath['top_song'] . "[$i]/div[2]/h3[1]/span";
                $singer = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_singer)), ENT_NOQUOTES, "UTF-8"));

                $xpath_author = $this->xpath['top_song'] . "[$i]/div[2]/h3[1]/span";
                $author = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_singer)), ENT_NOQUOTES, "UTF-8"));


                // dung luong
                $xpath_view = $this->xpath['top_song'] ."[$i]/div[@class='statics']/p/span";
                $item_view_str = html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_view)), ENT_NOQUOTES, "UTF-8");
                $view = trim($item_view_str);

                //quality
                $xpath_quality = $this->xpath['top_song'] ."[$i]/div[@class='statics']/p[2]";
                $item_quality_str = html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_quality)), ENT_NOQUOTES, "UTF-8");
                $item_quality_arr = explode('|', $item_quality_str);
                $quality = trim($item_quality_arr[1]);
                $duration = trim($item_quality_arr[0]);
                
                //link mp3
                $xpath_link_mp3 = $this->xpath['top_song'] ."[$i]/div[1]/ol/li[1]/a";
                $item_link_mp3 = $objhtml->get_xpath_attr($xpath_link_mp3);
                $link_str = $item_link_mp3['onclick'];
                preg_match('/http:(.*).mp3/', $link_str, $link_arr);
                $link_mp3 = $link_arr[0];

                $result['song']['data'][$cnt_song]['link_url'] = $link_url;
                $result['song']['data'][$cnt_song]['name'] = $name;
                $result['song']['data'][$cnt_song]['singer'] = $singer;
                $result['song']['data'][$cnt_song]['link_download'] = '';
                $result['song']['data'][$cnt_song]['link_mp3'] = $link_mp3;
                $result['song']['data'][$cnt_song]['quality'] = $quality;
                $result['song']['data'][$cnt_song]['duration'] = $duration;
                $result['song']['data'][$cnt_song]['view'] = $view;
                $result['song']['data'][$cnt_song]['source'] = 'ns';
                $cnt_song++;
            }
        }
        $this->response($result);
    }

}

?>
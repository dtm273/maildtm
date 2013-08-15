<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class SearchNCT extends REST_Controller {

    var $link;
    var $xpath;
    var $domain = "http://www.nhaccuatui.com";

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
            'top_video_sum' => "//div[@class='new-clip clip-home']/ul/li",
            'top_album_sum' => "//div[@class='al-pl']/ul/li",
            'top_song' => "//div[@class='new-song song-home']/ul/li",
            'link_mp3' => "",
        );
        $this->xpath = $xpath;
    }

    function setLink() {
        $link = array(
            'search' => "http://www.nhaccuatui.com/tim-kiem",
        );
        $this->link = $link;
    }

    //process api
    private function process($param) {
        //get param
        $keyword = $param['keyword'];
        $keyword = str_replace(' ', '+', $keyword);
        
        $link_search = $this->link['search'] . "?q=$keyword";
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
        for ($i = 1; $i <= $sum_top_album; $i++) {
            $xpath_item_ablum = $this->xpath['top_album_sum'] . "[$i]/div/a";
            $item_album = $objhtml->get_xpath_attr($xpath_item_ablum);

            $link_url = $item_album['href'];
            $name = trim(html_entity_decode(strip_tags($item_album['title']), ENT_NOQUOTES, "UTF-8"));
            
            $xpath_singer = $this->xpath['top_album_sum'] . "[$i]/p/a";
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
            $result['albums']['data'][$cnt_album]['source'] = 'nct';
            $cnt_album++;
        }
        
        /*
         * Lay ra danh sach top video
         */
        $result['video']['metadata'] = '';
        $result['video']['data'] = array();
        $sum_top_video = $objhtml->get_xpath_node_length($this->xpath['top_video_sum']);
        $cnt_video = 0;
        for($i = 1; $i <= $sum_top_video; $i++){
            $xpath_item_video = $this->xpath['top_video_sum'] . "[$i]/div/a";
            $item_video = $objhtml->get_xpath_attr($xpath_item_video);
            
            $link_url = $item_video['href'];
            $name = trim(html_entity_decode(strip_tags($item_video['title']), ENT_NOQUOTES, "UTF-8"));
            
            $xpath_singer = $this->xpath['top_video_sum'] . "[$i]/p/a";
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
            $result['video']['data'][$cnt_video]['source'] = 'nct';
            
            $cnt_video++;
        }
        /*
         * Lay da danh sach top cac bai hat
         */
        $result['song']['metadata'] = '';
        $result['song']['data'] = array();
        $sum_top_song = $objhtml->get_xpath_node_length($this->xpath['top_song']);
        $cnt_song = 0;
        for($i = 1; $i < $sum_top_song - 2; $i++){
            // Thong tin bai hat
            $xpath_item_song = $this->xpath['top_song'] ."[$i]/div[1]/h4/span/a[2]";
            $item_song = $objhtml->get_xpath_attr($xpath_item_song);
            $link_url = $this->domain . $item_song['href'];
            $name = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_item_song)), ENT_NOQUOTES, "UTF-8"));
            
            
            $xpath_singer = $this->xpath['top_song'] . "[$i]/div[2]";
            $singer = trim(html_entity_decode(strip_tags($objhtml->get_xpath_content($xpath_singer)), ENT_NOQUOTES, "UTF-8"));
            $singer = preg_replace(array('/\n/', '/  /'), '', $singer);
            
            // link download
            $xpath_download = $this->xpath['top_song'] . "[$i]/div[@class='add-pl']/a[1]";
            $item_download = $objhtml->get_xpath_attr($xpath_download);
            $key = $item_download['key'];
            $link_GetLinkDl = "http://www.nhaccuatui.com/download/song/" . $key;
            $content_dl = $this->connection->get_content($link_GetLinkDl);
            $obj_linkDl = json_decode($content_dl);
            $link_download = $obj_linkDl->data->stream_url;
            
            // dung luong
            $xpath_view = $this->xpath['top_song'] ."[$i]/div[@class='num']";
            $item_view_str = html_entity_decode($objhtml->get_xpath_content($xpath_view), ENT_NOQUOTES, "UTF-8");
            $view = trim($item_view_str);
            
            $result['song']['data'][$cnt_song]['link_url'] = $link_url;
            $result['song']['data'][$cnt_song]['name'] = $name;
            $result['song']['data'][$cnt_song]['singer'] = $singer;
            $result['song']['data'][$cnt_song]['link_download'] = $link_download;
            $result['song']['data'][$cnt_song]['link_mp3'] = '';
            $result['song']['data'][$cnt_song]['quality'] = '';
            $result['song']['data'][$cnt_song]['view'] = $view;
            $result['song']['data'][$cnt_song]['source'] = 'nct';
            $cnt_song++;
        }
        
        $this->response($result);
    }

}

?>
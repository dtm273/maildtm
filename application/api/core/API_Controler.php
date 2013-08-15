<?php

require_once(APPPATH . 'libraries/REST_Controller.php');

class API_Controller extends REST_Controller {
    var $link;
    var $xpath;
    var $domain;

    //initialize
    public function __construct() {
        parent::__construct();
        $this->load->library('connection');
        $this->load->library('Html');
        $this->load->library('Log');
        
        $this->setDomain();
        $this->setLink();
        $this->setXpath();
    }
    public static function setDomain(){
        
    }
    public static function setLink(){
        
    }
    public static function setXpath(){
        
    }
    public function  multiThreadsRequest($urls, $authen = NULL) {
        $mh = curl_multi_init();
        $curl_array = array();
        foreach ($urls as $i => $url) {
            $curl_array[$i] = curl_init($url);
            if($authen){
                $username = $authen['username'];
                $password = $authen['password'];
                curl_setopt($curl_array[$i], CURLOPT_USERPWD, $username .':'.$password); 
            }
            // MaiDTM added
            curl_setopt($curl_array[$i], CURLOPT_HEADER, 0);
            curl_setopt($curl_array[$i], CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl_array[$i], CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
            curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, TRUE); //tra ve string chu k in ra man hinh
            curl_setopt($curl_array[$i], CURLOPT_URL, $link);
            // MaiDTM added END
            curl_setopt($curl_array[$i], CURLOPT_RETURNTRANSFER, TRUE);
            curl_multi_add_handle($mh, $curl_array[$i]);
        }
        $running = NULL;
        do {
            usleep(10000);
            curl_multi_exec($mh, $running);
        } while ($running > 0);

        $res = array();
        foreach ($urls as $i => $url) {
            $res[$i] = curl_multi_getcontent($curl_array[$i]);
        };

        foreach ($urls as $i => $url) {
            curl_multi_remove_handle($mh, $curl_array[$i]);
        }
        curl_multi_close($mh);
        return $res;
    }
}
?>

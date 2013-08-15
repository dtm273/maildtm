<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Push_Model extends CI_Model
{
   /**
    * Constructor
    * @access public
    */
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        //$this->load->library('push_notification');
    }
    function get_setting(){
        
    }
    
    function get_all_deviceid(){
        $this->db->select('token_id, device_id');
        $query = $this->db->get('push_device');
         
        return $query->result();
    }
    
    // 0: ios | 1: android
    function get_deviceid( $os = '0' ){
        $this->db->select('token_id, device_id');
        $this->db->where('os_type', $os);
        $query = $this->db->get('push_device');
         
        return $query->result();
    }
    /**
     * Get tokens by device type 
     * @param type $os_type
     * @return type
     */
    function getTokenByDeviceType($os_type, $deviceIds = NULL){
        $this->db->select(array('token_id', 'device_id'));
        
        if($deviceIds){
            $this->db->where_in('device_id', $deviceIds);
        }
        $this->db->where('os_type', $os_type);
        $query = $this->db->get('push_device');
        
         return $query->result();
    }
    
    /**
     * Get token by device id 
     * @param type $deviceId
     * @return type
     */
    function getTokenByDeviceId($deviceId){
        $this->db->select('token_id, device_id');
        $this->db->where('device_id', $deviceId);
        $query = $this->db->get('push_device');
        return $query->result();
    }
    /**
     * 
     * @param type $user_type : 0: customer | 1: drivers | NULL: all
     * @return token array
     */
    function getAllUserToken($user_type = NULL){
        $this->db->select('token_id, device_id');
        if($user_type){
            $this->db->where('user_type', $user_type);
        } 
        $query = $this->db->get('push_device');
        $objects = $query->result();
        $result = array();
        $sum = count($objects);
        if($sum > 0){
            for($i = 0; $i < $sum; $i++){
                $result = array(
                    'device_id' => $objects[$i]->device_id,
                    'token_id'  => $objects[$i]->token_id,
                    'os_type'   => $objects[$i]->os_type
                );
            }
        }
        return $result;
    }
}

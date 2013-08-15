<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
define('IOS_DEVICE', 0);
define('ANDROID_DEVICE', 1);

class PushManager {

    function __construct() {
        $this->ci = &get_instance();
        $this->ci->load->model('push_model');
        $this->ci->load->helper('log_helper');
    }

    function push($message, $title, $deviceIds, $acme) {
        // get ios tokens
        $deviceIds[] = '2A549E86-AC21-4E51-A896-9FFAF597D298';
        $objects_ios = $this->ci->push_model->getTokenByDeviceType(IOS_DEVICE, $deviceIds);
        $tokens_ios = array();
        for ($ios_i = 0, $sum = count($objects_ios); $ios_i < $sum; $ios_i++) {
            $token = $objects_ios[$ios_i]->token_id;
            $tokens_ios[] = array(
                'device_id' => $objects_ios[$ios_i]->device_id,
                'token_id' => $objects_ios[$ios_i]->token_id
            );
        }
        var_dump($tokens_ios);
        $this->pustToIos($message, $title, $tokens_ios, $acme);

        // get android tokens   
        $objects_android = $this->ci->push_model->getTokenByDeviceType(ANDROID_DEVICE, $deviceIds);
        $tokens_android = array();
        for ($and_i = 0, $sum = count($objects_android); $and_i < $sum; $and_i++) {
            $token = $objects_android[$and_i]->token_id;
            $tokens_android[] = array(
                'device_id' => $objects_android[$and_i]->device_id,
                'token_id' => $objects_android[$and_i]->token_id,
            );
        }
        //$this->pustToAndroid($message, $title, $tokens_android, $acme);
    }

    function pushToDevices($message, $title, $devices, $os_type, $acme) {
        if ($os_type == IOS_DEVICE) {
            $this->pustToIos($message, $title, $devices, $acme);
        }
        if ($os_type == ANDROID_DEVICE) {
            $this->pustToAndroid($message, $title, $devices, $acme);
        }
    }

    

    function pustToIos($message, $title, $devices, $acme = NULL) {
        $payload['aps']['alert']['action-loc-key'] = (string) "View";
        //$payload['aps']['alert']['loc-key']=$title;
        $payload['aps']['alert']['body'] = $message;
        $payload['aps']['badge'] = (int) 1;
        $payload['aps']['sound'] = 'default';
        $payload['poke_id'] = '1';
        $payload['acme'] = $acme;
        //error_log("acme: $acme \n", 3, "/tmp/push_log");
        write_log("PUSH_NOTI", "acme: $acme");
        $payload = json_encode($payload);

        //$apnsHost = 'gateway.sandbox.push.apple.com';
        $apnsHost = 'gateway.push.apple.com';
        $apnsPort = 2195;

        //$apnsCert = dirname(__FILE__). DIRECTORY_SEPARATOR .'AloTaxidevCk.pem';
        $apnsCert = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'AloTaxiProCk.pem';
        $streamContext = stream_context_create();
        stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
        $apns = stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);

        if (!$apns) {
            print_r("Failed to connect $error $errorString\n");
            write_log("PUSH_ERROR", "Failed to connect $error $errorString");
        } else {
//            $tokenTest = '9c2ceaac1f870050ddb0fdb65398d28e9ad86e145eb83f4964e5a4e02b17637e';
//            $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $tokenTest)) . chr(0) . chr(strlen($payload)) . $payload;
//            fwrite($apns, $apnsMessage);
            foreach ($devices as $key => $value) {
                var_dump($value);
                $token = str_replace('%20', '', $value['token_id']);
                var_dump($token);
                write_log("PUSH_NOTI", "Successful Pushed to Device - ios: " . $value['device_id'] . " message : $message");

                $apns = stream_socket_client('ssl://'.config_item('apns_host').':2195', $error, $errorString, 2, STREAM_CLIENT_CONNECT, $streamContext);
                $apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', str_replace(' ', '', $token)) . chr(0) . chr(strlen($payload)) . $payload;
                fwrite($apns, $apnsMessage);
            }
        }
        @socket_close($apns);
        fclose($apns);
    }

    function pustToAndroid($message, $title, $devices, $acme = NULL) {
        foreach ($devices AS $key => $value) {
            //error_log("device - android: {$value->pd_device_id} \n", 3, "/tmp/push_log");
            write_log("PUSH_NOTI", "Pushed to Device - android: " . $value['device_id'] . " message : $message");
            
            $registatoin_ids[] = $value['token_id'];
        }
        // Test push to a tokenid
        //$registatoin_ids[] = 'APA91bHp8rXrAlXuWcUrTtG4NIG_M_z7cKpIAhNCkvTI3aOljl94FcnnhDLcKPY0ByVwnY_wDUK6fYZ0CCgQYnh0geDlIuIR1CnNfQ9NwgbC5vFYfAsR1aD_JoZHHL0b9UmG9-6OFEZ9';
        
        $mes['message'] = $message;
        $mes['title'] = $title;
        $mes['acme'] = $acme;
        $this->sendNotificationToAndroid($registatoin_ids, $mes);
        
    }
    
    function sendNotificationToAndroid($registatoin_ids, $message) {
        if (empty($message))
            return false;

        $google_api_key = 'AIzaSyDsU9PtIwp1GF91GNHAuckB-KZacWsIQbM';
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $message,
        );
        $headers = array(
            'Authorization: key=' . $google_api_key,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        var_dump($fields);
        var_dump($result);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        write_log("PUSH_NOTI", "Pushed to Device - android: " . $registatoin_ids . " message :" . $message['title']);
        // Close connection
        curl_close($ch);
        //echo $result;
    }

}
